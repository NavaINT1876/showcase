<?php

namespace App\Http\Controllers;

use PHPOpenSourceSaver\JWTAuth\Providers\JWT\Namshi as JwtEncoder;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/token",
     *     summary="Generates Json Web Token",
     *     @OA\Response(
     *         response=200,
     *         description="Token"
     *     )
     * )
     */
    public function index(JwtEncoder $encoder)
    {
        $token = $encoder->encode([
            'sub' => time(),
            'name' => 'NuduroMaro',
            'iat' => time(),
            '_showcase_is_awesome_' => true
        ]);

        return response()->json(['token' => $token], ResponseAlias::HTTP_OK);
    }
}
