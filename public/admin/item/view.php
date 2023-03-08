<?php
session_start();
require '../../config.php';
include '../include/function.php';
if (!isset($_SESSION['admin_id'])) {
    header('location: ../login.php');
}


if (isset($_GET['show'])) {
	$id = $_GET['show'];

	$sql= "SELECT * FROM item WHERE id = '$id' ";
	$result = $connect->query($sql);
	$row = mysqli_fetch_assoc($result);
}

?>

<?php require_once('../include/header.php'); ?>



<div class="card mt-3">
    <div class="card-header">
        <div class="row">
            <div class="col-12">
                <div class="card-title">Item Details <a href="manage-category" class="btn btn-warning pull-right"><i class="icon-lock"></i> Back</a></div>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <h1><?php if (isset($row['category_name'])) {echo $row['category_name'];}?> 
        <img class="pull-right" src="../upload/category_image/<?php echo $row['category_image']?>" alt="Category Image" style="height: 100%; width: 100%; border-radius: 10px;"></h1>
        <br>
        <hr>
        <p>
        <?php if (isset($row['description'])) {echo $row['description'];}?>
        </p>
        <div>
            
        </div>
    </div>
</div>


<!--start overlay-->
<div class="overlay toggle-menu"></div>
<!--end overlay-->

<?php require_once('../include/footer.php'); ?>