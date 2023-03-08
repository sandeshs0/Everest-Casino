<?php
session_start();
require '../../config.php';
if (!isset($_SESSION['admin_id'])) {
    header('location: ../login.php');
}


if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$sql= "SELECT * FROM socials WHERE id = '$id' ";
	$result = $connect->query($sql);
	$data = mysqli_fetch_assoc($result);

    if ($data['status'] == '1') {
        $sql = "UPDATE socials SET status = '0' WHERE id = '$id';";
        $result = $connect->query($sql);

    }else{
        $sql = "UPDATE socials SET status = '1' WHERE id = '$id';";
        $result = $connect->query($sql);

    }
    
    if ($result) {
        $_SESSION['social_update_success'] = "Social Status Successfully Changed!!";
        header('location: manage-social');
    }else{
        $_SESSION['social_update_error'] = "Error. Please Try Again !!";
	    header('location: manage-social');
    }
    
 
}
