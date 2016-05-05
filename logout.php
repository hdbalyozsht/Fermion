<?php
/**
 * Created by PhpStorm.
 * User: LabbiqX
 * Date: 20.02.2016
 * Time: 8:52 PM
 */
session_start();
// Set Session data to an empty array
$_SESSION = array();
// Expire their cookie files
if(isset($_COOKIE["user"]) && isset($_COOKIE["pass"])) {
    setcookie("id", '', strtotime( '-5 days' ), '/');
    setcookie("user", '', strtotime( '-5 days' ), '/');
    setcookie("pass", '', strtotime( '-5 days' ), '/');
}
// Destroy the session variables
session_destroy();

header("Location: login.php");

?>