<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header('Location: ../auth/login.php');
    exit();
}


if($_SESSION['user_role'] !== 'admin'){
    header('Location: ../pages/home.php');
    exit();
}
?>