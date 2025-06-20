<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auction\AuctionDestroyRequest;
use App\Http\Requests\Auction\AuctionFilteredIndexRequest;
use App\Http\Requests\Auction\AuctionStoreRequest;
use App\Http\Requests\Auction\AuctionUpdateRequest;
use App\Models\Auction;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class AuctionApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuctionFilteredIndexRequest $request)
    {
        $validated = $request->validated();
        $filter = $validated['filter'] ?? '';
        $auctions = Auction::query()
            ->whereDate('end_at', '>', now())
            ->with('author')
            ->with('images')
            ->withCount('offers');

        $search = $validated['search'] ?? null;
        if ($search) {
            $auctions = $auctions->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('description', 'like', "%$search%") ;
            });
        }

        if ($filter == 'endsoon') {
            $auctions = $auctions->orderBy('end_at', 'asc');
        } elseif ($filter == 'new') {
            $auctions = $auctions->orderBy('created_at', 'desc');
        } elseif ($filter == 'highest') {
            // Utilisation d'une sous-requête pour trier par l'offre la plus élevée
            $auctions = $auctions->leftJoin('offers', function ($join) {
                $join->on('auctions.id', '=', 'offers.auction_id');
            })
                ->select('auctions.*', \DB::raw('MAX(offers.price) as highest_offer'))
                ->groupBy('auctions.id')
                ->orderByRaw('COALESCE(highest_offer, 0) DESC');
        }
        return response()->json($auctions->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuctionStoreRequest $request)
    {
        $validated = $request->validated();
        $auction = Auction::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'starting_price' => $validated['starting_price'] ?? 0,
            'end_at' => $validated['end_at'] ?? now()->addDays(7),
            'author_id' => auth()->id(),
        ]);
        if ($request->hasFile('images')) {
            var_dump(var_export($request->allFiles("images")));
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');

                $model = Image::create([
                    'auction_id' => $auction->id,
                    'path' => $path,
                    'url' => url(Storage::url($path)),
                ]);
                $model->save();
            }
        }


        return response()->json($auction->load('images'));

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $auction = Auction::findOrFail($id);
        $auction->load('author');
        $auction->load('images');
        $auction->load('offers');
        $auction->offers->load('author');
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

        if ($request->hasFile('images')) {
            // Delete existing images
            foreach ($auction->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }

            // Store new images
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                Image::create([
                    'auction_id' => $auction->id,
                    'path' => $path,
                    'url' => url(Storage::url($path)),
                ])->save();
            }
        }


        return response()->json($auction->load('images'));
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
