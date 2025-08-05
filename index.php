<?php

$test_login = "Karim";
$test_password = "123456";
$new_login = "Pierre";
$new_password = "qwerty";
$wrong_login = "Bob";
$wrong_password = "abcdefg";


echo "============================ mysqli ====================================<br/>";

echo "Step 1: creating User class instance<br/>";
require_once("User.php");
$newuser = new User();
include("tests.php");


echo "============================== pdo =====================================<br/>";

echo "Step 1: creating Userpdo class instance<br/>";
require_once("user-pdo.php");
$newuser = new Userpdo();
include("tests.php");

?>