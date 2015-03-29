<?php
	if (isset($_GET['id']) && is_int((int) $_GET['id'])) {
		// only continue if id is set and it is an int
		$selectedId = $_GET['id'];
	} else {
		// send them to the index page whatever it may be
		header('Location: ./');
	}
	
	$outputArray = array(
		"group" => array(),
		"articles" => array()
	);
	
	// connect to mysql
	include ('../3971thesis-db/db-MysqlAccess.php');
	
	//
	// GROUP DETAILS
	//
	$resGroup = $mysqli->query("
		SELECT *
		FROM 3971thesis_groups
		WHERE group_id = ".$selectedId."
	");
	
	while ($row = $resGroup->fetch_assoc()) {
		$outputArray["group"] = $row;
	}
	$resGroup->close();
	
	//
	// ARTICLE DETAILS
	//
	$resArticles = $mysqli->query("
		SELECT article_id, title
		FROM 3971thesis_articles a
		WHERE a.article_id IN (
			SELECT article_id
			FROM 3971thesis_membership
			WHERE group_id = ".$selectedId."
		)
	");
	while ($row = $resArticles->fetch_assoc()) {
		array_push($outputArray["articles"], $row);
	}
	$resArticles->close();
	
	//
	// AUTHORSHIP DETAILS
	//
	for ($i = 0; $i < count($outputArray["articles"]); $i++) {
		$outputArray["articles"][$i]["authors"] = array();
	}
	$resAuthorships = $mysqli->query("
		SELECT *
		FROM 3971thesis_authorship ash
		LEFT JOIN 3971thesis_authors a ON a.author_id = ash.author_id
		WHERE ash.article_id IN (
			SELECT article_id
			FROM 3971thesis_membership
			WHERE group_id = ".$selectedId."
		)
		ORDER BY ash.article_id, ash.sequence, ash.author_id
	");
	$arrayAuthorships = array();
	while ($row = $resAuthorships->fetch_assoc()) {
		array_push($arrayAuthorships, $row);
	}
	$resAuthorships->close();
	for ($j = 0; $j < count($outputArray["articles"]); $j++) {
		for ($i = 0; $i < count($arrayAuthorships); $i++) {
			if (
				$arrayAuthorships[$i]["article_id"]
				== $outputArray["articles"][$j]["article_id"]
			) {
				array_push($outputArray["articles"][$j]["authors"], $arrayAuthorships[$i]);
			}
		}
	}
	
	$mysqli->close();
	
	header('Content-type: application/json');
	echo json_encode($outputArray, JSON_PRETTY_PRINT);
?>
