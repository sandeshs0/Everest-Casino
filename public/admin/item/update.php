<?php
session_start();
require '../../config.php';
if (!isset($_SESSION['admin_id'])) {
    header('location: ../login.php');
}

if (isset($_GET['update'])) {
	$id = $_GET['update'];

	$sql= "SELECT * FROM item WHERE id = '$id' ";
	$result = $connect->query($sql);
	$data = mysqli_fetch_assoc($result);
}

if (isset($_POST['update_data'])) {
    
	$name = $_POST['name'];
	$description = $_POST['description'];
	
	// For upload image
	$rand = rand(11111, 99999999);
	$image= explode('.', $_FILES['image']['name']);
	$image = end($image);
	$item_image = $rand.'.'.$image;
    // For upload image

	
	$sql = "UPDATE `item` SET `id`= '$id', `name`= '$name', `description`= '$description', `image`= '$item_image' WHERE `id`= '$id'";
	
	move_uploaded_file($_FILES['image']['tmp_name'], '../upload/item_image/'.$item_image);

	$result = $connect->query($sql);

	if ($result) {
        $_SESSION['item_update_success'] = "Item Updated Successfully !!";
        header('location: manage-item');
    }else{
        $_SESSION['item_update_error'] = "Error. Please Try Again !!";
	    header('location: manage-item');
    }
}
