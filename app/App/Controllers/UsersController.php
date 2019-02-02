<?php namespace App\Controllers;

use Core\Http\Response;
use App\Model\User;

class UsersController extends BaseController {
    public function new($data) {
        $model = new User;
        $input = $data['input'];        
        
        if(!isset($input->email) || !isset($input->password))
            return new Response([], 400, 'Missing fields');

        if($this->client->users->findOne(['email' => $input->email]))
            return new Response([], 400, 'User already exists');            

        $userData = [
            'email' => $input->email,
            'password' => hash('sha256', $input->password)
        ];

        $model->create($userData);

        return new Response($userData);
    }

    public function deleteByEmail($data) {
        $input = $data['input'];

        if(!$input->email)
            return new Response([], 400, 'No email specified');

        $user = (new User)->deleteByEmail($input->email);

        return new Response('OK');

    }
}   