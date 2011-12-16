<?php

require_once 'includes/config.php';

$session->logout();
$referer = (strpos($_SERVER['HTTP_REFERER'], "admin") === false) ? $_SERVER['HTTP_REFERER'] : "index.php";
header("Location: " . $referer);

?>