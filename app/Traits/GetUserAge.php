<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

trait GetUserAge
{
    public function getUserAge($request)
    {
        // make carbon object from dob
        $dateOfBirth = Carbon::parse($request->dob_ad);

        // get today date
        $todayDate = Carbon::now();

        // get date difference in years
        $years = $dateOfBirth->diffInYears($todayDate);

        // set age parameter
        $age = ($request->age < $years || $request->age > $years)
            ? $years
            : $request->age;

        // throw error if age exceeds 125 if fails to pass validation
        if ($age > 125)
            throw ValidationException::withMessages([
                'age' => 'Age cannot be more than 125 years'
            ]);

        return $age;
    }
}
