<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(version="1.0", title="API Documentation & Sandbox")
 * @OA\Components(securitySchemes={@OA\SecurityScheme(type="http", name="Authorization", securityScheme="bearerAuth", scheme="bearer", bearerFormat="JWT")})
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
