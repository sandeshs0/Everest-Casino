<?php
session_start();
require '../../config.php';
if (!isset($_SESSION['admin_id'])) {
    header('location: ../login.php');
}


if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$sql= "SELECT * FROM item WHERE id = '$id' ";
	$result = $connect->query($sql);
	$data = mysqli_fetch_assoc($result);

    if ($data['status'] == '1') {
        $sql = "UPDATE item SET status = '0' WHERE id = '$id';";
        $result = $connect->query($sql);

    }else{
        $sql = "UPDATE item SET status = '1' WHERE id = '$id';";
        $result = $connect->query($sql);

    }
    
    if ($result) {
        $_SESSION['item_update_success'] = "Item Status Successfully !!";
        header('location: manage-item');
    }else{
        $_SESSION['item_update_error'] = "Error. Please Try Again !!";
	    header('location: manage-item');
    }
    
 
}
