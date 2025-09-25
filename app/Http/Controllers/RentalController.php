<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Car;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        return auth()->user()->rentals()->with(['car', 'user'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $car = Car::findOrFail($request->car_id);
        
        if (!in_array($car->type, ['rental', 'both'])) {
            return response()->json(['message' => 'Car is not available for rental'], 422);
        }

        if ($car->user_id === auth()->id()) {
            return response()->json(['message' => 'Cannot rent your own car'], 422);
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1;
        $totalAmount = $days * $car->rental_price_per_day;

        $rental = Rental::create([
            'car_id' => $request->car_id,
            'user_id' => auth()->id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_amount' => $totalAmount,
        ]);

        return response()->json($rental->load(['car', 'user']), 201);
    }

    public function show(Rental $rental)
    {
        return $rental->load(['car', 'user']);
    }

    public function update(Request $request, Rental $rental)
    {
        if ($rental->car->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        $rental->update(['status' => $request->status]);
        return $rental->load(['car', 'user']);
    }

    public function destroy(Rental $rental)
    {
        if ($rental->user_id !== auth()->id() && $rental->car->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $rental->delete();
        return response()->json(['message' => 'Rental deleted successfully']);
    }
}
