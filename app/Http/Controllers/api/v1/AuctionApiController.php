<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Requests\AuctionDestroyRequest;
use App\Http\Requests\AuctionStoreRequest;
use App\Http\Requests\AuctionUpdateRequest;
use App\Models\Auction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuctionApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auctions = Auction::with('author')->get();
        return response()->json($auctions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuctionStoreRequest $request)
    {
        $validated = $request->validated();

        $auction = new Auction();
        $auction->name = $validated->name;
        $auction->description = $validated->description;
        $auction->starting_price = $validated->has('starting_price') ? $validated->starting_price : 0;
        $auction->end_at = $validated->has('end_at') ? $validated->end_at : now()->addDays(7);
        $auction->author_id = auth()->id();
        $auction->save();

        return response()->json($auction);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $auction = Auction::findOrFail($id);
        $auction->load('author');
        return response()->json($auction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, AuctionUpdateRequest $request)
    {
        $validated = $request->validated();

        $auction = Auction::findOrFail($id);
        $auction->update($validated);
        $auction->save();
        return response()->json($auction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auction $auction, AuctionDestroyRequest $request)
    {
        $request->validated();

        $auction->delete();
        return response()->json(['message' => 'Auction deleted']);
    }
}
