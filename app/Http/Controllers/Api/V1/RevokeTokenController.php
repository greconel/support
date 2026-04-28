<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;

use function app;
use function response;

class RevokeTokenController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$request->bearerToken()){
            return response()->json([
                'message' => 'No Bearer token detected'
            ], 400);
        }

        $tokenId = (new Parser(new JoseEncoder()))->parse($request->bearerToken())->claims()->all()['jti'];

        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        $tokenRepository->revokeAccessToken($tokenId);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);

        return response()->json([
            'message' => 'Token revoked successfully'
        ]);
    }
}
