<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Log;

class AIFoodController extends Controller
{
    /**
     * Generate food suggestions using Google Gemini API.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'mood' => 'required|string',
            'preference' => 'required|string',
            'budget' => 'required|string',
        ]);

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            // Fallback mock data based on preference
            return response()->json($this->getFallbackData($request->preference));
        }

        $prompt = "Suggest 3 specific food dishes (available in Malaysia) for someone with a budget of {$request->budget}, feeling {$request->mood}, and craving {$request->preference} food. Only output a valid JSON array of objects with 'name' and 'description' keys. Do not include markdown formatting or backticks.";

        try {
            // Disable SSL verification temporarily in case Laragon doesn't have a valid cacert.pem
            $response = Http::withoutVerifying()->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
                
                // Clean up potential markdown formatting from Gemini response
                $text = str_replace(['```json', '```'], '', $text);
                $suggestions = json_decode(trim($text), true);

                if (is_array($suggestions)) {
                    return response()->json($suggestions);
                }
            } else {
                return response()->json([
                    ['name' => 'API Error', 'description' => $response->body()]
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                ['name' => 'System Error', 'description' => $e->getMessage()]
            ]);
        }

        // If we reach here, something else failed
        return response()->json([
            ['name' => 'Parsing Error', 'description' => 'Failed to parse Gemini response']
        ]);
    }

    private function getFallbackData($preference)
    {
        $foods = [
            'Malay' => [
                ['name' => 'Nasi Lemak', 'description' => 'Classic Malaysian dish with coconut rice.'],
                ['name' => 'Nasi Goreng Kampung', 'description' => 'Spicy traditional fried rice.'],
                ['name' => 'Ayam Masak Merah', 'description' => 'Chicken cooked in sweet and spicy tomato sauce.']
            ],
            'Western' => [
                ['name' => 'Chicken Chop', 'description' => 'Grilled or fried chicken with black pepper sauce.'],
                ['name' => 'Beef Burger', 'description' => 'Juicy beef patty with cheese and fries.'],
                ['name' => 'Spaghetti Carbonara', 'description' => 'Creamy pasta with beef bacon.']
            ],
            'Japanese' => [
                ['name' => 'Sushi Platter', 'description' => 'Fresh assortment of sushi and sashimi.'],
                ['name' => 'Chicken Katsu Curry', 'description' => 'Crispy chicken with Japanese curry and rice.'],
                ['name' => 'Ramen', 'description' => 'Hot noodle soup with rich broth.']
            ],
            'Chinese' => [
                ['name' => 'Dim Sum', 'description' => 'Assortment of steamed bite-sized dishes.'],
                ['name' => 'Chicken Rice', 'description' => 'Roasted or steamed chicken with fragrant rice.'],
                ['name' => 'Char Kway Teow', 'description' => 'Stir-fried flat rice noodles.']
            ],
            'Korean' => [
                ['name' => 'Bibimbap', 'description' => 'Mixed rice with meat and assorted vegetables.'],
                ['name' => 'Tteokbokki', 'description' => 'Spicy stir-fried rice cakes.'],
                ['name' => 'Korean Fried Chicken', 'description' => 'Crispy fried chicken glazed with sweet and spicy sauce.']
            ],
            'Fast Food' => [
                ['name' => 'Fried Chicken Combo', 'description' => 'Crispy fried chicken with fries and drink.'],
                ['name' => 'Double Cheeseburger', 'description' => 'Classic double patty burger with cheese.'],
                ['name' => 'Pizza', 'description' => 'Cheesy pizza with various toppings.']
            ]
        ];

        // Find the matching preference, ignoring case/spacing
        foreach ($foods as $key => $options) {
            if (stripos($preference, $key) !== false) {
                return $options;
            }
        }

        return $foods['Malay']; // Default
    }

    /**
     * Find Top 5 restaurants matching the selected food and geolocation.
     */
    public function recommendRestaurants(Request $request)
    {
        $request->validate([
            'selected_food' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'mood' => 'nullable|string',
        ]);

        $userLat = $request->latitude;
        $userLon = $request->longitude;
        $selectedFood = $request->selected_food;
        $apiKey = env('GOOGLE_MAPS_API_KEY');

        if (!$apiKey) {
            return back()->with('error', 'Google Maps API Key is missing. Please configure it in .env.');
        }

        try {
            $response = Http::withoutVerifying()->get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
                'location' => "{$userLat},{$userLon}",
                'radius' => 10000, // 10km radius
                'type' => 'restaurant',
                'keyword' => $selectedFood,
                'key' => $apiKey
            ]);

            $restaurants = [];

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['results'])) {
                    // Take top 5 results
                    $results = array_slice($data['results'], 0, 5);
                    
                    foreach ($results as $place) {
                        $restaurants[] = (object) [
                            'restaurant_name' => $place['name'] ?? 'Unknown Restaurant',
                            'address' => $place['vicinity'] ?? 'Address not available',
                            'rating' => $place['rating'] ?? 'N/A',
                            'user_ratings_total' => $place['user_ratings_total'] ?? 0,
                            'latitude' => $place['geometry']['location']['lat'] ?? null,
                            'longitude' => $place['geometry']['location']['lng'] ?? null,
                            'place_id' => $place['place_id'] ?? null,
                            // Calculate straight-line distance roughly
                            'distance' => $this->calculateDistance($userLat, $userLon, $place['geometry']['location']['lat'], $place['geometry']['location']['lng'])
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Google Places API Error: ' . $e->getMessage());
        }

        $moodName = $request->mood ?? 'Happy';

        return view('recommendations.top5', compact('restaurants', 'selectedFood', 'moodName', 'userLat', 'userLon'));
    }

    /**
     * Calculate Haversine distance in KM
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return round($miles * 1.609344, 2); // Convert to KM
    }
}
