<?php
/**
 * Created by PhpStorm.
 * User: LabbiqX
 * Date: 20.02.2016
 * Time: 5:44 PM
 */
session_start();
require_once(__DIR__ . "/DBConnection.php");
$dbConn = new DBConnection();
$dbConn->Connect();
// Files that inculde this file at the very top would NOT require
// connection to database or session_start(), be careful.
// Initialize some vars
$user_ok = false;
$log_username = "";
$log_password = "";
// User Verify function
function evalLoggedUser($u, $p)
{
    $sql = "SELECT e_mail FROM user_credentials WHERE e_mail='$u' AND UPPER(password)='$p'";
    $query = mysql_query($sql);
    $numRows = mysql_num_rows($query);
    if ($numRows > 0)
        return true;
    return false;
}

if (isset($_SESSION["username"]) && isset($_SESSION["password"])) {
    $log_username = htmlentities(mysql_real_escape_string($_SESSION['username']));
    $log_password = preg_replace('#[^a-z0-9]#i', '', $_SESSION['password']);
    // Verify the user
    $user_ok = evalLoggedUser($log_username, $log_password);
} else {
    if (isset($_COOKIE["user"]) && isset($_COOKIE["pass"])) {
        $_SESSION['username'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['user']);
        $_SESSION['password'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['pass']);
        $log_username = $_SESSION['username'];
        $log_password = $_SESSION['password'];
        // Verify the user
        $user_ok = evalLoggedUser($log_username, $log_password);
        if ($user_ok == true) {
            // Update their lastlogin datetime field
            $sql = "UPDATE users u JOIN user_credentials e ON u.id=e.id SET last_login=now() WHERE e_mail='$u'";
            $query = mysql_query($sql);
        }
    }
}
$dbConn->Close();
?>