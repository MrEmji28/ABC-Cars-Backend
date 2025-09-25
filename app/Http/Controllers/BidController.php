<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Car;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function index()
    {
        return auth()->user()->bids()->with(['car', 'user'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $car = Car::findOrFail($request->car_id);
        
        if ($car->user_id === auth()->id()) {
            return response()->json(['message' => 'Cannot bid on your own car'], 422);
        }

        $bid = Bid::create([
            'car_id' => $request->car_id,
            'user_id' => auth()->id(),
            'amount' => $request->amount,
        ]);

        return response()->json($bid->load(['car', 'user']), 201);
    }

    public function show(Bid $bid)
    {
        return $bid->load(['car', 'user']);
    }

    public function update(Request $request, Bid $bid)
    {
        if ($bid->car->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $bid->update(['status' => $request->status]);
        
        if ($request->status === 'accepted') {
            $bid->car->update(['status' => 'sold']);
            Bid::where('car_id', $bid->car_id)
                ->where('id', '!=', $bid->id)
                ->update(['status' => 'rejected']);
        }

        return $bid->load(['car', 'user']);
    }

    public function destroy(Bid $bid)
    {
        if ($bid->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $bid->delete();
        return response()->json(['message' => 'Bid deleted successfully']);
    }
}
