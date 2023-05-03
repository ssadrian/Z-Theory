<?php

namespace App\Http\Controllers;

use Illuminate\{
    Foundation\Auth\Access\AuthorizesRequests,
    Foundation\Bus\DispatchesJobs,
    Foundation\Validation\ValidatesRequests,
    Routing\Controller as BaseController,
    Http\Response
};

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function forbidden(): Response
    {
        return response([
            'message' => 'Access forbidden'
        ], Response::HTTP_FORBIDDEN);
    }
}
