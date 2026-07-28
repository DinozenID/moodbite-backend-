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
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
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
            }
        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
        }

        // Fallback if API fails
        return response()->json($this->getFallbackData($request->preference));
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

        // Haversine formula for distance calculation (in km)
        $haversine = "(6371 * acos(cos(radians($userLat)) 
                     * cos(radians(restaurants.latitude)) 
                     * cos(radians(restaurants.longitude) - radians($userLon)) 
                     + sin(radians($userLat)) 
                     * sin(radians(restaurants.latitude))))";

        // Create keywords from selected food for a broader match
        $keywords = explode(' ', $selectedFood);
        
        $query = Food::join('restaurants', 'foods.restaurant_id', '=', 'restaurants.restaurant_id')
            ->whereNotNull('restaurants.latitude')
            ->whereNotNull('restaurants.longitude')
            ->select('foods.*', 'restaurants.restaurant_name', 'restaurants.address', 'restaurants.latitude', 'restaurants.longitude', 'restaurants.contact_number')
            ->selectRaw("{$haversine} AS distance");

        // Try to match keywords in food name or category
        $query->where(function($q) use ($keywords, $selectedFood) {
            $q->where('foods.food_name', 'LIKE', "%{$selectedFood}%")
              ->orWhere('foods.food_category', 'LIKE', "%{$selectedFood}%")
              ->orWhere('restaurants.restaurant_category', 'LIKE', "%{$selectedFood}%");
              
            foreach($keywords as $word) {
                if(strlen($word) > 3) { // Ignore short words like 'and', 'the', 'of'
                    $q->orWhere('foods.food_name', 'LIKE', "%{$word}%")
                      ->orWhere('foods.food_category', 'LIKE', "%{$word}%")
                      ->orWhere('restaurants.restaurant_category', 'LIKE', "%{$word}%");
                }
            }
        });

        $restaurants = $query->orderBy('distance')
            ->take(5)
            ->get();

        // If no match found, fallback to closest restaurants regardless of food
        if ($restaurants->isEmpty()) {
            $restaurants = Food::join('restaurants', 'foods.restaurant_id', '=', 'restaurants.restaurant_id')
                ->whereNotNull('restaurants.latitude')
                ->whereNotNull('restaurants.longitude')
                ->select('foods.*', 'restaurants.restaurant_name', 'restaurants.address', 'restaurants.latitude', 'restaurants.longitude', 'restaurants.contact_number')
                ->selectRaw("{$haversine} AS distance")
                ->orderBy('distance')
                ->take(5)
                ->get();
        }

        $moodName = $request->mood ?? 'Happy';

        return view('recommendations.top5', compact('restaurants', 'selectedFood', 'moodName', 'userLat', 'userLon'));
    }
}
