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
        $migrations = array_values(array_diff($migrations, ['.', '..', '0_migrations.sql']));

        foreach ($migrations as $migration) {
            $table = preg_replace('/[\d_]+/i', '', $migration);
            if (!$this -> checkIfMigrationWasRun($table)) {
                d("Run {$table}");
                $script = file_get_contents(self::SCRIPT_DIR . $migration);
                $query = Db::connect() -> prepare($script);
                if($query -> execute()){
                    $this -> insertIntoMigrations($table);
                    d("{$table} OK!");
                }
            }
        }
    }

    protected function insertIntoMigrations(string $migration) {

        $query = Db::connect() -> prepare("INSERT INTO migrations (name) VALUES (:name)");
        $query -> bindParam('name', $migration);
        $query -> execute();
    }

    protected function checkIfMigrationWasRun(string $migration): bool  {

        $query = Db::connect()->prepare("SELECT * FROM migrations WHERE name = :name");
        $query->bindParam('name', $migration);
        $query->execute();

        return (bool) $query->fetch();
    }

    protected function checkMigrationTable() {

       $query = Db::connect() -> prepare("SHOW TABLES LIKE 'migrations'");
       $query -> execute();

       if (!$query->fetch()) {
           $this -> createMigrationTable();
       }
    }

    protected function createMigrationTable() {

        $script = file_get_contents(self::SCRIPT_DIR . '0_migrations.sql');
        $query = Db::connect() -> prepare($script);

        if($query -> execute()){
            d(__METHOD__);
        }
    }
}new Migration();


