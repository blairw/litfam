<?php
	// connect to mysql
	include ('../secret/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		SELECT * FROM litfam_groups ORDER BY group_name ASC
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
