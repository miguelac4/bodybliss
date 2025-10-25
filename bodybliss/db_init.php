<?php

//DB Connection
function db_connect(){

    //Database Credentials Catch: XML Way
    $xml = simplexml_load_file(__DIR__ . "/xml/config.xml");
    if (!$xml) {
        die("Error loading config.xml");
    }
    $servername = (string)$xml->database->servername;
    $port = (int) $xml->database->port;
    $username = $xml->database->username;
    $password_db = $xml->database->pass;
    $dbname = (string)$xml->database->dbname;

    $conn = new mysqli($servername, $username, $password_db, $dbname, $port);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>

