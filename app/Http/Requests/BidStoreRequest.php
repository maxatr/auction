<?php

namespace App\Http\Requests;

use App\Rules\ValidBidRule;
use Illuminate\Foundation\Http\FormRequest;

class BidStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'bid_amount' => 'required|numeric|gt:0',
            'item_id' => [
                'required',
                'exists:items,id',
                new ValidBidRule($this->bid_amount, $this->item_id)
            ],
        ];
    }
}
