<?php namespace Core\Auth;

use App\Model\User;
use Core\Util\Config;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;

class JWTManager {
    public static function createToken($email, $password) {
        //TODO: SIGN Token using built-in parser        
        return (new Builder())->setIssuer(Config::get('APP_URL'))
                                ->setAudience(Config::get('APP_URL')) 
                                ->setId(substr(base64_encode(sha1(mt_rand())), 0, 16), true)
                                ->setIssuedAt(time())
                                ->setExpiration(time() + 3600) 
                                ->set('email', $email)
                                ->set('password', hash('sha256', $password)) 
                                ->getToken();        
    }

    public static function parseToken($jwt_string) {   
        return (new Parser())->parse($jwt_string);
    }

    public static function getUser($token) {
        return (new User)->findByEmail($token->getClaim('email'));
    }
}