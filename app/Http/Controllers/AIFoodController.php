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
            // Fallback mock data if no API key is set for testing
            return response()->json([
                ['name' => 'Nasi Lemak Ayam Goreng', 'description' => 'Classic Malaysian comfort food matching your budget and mood.'],
                ['name' => 'Tom Yam Seafood', 'description' => 'Spicy and sour soup to wake you up.'],
                ['name' => 'Ayam Gunting', 'description' => 'Quick, budget-friendly and delicious fast food snack.']
            ]);
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
        return response()->json([
            ['name' => 'Nasi Goreng Kampung', 'description' => 'Spicy traditional fried rice.'],
            ['name' => 'Mee Goreng Mamak', 'description' => 'Flavorful fried noodles.'],
            ['name' => 'Burger Ramly', 'description' => 'Classic local street burger.']
        ]);
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
              ->orWhere('foods.food_category', 'LIKE', "%{$selectedFood}%");
              
            foreach($keywords as $word) {
                if(strlen($word) > 3) { // Ignore short words like 'and', 'the', 'of'
                    $q->orWhere('foods.food_name', 'LIKE', "%{$word}%")
                      ->orWhere('foods.food_category', 'LIKE', "%{$word}%");
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
