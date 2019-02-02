<?php

require __DIR__.'/../bootstrap/autoload.php';

class Migrate {
    protected $migrations;

    public function __construct() {
        $this->migrations = [
            'Migrations\CreateUsersCollectionMigration',
            'Migrations\CreateRatingsCollectionMigration',
            'Migrations\CreateSessionsCollectionMigration',
            'Migrations\CreateRecipesCollectionMigration',
        ];

        $this->run();
    }
    
    public function run() {
        foreach ($this->migrations as $class) {
            $className =  array_slice(explode('\\', $class), 1);
            echo "Running: ".$className[0]. "\n";
            $migration = new $class;
            $migration->run();
        }
    }

}

new Migrate();