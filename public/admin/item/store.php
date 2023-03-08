<?php
session_start();
require '../../config.php';
if (!isset($_SESSION['admin_id'])) {
	header('location: ../login.php');
}


if (isset($_POST['submit'])) {
	$name = $_POST['name'];
	$description = $_POST['description'];
	
	// For upload image
	$rand = rand(11111, 99999999);
	$image= explode('.', $_FILES['image']['name']);
	$image = end($image);
	$item_image = $rand.'.'.$image;
    // For upload image

	
	$sql = "INSERT INTO `item`(`name`,  `image`, `description`) VALUES ('$name', '$item_image','$description')";
	
	move_uploaded_file($_FILES['image']['tmp_name'], '../upload/item_image/'.$item_image);

	$result = $connect->query($sql);

    if ($result) {
        $_SESSION['item_insert_success'] = "Item Insert Successfully !!";
	    header('location: manage-item');
    }else{
        $_SESSION['item_insert_error'] = "Error. Please Try Again !!";
	    header('location: manage-item');
    }
    

}



?>