<?php

session_start();

$_SESSION = array(); // permet de vider la session

session_destroy();

header("Location : index.php");
exit();


?>