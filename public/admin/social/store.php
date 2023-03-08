<?php
session_start();
require '../../config.php';
if (!isset($_SESSION['admin_id'])) {
	header('location: ../login.php');
}

if (isset($_POST['submit'])) {
	$title = $_POST['title'];
	$icon_class = $_POST['icon_class'];
	$link = $_POST['link'];

	
	$sql = "INSERT INTO `socials`(`title`,  `icon_class`, `link`) VALUES ('$title', '$icon_class','$link')";
	
	$result = $connect->query($sql);

    if ($result) {
        $_SESSION['social_insert_success'] = "Social Inserted Successfully !!";
	    header('location: ../index');
    }else{
        $_SESSION['social_insert_error'] = "Error. Please Try Again !!";
	    header('location: ../index');
    }
    

}



?>