<?php
require_once 'config.php';
session_unset();
session_destroy();
header('Location: index.php');
exit;
?>
