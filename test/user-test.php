<?php

include "../user.php";

// start the session
session_start();

$user = new User();

$user->connect("hajar123", "Hajar777");

// var_dump($user->getAllInfos());

// $user->register("hajar", "Hajar1988", "hajar.aslan@gmail.com", "Hajar", "Aslan");
// $user->register("hamza", "hamza-aslan", "hamza.aslan@laplateforme.io", "Hamza", "Aslan");

$user->disconnect();


// $user->delete();


// $user->update("hajar123", "Hajar777", "aslan.hajar@laplateforme.io", "Hajar", "Aslan");

