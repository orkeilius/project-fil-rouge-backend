<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserAuctionFilteredIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filter' => ['string','in:ongoing,ended,won'],
        ];
    }
}
