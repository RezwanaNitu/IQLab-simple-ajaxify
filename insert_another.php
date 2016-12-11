<?php
	
require_once 'core/init.php';

// Check if ajax request send or not
if(is_request_ajax()) {
	
	$customer_id = $_POST['customer_id'];
	$another_field = $_POST['another_field'];

	$SQL_add_another_data = "INSERT INTO orders (customer_id, another_field) VALUES('{$customer_id}', '{$another_field}')";
	$insert = $db->query($SQL_add_another_data);

	if($db->affected_rows == true){

		$reports = [ 'status' => 'success' ];

		echo json_encode($reports);

	}else{

		$reports = [
			'status' => 'fail',
			'error' => 'Sorry we are unable to insert your data. Please try again or have some problem in your code.'
		];

		echo json_encode($reports);

	}

}