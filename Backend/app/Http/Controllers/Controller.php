<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

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
