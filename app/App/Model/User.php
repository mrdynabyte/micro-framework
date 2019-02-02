<?php namespace App\Model;

use Core\Util\Config;
use MongoDB\Client;

class User extends Model {
    public function __construct() {
        parent::__construct('users'); 
    }

    public function findByEmail($email) {
        return $this->client->findOne(['email' => $email]);
    }

    public function findById($id) {
        return $this->client->findOne(['_id' => $id]);
    }

    public function create($user) {
        return $this->client->insertOne($user);
    }

    public function deleteByEmail($email) {
        return $this->client->deleteOne(['email' => $email]);
    }

}