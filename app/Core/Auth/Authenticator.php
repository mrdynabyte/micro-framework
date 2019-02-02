<?php namespace Core\Auth;

use Core\Auth\JWTManager;
use Core\Http\Response;

class Authenticator {
    public static function authenticate($email, $password) {    
        $token = JWTManager::createToken($email, $password);
        return new Response(['token' => $token->getPayload() . '.']); //Library bug. Token needs a dot (.) attached at the end for further parsing
    }

    public static function authFromToken($authHeader) {
        
        if(empty($authHeader))
            return new Response([], 401, 'Token not provided');

        $parts = explode(' ', $authHeader);
        $token = JWTManager::parseToken(array_pop($parts));
        $user  = JWTManager::getUser($token);
        
        if(!$user || $token->getClaim('password') != $user->password)
            return new Response([], 403, 'Invalid email or password');
        return new Response($user);
    }
}