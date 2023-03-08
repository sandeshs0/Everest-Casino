<?php
session_start();
require '../../config.php';
if (!isset($_SESSION['admin_id'])) {
	header('location: ../login.php');
}



if (isset($_GET['delete'])) {
	$id = $_GET['delete'];

	$sql= "DELETE FROM `item` WHERE `id` = '$id' ";
	$result = $connect->query($sql);
	
	$_SESSION['item_delete_success'] = "Item Deleted Successfully !!";
	header('location: manage-item');
}





?>