<?php
session_start();
require '../../config.php';
if (!isset($_SESSION['admin_id'])) {
	header('location: ../login.php');
}
$id = $_SESSION['admin_id'];


if (isset($_POST['current_password']) && isset($_POST['new_password'])) {

    $current = $_POST['current_password'];
	$new = $_POST['new_password'];


    $select_sql = "SELECT * FROM `admin` WHERE `id`= '$id'";
    
    $select_result = $connect->query($select_sql);

    

	if ($current != '' && $new != '') {

        $password = password_hash($new, PASSWORD_DEFAULT);

        $sql = "UPDATE `admin` SET `password`= '$password' WHERE `id`= '$id'";
    
        $result = $connect->query($sql);
        if ($result) {
            $_SESSION['password_update_success'] = "Password Updated Successfully !!";
            header('location: change');
        }else{
            $_SESSION['password_update_error'] = "Error. Please Try Again !!";
            header('location: change');
        }

    }elseif ($current == '' || $new == '') {
        $_SESSION['password_update_error'] = "Error. Fill In The Boxes !!";
        header('location: change');
    }

}



?>


<?php include('../include/header.php'); ?>

       

<div class="card mt-3">
    <div class="card-content">
        <div class="row row-group m-0">
            <div class="col-12 border-light">
                <div class="card-body">
                <?php if (isset($_SESSION['password_update_success'])) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['password_update_success']; ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php }?>
                <?php if (isset($_SESSION['password_update_error'])) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['password_update_error']; ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php }?>
                <form method="POST" action="">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Current Password</label>
                            <input type="password" class="form-control" name="current_password" id="exampleInputPassword1" placeholder="Current Password">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="exampleInputPassword1">New Password</label>
                            <input type="password" class="form-control" name="new_password" id="exampleInputPassword1" placeholder="New Password">
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Change</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<!--End Dashboard Content-->

<?php include('../include/footer.php'); ?>