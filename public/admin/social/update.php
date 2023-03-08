<?php
session_start();
require '../../config.php';
if (!isset($_SESSION['admin_id'])) {
    header('location: ../login.php');
}

if (isset($_GET['update'])) {
	$id = $_GET['update'];

	$sql= "SELECT * FROM socials WHERE id = '$id' ";
	$result = $connect->query($sql);
	$data = mysqli_fetch_assoc($result);
}

if (isset($_POST['update_data'])) {
    
	$title = $_POST['title'];
	$icon_class = $_POST['icon_class'];
	$link = $_POST['link'];
	
	$sql = "UPDATE `socials` SET `id`= '$id', `title`= '$title', `icon_class`= '$icon_class', `link`= '$link' WHERE `id`= '$id'";

	$result = $connect->query($sql);

	if ($result) {
        $_SESSION['social_update_success'] = "Social Updated Successfully !!";
        header('location: ../index.php');
    }else{
        $_SESSION['social_update_error'] = "Error. Please Try Again !!";
	    header('location: ../index.php');
    }
}
