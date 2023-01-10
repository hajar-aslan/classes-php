<?php

include "../user-pdo.php";

// start the session
session_start();

$user = new Userpdo();

$user->connect("hajar123", "Hajar777");
// $user->connect("anass", "anass-ssana");

// var_dump($user->getAllInfos());

// $user->register("hajar", "Hajar1988", "hajar.aslan@gmail.com", "Hajar", "Aslan");
// $user->register("hamza", "hamza-aslan", "hamza.aslan@laplateforme.io", "Hamza", "Aslan");
// $user->register("anass", "anass-ssana", "anass.ssana@laplateforme.io", "Anass", "Ssana");

// $user->disconnect();


// $user->delete();


// $user->update("hajar007", "Hajar777", "aslan.hajar@laplateforme.io", "Hajar", "Aslan");

