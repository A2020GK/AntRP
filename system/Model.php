<?php
namespace System;

/**
 * Abstract base class for models in the application.
 * 
 * This class provides a basic interface for interacting with the database.
 */
class Model {
    
    /**
     * The PDO instance used to connect to the database.
     * 
     * @var \PDO
     */
    protected static \PDO $database;

    /**
     * Establishes a connection to the database if one does not already exist.
     * 
     * This method checks if a database connection has been established and 
     * creates a new connection if necessary.
     */
    public static function dbConnect() {
        $recreate = !file_exists(DATA_DIR ."/database.sqlite3");
        if($recreate) {
            copy(DATA_DIR."/database.example.sqlite3", DATA_DIR ."/database.sqlite3");
        }
        if(empty(self::$database)) {
            self::$database=new \PDO("sqlite:".DATA_DIR."/database.sqlite3");
        }
    }

    /**
     * Executes a SQL query on the database.
     * 
     * This method prepares and executes a SQL query with the given arguments.
     * 
     * @param string $query The SQL query to execute.
     * @param array $args An array of arguments to bind to the query.
     * 
     * @return \PDOStatement A PDOStatement object representing the query result.
     */
    public static function dbQuery(string $query, array $args = []): \PDOStatement {
        self::dbConnect();
        $statement=self::$database->prepare($query);
        $statement->execute($args);
        return $statement;
    }
}