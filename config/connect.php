<?php
require_once 'config.php';

try {
    $conn = new PDO('mysql:host='.DB_SERVER,DB_USER,DB_PASSWD);
    // $conn = null;
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}




?>