<?php
session_start();
require '../../config.php';
if (!isset($_SESSION['admin_id'])) {
	header('location: ../login.php');
}

$sl = 1;

$sql= "SELECT * FROM item";
$result = $connect->query($sql);


?>



<?php include('../include/header.php'); ?>


<?php if (isset($_SESSION['item_insert_success'])) { ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['item_insert_success']; ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php }?>
<?php if (isset($_SESSION['item_insert_error'])) { ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['item_insert_error']; ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php }?>
<?php if (isset($_SESSION['item_update_success'])) { ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['item_update_success']; ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php }?>
<?php if (isset($_SESSION['item_update_error'])) { ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['item_update_error']; ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php }?>
<?php if (isset($_SESSION['item_delete_success'])) { ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $_SESSION['item_delete_success']; ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php }?>


<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Item Table</h6>
            <a href="create.php">Add Item</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Details</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        while ($row = mysqli_fetch_assoc($result)) {?>
                    <tr>
                        <td><?php echo $sl;?></td>
                        <td>
                            <img src="../upload/item_image/<?php echo $row['image']?>" alt="image"
                                style="height: 50px; width: 50px;">
                        </td>
                        <td><?php echo $row['name'];?></td>
                        <td><?php echo $row['description'];?></td>
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
                            <!-- <a href="view.php?show=<?= $row['id']?>" class="btn btn-info btn-sm"
                                style="font-size: 1rem; padding: 0px 6px;"><i class="fa fa-eye"
                                    aria-hidden="true"></i></a> -->
                            <a href="edit.php?edit=<?= $row['id']?>" class="btn btn-success btn-sm"
                                style="font-size: 1rem; padding: 0px 6px;"><i class="fa fa-edit"
                                    aria-hidden="true"></i></a>
                            <!-- <a href="delete.php?delete=<?= $row['id']?>" class="btn btn-warning btn-sm"
                                style="font-size: 1rem; padding: 0px 6px;"
                                onclick="return confirm('Are You Sure To Delete?')"><i class="fa fa-trash"
                                    aria-hidden="true"></i></a> -->
                        </td>
                    </tr>
                    <?php $sl++ ;}?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php include('../include/footer.php'); ?>

<?php unset($_SESSION['item_insert_success']) ?>
<?php unset($_SESSION['item_insert_error']) ?>
<?php unset($_SESSION['item_update_success']) ?>
<?php unset($_SESSION['item_update_error']) ?>
<?php unset($_SESSION['item_delete_success']) ?>