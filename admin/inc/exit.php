<?php session_start();
if(isset($_SESSION['logged_in_user'])){
	unset($_SESSION['logged_in_user']);
	header("Location:/admin/login.php");
} ?>