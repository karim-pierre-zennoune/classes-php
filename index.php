<?php


require_once("User.php");


$newuser = new User();

$ret = $newuser->register(
    "Jojdddfo",
    "123456",
    "kazenun@gmail.com",
    "Karim",
    "Zennoune"
);

var_dump($ret);



?>