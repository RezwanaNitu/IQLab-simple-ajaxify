<?php
	
require_once 'core/init.php';

// Check if ajax request send or not
if(is_request_ajax()) {
	
	$name = $_POST['name'];
	$email = $_POST['email'];

	$SQL_add_user = "INSERT INTO customers (name, email) VALUES('{$name}', '{$email}')";
	$insert = $db->query($SQL_add_user);

	if($db->affected_rows == true){
		
		$SQL_get_last_data = "SELECT * FROM customers WHERE id = {$db->insert_id} LIMIT 1";

		$Query_results = $db->query($SQL_get_last_data);

		$reports = [
			'status' => 'success',
			'results' => $Query_results->fetch_object()
		];

		echo json_encode($reports);

	}else{

		$reports = [
			'status' => 'fail',
			'error' => 'Sorry we are unable to insert your data. Please try again or have some problem in your code.'
		];

		echo json_encode($reports);

	}

}