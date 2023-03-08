<?php
session_start();
require '../../config.php';
include '../include/function.php';
if (!isset($_SESSION['admin_id'])) {
    header('location: ../login.php');
}


if (isset($_GET['edit'])) {
	$id = $_GET['edit'];

	$sql= "SELECT * FROM socials WHERE id = '$id' ";
	$result = $connect->query($sql);
	$row = mysqli_fetch_assoc($result);
}

?>

<?php require_once('../include/header.php'); ?>


<!-- Form Start -->
<div class="container-fluid">
    <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
            <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <a href="../index" class="">
                        Back
                    </a>
                    <h3>Item Edit Form</h3>
                </div>
                <form action="update?update=<?= $row['id']?>" method="POST" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <input type="text" name="title" class="form-control" id="defaultFormControlInput"
                        value="<?php if (isset($row['title'])) {echo $row['title'];}?>">
                        <label for="floatingInput">Title</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="icon_class" class="form-control" id="defaultFormControlInput"
                        value="<?php if (isset($row['icon_class'])) {echo $row['icon_class'];}?>">
                        <label for="floatingInput">Icon Class</label>
                        <br>
                        <small class="text-danger">[Copy icon html from this link - <a href="https://fontawesome.com/search?m=free&o=r">Font Awesome</a> ]</small> 
                        <br>
                        <small class="text-danger">[Only use this portion of what u have copied - (fa........) ]</small>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="link" class="form-control" id="defaultFormControlInput"
                        value="<?php if (isset($row['link'])) {echo $row['link'];}?>">
                        <label for="floatingInput">Link</label>
                    </div>
                    <input type="submit" class="btn btn-primary py-3 w-100 mb-4" value="Submit Now">
                    <!-- <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Submit Now</button> -->
                </form>
                <!-- <p class="text-center mb-0">Go Back to <a href="../">Home Page</a></p> -->
            </div>
        </div>
    </div>
</div>
<!-- Form End -->


<?php require_once('../include/footer.php'); ?>