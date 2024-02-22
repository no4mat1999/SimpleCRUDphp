<?php
class Configuration{
    public $host;
    public $port;
    public $username;
    public $password;
    public $database;

    function __construct(){
        $this->host = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->database = "gestionusuarios";
        $this->port = "3306";
    }
}