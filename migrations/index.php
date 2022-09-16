<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";

use Dotenv\Dotenv;
use Core\Db;

$dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__));
$dotenv->load();




class Migration {

    const SCRIPT_DIR = __DIR__ . "/scripts/";

    public function __construct() {

        try {
            $this -> checkMigrationTable();
            $this -> runAllMigrations();
        } catch (PDOException $exception) {
            d($exception -> getMessage());
        }
    }

    protected function runAllMigrations() {

        d(__METHOD__);
        $migrations = scandir(self::SCRIPT_DIR);
        $migrations = array_values(array_diff($migrations, ['.', '..', 'migrations.sql']));

        foreach ($migrations as $migration) {
            d("Run {$migration}");
            $script = file_get_contents(self::SCRIPT_DIR . $migration);
            $query = Db::connect() -> prepare($script);
            if($query -> execute()){
                $this -> insertIntoMigrations($migration);
                d("{$migration} OK!");
            }
        }
    }

    protected function insertIntoMigrations(string $migration) {

        $query = Db::connect() -> prepare("INSERT INTO migrations (name) VALUES (:name)");
        $query -> bindParam('name', $migration);
        $query -> execute();
    }

    protected function checkMigrationTable() {

       $query = Db::connect() -> prepare("SHOW TABLES LIKE 'migrations'");
       $query -> execute();

       if (!$query->fetch()) {
           $this -> createMigrationTable();
       }
    }

    protected function createMigrationTable() {

        $script = file_get_contents(self::SCRIPT_DIR . 'migrations.sql');
        $query = Db::connect() -> prepare($script);

        if($query -> execute()){
            d(__METHOD__);
        }
    }
}new Migration();


