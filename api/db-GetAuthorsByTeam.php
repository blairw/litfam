<?php
	if (isset($_GET['id']) && is_int((int) $_GET['id'])) {
		// only continue if id is set and it is an int
		$selectedId = $_GET['id'];
	} else {
		// send them to the index page whatever it may be
		header('Location: ./');
	}
	
	// connect to mysql
	include ('../secret/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		SELECT
			a.author_id,
			a.author_lname,
			a.author_fname,
			a.author_mname,
			a.url,
			t.team_id,
			t.name,
			t.geography_country,
			t.geography_city,
			tm.role
		FROM litfam_authors a
			LEFT JOIN litfam_team_membership tm on tm.author_id = a.author_id
				LEFT JOIN litfam_teams t on t.team_id = tm.team_id
		WHERE t.team_id = ".$selectedId."
		ORDER BY tm.role ASC
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
