<?php

namespace Wp\Sfb\Auth;

use Wp\Sfb\Model\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Wp\Sfb\Util\UserRepository;

class PermissionHandler
{
    public static function generateJwtFromUserName(string $username): ?string {
        $uRepo = new UserRepository();
        $user = $uRepo->getByName($username);
        if ($user) {
            return JWT::encode($user->toArray(), JWT_SECRET, 'HS256');
        }
        return null;
    }

    private static function extractJwtFromRequest(): string {
        $matches = [];
        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            header('HTTP/1.0 400 Bad Request');
            echo 'Token not found in request';
            exit;
        }
        $jwt = $matches[1];
        if (! $jwt) {
            // No token was able to be extracted from the authorization header
            header('HTTP/1.0 400 Bad Request');
            exit;
        }
        return $jwt;
    }

    private static function validateAndExtractJwt(string $jwtToken): ?array {
        $data = JWT::decode($jwtToken, new Key(JWT_SECRET, 'HS256'));
        return (array)$data;
    }

    public static function getAuthenticatedUser(): User {
        $jwtToken = self::extractJwtFromRequest();
        $jwtData = self::validateAndExtractJwt($jwtToken);

        if (!$jwtData) {
            http_response_code(403);
            exit(0);
        }

        $user = new User(
            $jwtData['user_id'],
            $jwtData['user'],
            $jwtData['full_name'],
            $jwtData['group_id'],
            null,
            User::packPermissions($jwtData['permissions'])
        );

        return $user;
    }
}