<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Mood;
use App\Models\Restaurant;
use App\Models\Recommendation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    public function suggest(Request $request)
    {
        $request->validate([
            'mood' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Clean mood string (remove emojis)
        $moodName = trim(preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $request->mood));
        if (empty($moodName)) {
            $moodName = 'Happy'; // Fallback
        }

        // Find or create mood
        $mood = Mood::firstOrCreate(
            ['mood_name' => $moodName],
            ['description' => 'User selected mood: ' . $moodName]
        );

        // Map mood to food category (you can adjust this as needed)
        $categoryMapping = [
            'Happy' => ['Dessert', 'Fast Food', 'Western', 'Cafe'],
            'Tired' => ['Coffee', 'Cafe', 'Snacks', 'Local'],
            'Sad' => ['Comfort Food', 'Dessert', 'Ice Cream', 'Fast Food'],
            'Excited' => ['Spicy', 'Korean', 'Japanese', 'Western', 'BBQ'],
            'Angry' => ['Spicy', 'Fast Food', 'Burger', 'Mexican'],
            'Stressed' => ['Comfort Food', 'Sweet', 'Dessert', 'Bakery'],
        ];

        // Use array_keys to do a loose match if needed, but direct match is fine since we know the buttons
        $categories = $categoryMapping[$moodName] ?? ['Local', 'Western', 'Asian', 'Fast Food'];

        $userLat = $request->latitude;
        $userLon = $request->longitude;

        // Haversine formula
        $haversine = "(6371 * acos(cos(radians($userLat)) 
                     * cos(radians(restaurants.latitude)) 
                     * cos(radians(restaurants.longitude) - radians($userLon)) 
                     + sin(radians($userLat)) 
                     * sin(radians(restaurants.latitude))))";

        // Query to find best food based on category and distance
        $food = Food::join('restaurants', 'foods.restaurant_id', '=', 'restaurants.restaurant_id')
            ->whereIn('foods.food_category', $categories)
            ->whereNotNull('restaurants.latitude')
            ->whereNotNull('restaurants.longitude')
            ->select('foods.*', 'restaurants.restaurant_name', 'restaurants.address', 'restaurants.latitude', 'restaurants.longitude', 'restaurants.contact_number')
            ->selectRaw("{$haversine} AS distance")
            ->orderBy('distance')
            ->first();

        // Fallback if no specific category matches
        if (!$food) {
            $food = Food::join('restaurants', 'foods.restaurant_id', '=', 'restaurants.restaurant_id')
                ->whereNotNull('restaurants.latitude')
                ->whereNotNull('restaurants.longitude')
                ->select('foods.*', 'restaurants.restaurant_name', 'restaurants.address', 'restaurants.latitude', 'restaurants.longitude', 'restaurants.contact_number')
                ->selectRaw("{$haversine} AS distance")
                ->orderBy('distance')
                ->first();
        }

        if (!$food) {
            return back()->with('error', 'No restaurants found nearby. Please ensure restaurants have geolocation data in the system.');
        }

        // Save recommendation
        $recommendation = Recommendation::create([
            'user_id' => Auth::id() ?? 1, // Fallback to 1 if testing without auth
            'food_id' => $food->food_id ?? $food->id,
            'mood_id' => $mood->mood_id ?? $mood->id,
            'budget' => $food->price ?? 0,
            'recommendation_score' => 5.0,
        ]);

        return view('recommendations.show', compact('food', 'moodName', 'recommendation', 'userLat', 'userLon'));
    }
}
