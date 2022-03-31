<?php

class Dbh {

    protected function connect() {
        try {
            $username = "root";
            $password = "mysql";
            $dbh = new PDO('mysql:host=localhost;dbname=dblabb', $username, $password);
            return $dbh;
        } 
        catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

}