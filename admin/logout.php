<?php
/**
 * Admin Logout
 * SOFTNETIX
 */

session_start();
session_destroy();
header('Location: index.php');
exit;
?>
