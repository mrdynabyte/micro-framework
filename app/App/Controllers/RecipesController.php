<?php namespace App\Controllers;

use Core\Http\Response;
use App\Model\Recipe;
use App\Model\Rating;
 
class RecipesController { 

    public function list() { 
        return new Response((array) (new Recipe)->findAll()); 
    }

    public function create($data) { 
     
        $input = $data['input']; 
 
        if(!isset($input->name) || !isset($input->prep_time) || !isset($input->difficulty) || !isset($input->vegetarian)) 
            return ['error' => 'Missing fields', 'status' => 404]; 
 
        $recipe = new Recipe; 
        $recipe->setAttributes($input); 
        $recipe->save(); 
 
        return new Response($recipe->getAttributes()); 
 
    } 

    public function update($data) {

        if(!isset($data['id']))
            return new Response([], 400, 'No recipe ID provided');

        $recipe = $data['input'];
        $recipe->id = $data['id'];

        $result = (new Recipe)->update($recipe);

        return new Response(['updated' => (bool) $result->getModifiedCount(), 'recipe' => (array) $recipe]);
    }


    public function get($data) {

        if(!isset($data['id']))
            return new Response([], 400, 'No recipe ID provided');

        $recipe = (new Recipe)->findById($data['id']);

        return new Response((array) $recipe);
    }

    public function delete($data) {

        if(!isset($data['id']))
            return new Response([], 400, 'No recipe ID provided');
            
        $result = $result = (new Recipe)->delete($data['id']);

        return new Response(['deleted' =>  (bool) $result->getDeletedCount()]);
    }

    public function rate($data) {
        $input = $data['input'];
        
        if(!isset($data['id']))
            return new Response([], 400, 'No recipe ID provided');
        
        if(!isset($input->rating))
            return new Response([], 400, 'No rating provided');

        if($input->rating < 1 || $input->rating > 5)
            return new Response([], 400, 'Invalid rating provided');            
        
        $input->recipe_id = $data['id']; 

        $rating = new Rating;
        $rating->setAttributes($input);
        $rating->save();

        return new Response($rating->getAttributes());
    }

    public function search($data) {
        $input = $data['input'];

        if(!isset($input->criteria))
            return new Response([], 400, 'Search criteria not defined');        

        return new Response((array) (new Recipe)->find($input->criteria)); 
    }
}