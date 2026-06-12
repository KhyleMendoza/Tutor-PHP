<?php 
session_start();

session_unset();
session_abort();

header("Location: index.php");
exit();
?>