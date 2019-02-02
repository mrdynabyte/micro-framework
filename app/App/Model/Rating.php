<?php namespace App\Model; 

use MongoDB\BSON\ObjectId;

class Rating extends Model{

    public function __construct() {
        parent::__construct('ratings');
    }

    public function save() { 
        $rating = $this->client->insertOne($this->getAttributes());
        $this->attributes->id = (string) $rating->getInsertedId();         
        return (array) $this->getAttributes(); 
    }
}