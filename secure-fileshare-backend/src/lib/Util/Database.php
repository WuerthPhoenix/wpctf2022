<?php

namespace Wp\Sfb\Util;

use mysqli;

class Database
{
    private $_connection;
    private static $_instance; //The single instance

    /*
    Get an instance of the Database
    @return Instance
    */
    public static function getInstance() {
        if(!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Constructor
    private function __construct() {
        $this->_connection = new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASS,
            DB_NAME
        );

        // Error handling
        if(mysqli_connect_error()) {
            trigger_error("Failed to connect to DB: " . mysqli_connect_error(),
                E_USER_ERROR);
        }

        //default settings
        $this->_connection->query("SET sql_mode = ''");
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }

    // Get mysqli connection
    public function getConnection() {
        return $this->_connection;
    }

    public static function sanitizeString(string $input): string {
        return str_replace("'", "\'", $input);
    }
}