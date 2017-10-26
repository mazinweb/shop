<?php

// connect to database
include 'connect.php';

//Routes
$tpl = 'includs/templets/'; //templete directory
$lan = 'includs/language/'; // language directory
$func = 'includs/function/'; // function directory
$css = 'layout/css/'; //css directory
$js = 'layout/js/'; //js directory


//includs important files
include $func . 'function.php';
include $lan . 'english.php';
include $tpl . 'header.php';


//include navbar in all pages expacte that have $nonavbar varibale
if (!isset($nonavbar)) {
    include $tpl . 'navbar.php';
}


