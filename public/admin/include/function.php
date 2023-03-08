<?php 

function check_data($data){
	$trim_data = trim($data);
	$stripslashes_data = stripcslashes($trim_data);
	$main_data = htmlspecialchars($stripslashes_data);

	return $main_data;
}

// function check_admin_acces(){
// 	if ($_SESSION['admin_id'] != 1) {
// 		header('location: forbidden.php');
// 	}
// }


?>