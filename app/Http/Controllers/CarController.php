<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with('user');

        if ($request->has('brand')) {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }

        if ($request->has('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        return $query->get();
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
