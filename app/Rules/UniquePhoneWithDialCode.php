<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniquePhoneWithDialCode implements Rule
{
    private $userId;
    private $dialCode;

    public function __construct($userId, $dialCode)
    {
        $this->userId = $userId;
        $this->dialCode = '+'.$dialCode;
    }

    public function passes($attribute, $value)
    {
        $numericValue = preg_replace("/[^0-9]/", "", $value);

        // Query the database to retrieve the phone numbers
        $dbPhoneNumbers = User::where('id', '!=', $this->userId)
            ->where('dial_code', $this->dialCode)
            ->pluck('phone')
            ->toArray();

        // Format the database phone numbers to match the sanitized input
        $formattedDbPhoneNumbers = array_map(function($phoneNumber) {
            return preg_replace("/[^0-9]/", "", $phoneNumber);
        }, $dbPhoneNumbers);

        // Check if the sanitized input matches any of the database phone numbers
        return !in_array($numericValue, $formattedDbPhoneNumbers);
    }



    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The phone number already exists.';
    }
}
