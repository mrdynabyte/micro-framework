<?php namespace App\Controllers;

use Core\Http\Response;
use Core\Auth\Authenticator;
use App\Model\User;

class AuthController extends BaseController {
    public function auth($data) {
        $model = new User;
        
        $input = $data['input'];

        if(!isset($input->email) || !isset($input->password))
            return new Response([], 400, 'You must provide valid credentials');

        $user = $model->findByEmail($input->email);

        if(!$user || hash('sha256', $input->password) != $user->password)
            return new Response([], 400, 'Invalid email or password');
        
        return Authenticator::authenticate($input->email, $input->password);
    }
}