<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ItemHighestBidRequest extends FormRequest
{
    // to have access to GET parameters here
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['id'] = $this->route('id');

        return $data;
    }

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
            'id' => [
                'required',
                Rule::exists('items', 'id')->where(function ($builder) {
                    $builder->where('id', $this->id)
                        ->whereNull('deleted_at');
                })
            ]
        ];
    }
}
