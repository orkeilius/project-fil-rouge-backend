<?php

namespace App\Http\Requests\Auction;

use Illuminate\Foundation\Http\FormRequest;

class AuctionFilteredIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filter' => ['string','in:ongoing,ended,won'],
        ];
    }
}
