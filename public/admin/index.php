<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
	header('location: ../login.php');
}
require '../config.php';

$sl = 1;

$sql= "SELECT * FROM socials";
$result = $connect->query($sql);


include('include/header.php'); ?>

<!-- Banner Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="row bg-secondary rounded d-flex align-items-center justify-content-between">
                <div class="col-12">
                    <h1 class="purple1 text-center" style="font-size: 4.5rem;"><span
                            style="color:#a020f0ec;">Congratulations! 🎉</span> <span style="color: #ffbb00f6;">Welcome
                            to the Admin Panel</span> <br> of <span style="color:rgb(59,193,23);">Everest</span></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Banner End -->



<?php if (isset($_SESSION['social_insert_success'])) { ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['social_insert_success']; ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php }?>
<?php if (isset($_SESSION['social_insert_error'])) { ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['social_insert_error']; ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php }?>
<?php if (isset($_SESSION['social_update_success'])) { ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['social_update_success']; ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php }?>
<?php if (isset($_SESSION['social_update_error'])) { ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['social_update_error']; ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php }?>
<?php if (isset($_SESSION['social_delete_success'])) { ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['social_delete_success']; ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php }?>



<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Item Table</h6>
            <a href="social/create.php">Add Item</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Icon</th>
                        <th scope="col">Link</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        while ($row = mysqli_fetch_assoc($result)) {?>
                    <tr>
                        <td><?php echo $sl;?></td>
                        <td><?php echo $row['title'];?></td>
                        <td> <i class="<?php echo $row['icon_class'];?>"></i></td>
                        <td><?php echo $row['link'];?></td>
                        <td>
                            <?php if($row['status'] == 1) {?>
                            Active <a href="status.php?id=<?=$row['id']?>" class="btn btn-danger btn-sm py-1"
                                style="font-size: 1rem; padding: 0px 6px;"> <i class="fa fa-thumbs-down"
                                    aria-hidden="true"></i></a>
                            <?php }else{?>
                            Deactive <a href="status.php?id=<?=$row['id']?>" class="btn btn-success btn-sm py-1"
                                style="font-size: 1rem; padding: 0px 6px;"><i class="fa fa-thumbs-up"
                                    aria-hidden="true"></i></a>
                            <?php } ?>
                        </td>
                        <td>
                            <a href="social/edit.php?edit=<?= $row['id']?>" class="btn btn-success btn-sm"
                                style="font-size: 1rem; padding: 0px 6px;"><i class="fa fa-edit"
                                    aria-hidden="true"></i></a>
                            <a href="social/delete.php?delete=<?= $row['id']?>" class="btn btn-warning btn-sm"
                                style="font-size: 1rem; padding: 0px 6px;"
                                onclick="return confirm('Are You Sure To Delete?')"><i class="fa fa-trash"
                                    aria-hidden="true"></i></a>
                        </td>
                    </tr>
                    <?php $sl++ ;}?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>

<?php unset($_SESSION['social_insert_success']) ?>
<?php unset($_SESSION['social_insert_error']) ?>
<?php unset($_SESSION['social_update_success']) ?>
<?php unset($_SESSION['social_update_error']) ?>
<?php unset($_SESSION['social_delete_success']) ?>