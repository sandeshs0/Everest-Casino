<?php
session_start();
require '../../config.php';
if (!isset($_SESSION['admin_id'])) {
    header('location: ../login.php');
}
?>

<?php require_once('../include/header.php'); ?>

<!-- Banner Start -->

<!-- Form Start -->
<div class="container-fluid">
    <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
            <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <a href="manage-item" class="">
                        Back
                    </a>
                    <h3>Add Item</h3>
                </div>
                <form action="store.php" method="POST" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control" id="defaultFormControlInput"
                            placeholder="Enter Item Name">
                        <label for="floatingInput">Item Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="description" id="defaultFormControlInput" class="form-control" cols="30" rows="10"></textarea>
                        <label for="floatingInput">Item Description</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="file" name="image" class="form-control" id="formFile">
                        <!-- <label for="floatingPassword">Image</label> -->
                    </div>
                    <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Submit Now</button>
                </form>
                <!-- <p class="text-center mb-0">Go Back to <a href="../">Home Page</a></p> -->
            </div>
        </div>
    </div>
</div>
<!-- Form End -->

<?php require_once('../include/footer.php'); ?>