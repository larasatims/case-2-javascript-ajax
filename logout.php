<?php
session_start();

unset($_SESSION['username']);
unset($_SESSION['profile_picture']);

session_destroy();

header("Location: index.php");
exit();