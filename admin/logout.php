<?php
session_start(); //start the session

include "connect.php";

include "include/function/function.php";

$Adminprofil = cheakName("Username", "admins", $_SESSION['Admin']);

if ($Adminprofil == 1) {
    $stam = $con->prepare("UPDATE admins SET Groubid=1 WHERE ID = $_SESSION[ID]");
    $stam->execute();


    session_unset(); //unset the session


    session_destroy(); //destroy the session

    header('Location:index.php');
    exit();

}
