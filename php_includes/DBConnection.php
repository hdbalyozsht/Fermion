<?php

/**
 * Created by PhpStorm.
 * User: LabbiqX
 * Date: 18.02.2016
 * Time: 8:58 PM
 */
class DBConnection
{
    private $conn;

    public function Connect()
    {
        require_once "db_config.php";
        // Connecting to mysql database
        $this->conn = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());

        // Selecing database
        $db = mysql_select_db(DB_DATABASE) or die(mysql_error()) or die(mysql_error());
    }

    public function Close()
    {
        mysql_close();
    }
}