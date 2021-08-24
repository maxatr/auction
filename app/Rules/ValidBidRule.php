<?php

namespace App\Rules;

use App\Services\BidService;
use Illuminate\Contracts\Validation\Rule;

class ValidBidRule implements Rule
{
    protected $bidAmount;
    protected $itemId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($bidAmount, $itemId)
    {
        $this->bidAmount = $bidAmount;
        $this->itemId = $itemId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (new BidService())->isValidBidAmount($this->bidAmount, $this->itemId);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Incorrect bid amount';
    }
}
