<?php
session_start();   //start the session
session_unset();   // unset the data of session
session_destroy();  //destroy the session in the page
header('location:index.php'); // direction to index page
exit();