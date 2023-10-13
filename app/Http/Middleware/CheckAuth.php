<?php

namespace App\Http\Middleware;

use App\Exceptions\ShowcaseIsNotAwesomeException;
use Closure;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CheckAuth extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $this->checkForToken($request);

            $jwt = $this->auth->parseToken();

            $isAwesome = $jwt->getClaim('_showcase_is_awesome_');
            if (!$isAwesome) {
                throw new ShowcaseIsNotAwesomeException(
                    'Please make sure Showcase is really awesome in your mind and repeat the request.'
                );
            }

            $this->auth->parseToken()->manager()->decode($jwt->getToken());

            return $next($request);
        } catch (ShowcaseIsNotAwesomeException $exception) {
            return response()->json(['errors' => $exception->getMessage()], ResponseAlias::HTTP_UNAUTHORIZED);
        } catch (UnauthorizedHttpException|TokenInvalidException $exception) {
            return response()->json(['errors' => 'Unauthorized'], ResponseAlias::HTTP_UNAUTHORIZED);
        } catch (\Throwable $exception) {
            Log::error($exception, ['exception' => $exception->getTraceAsString()]);

            return response()->json(['errors' => 'Something went wrong'], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
