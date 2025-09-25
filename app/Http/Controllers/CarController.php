<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        return Car::with('user')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'required|numeric|min:0',
            'rental_price_per_day' => 'nullable|numeric|min:0',
            'type' => 'required|in:sale,rental,both',
            'image_url' => 'nullable|url',
        ]);

        $car = Car::create([
            'user_id' => auth()->id(),
            ...$request->validated()
        ]);

        return response()->json($car->load('user'), 201);
    }

    public function show(Car $car)
    {
        return $car->load(['user', 'bids.user']);
    }

    public function update(Request $request, Car $car)
    {
        if ($car->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'brand' => 'string|max:255',
            'model' => 'string|max:255',
            'year' => 'integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'numeric|min:0',
            'rental_price_per_day' => 'nullable|numeric|min:0',
            'type' => 'in:sale,rental,both',
            'status' => 'in:available,sold,rented',
            'image_url' => 'nullable|url',
        ]);

        $car->update($request->validated());
        return $car->load('user');
    }

    public function destroy(Car $car)
    {
        if ($car->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $car->delete();
        return response()->json(['message' => 'Car deleted successfully']);
    }
}
