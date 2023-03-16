<?php

namespace App\Http\Controllers;

use Illuminate\{
    Foundation\Auth\Access\AuthorizesRequests,
    Foundation\Bus\DispatchesJobs,
    Foundation\Validation\ValidatesRequests,
    Routing\Controller as BaseController,
    Validation\ValidationException,
    Validation\Validator
};

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @throws ValidationException
     */
    public function throwIfInvalid(Validator $validator)
    {
        if ($validator->valid()) {
            return;
        }

        throw new ValidationException($validator);
    }
}
