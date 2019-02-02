<?php namespace App\Model; 

use MongoDB\BSON\ObjectId;

class Recipe extends Model{ 
 
    public function __construct() { 
        parent::__construct('recipes'); 
    } 
 
    public function findByName($name) { 
        return $this->client->find(['name' => $name]); 
    } 
 
    public function findById($id) { 
        $recipe = $this->client->findOne(['_id' => new ObjectId($id)]);
        $recipe->id = $id;

        unset($recipe->_id);

        return (array) $recipe; 
    } 

    public function update($recipe) {
        return $this->client->updateOne(['_id' => new ObjectId($recipe->id)] , ['$set' => (array) $recipe]);
    }
 
    public function save() { 
        $recipe = $this->client->insertOne($this->getAttributes()); 
        $this->attributes->id = (string) $recipe->getInsertedId(); 
        return (array) $this->getAttributes(); 
    } 

    public function delete($id) {
        return $this->client->deleteOne(['_id' => new ObjectId($id)]);
    }

    public function findAll() { 
        $recipes = []; 
        $rows = $this->client->find(); 
         
        foreach($rows as $recipe) { 
            $recipe['_id'] =  (string) $recipe['_id']; 
            $recipes[] = $recipe; 
        } 
         
        return $recipes; 
    }

    public function find($criteria) {
        $recipes = [];

        if(isset($criteria->name))
            $criteria->name = ['$regex' => $criteria->name, '$options'=>'i'];

        $rows = $this->client->find($criteria);
        
        foreach($rows as $recipe) { 
            $recipe['_id'] =  (string) $recipe['_id']; 
            $recipes[] = $recipe; 
        } 
         
        return $recipes;        
    }
}