<div class="sidebar pe-4 pb-3">
  <nav class="navbar bg-secondary navbar-dark">
    <a href="<?php echo $url;?>index" class="navbar-brand mb-3">
      <h3 class="text-primary"><img src="<?php echo $url;?>assets/img/logo.png" width="250px"></h3>
    </a>
    <div class="navbar-nav w-100">
      <a href="<?php echo $url;?>index"
        class="nav-item nav-link <?php if($first_part == 'index'){echo 'active';}else{echo '';}?>">
        <i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
      <a href="<?php echo $url;?>item/manage-item"
        class="nav-item nav-link <?php if($first_part == 'item'){echo 'active';}else{echo '';}?>"><i
          class="fa fa-th me-2"></i>Items</a>
      <!-- <a href="form.html" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Items</a> -->
    </div>
  </nav>
</div>