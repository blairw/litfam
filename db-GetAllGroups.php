<?php
	// connect to mysql
	include ('../3971thesis-db/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		SELECT * FROM 3971thesis_groups
	");
	
	$arr = array();
	while ($row = $res->fetch_assoc()) {
		array_push($arr, $row);
	}
	$res->close();
	$mysqli->close();
	
	header('Content-type: application/json');
	echo json_encode($arr, JSON_PRETTY_PRINT);
?>
