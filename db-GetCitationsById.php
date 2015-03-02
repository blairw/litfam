<?php
	/* Set internal character encoding to UTF-8 */
	// mb_internal_encoding("UTF-8");

	if (isset($_GET['id']) && is_int((int) $_GET['id'])) {
		// only continue if id is set and it is an int
		$selectedId = $_GET['id'];
	} else {
		// send them to the index page whatever it may be
		header('Location: ./');
	}
	 
	// connect to mysql
	include ('../3971thesis-db/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		SELECT
			citation_id,
			original_article_id,
			derived_article_id,
			ref_number
		FROM 3971thesis_citations
		WHERE original_article_id = ".$selectedId."
		OR derived_article_id = ".$selectedId."
		ORDER BY ref_number ASC
	");
	
	$cited_by = array();
	$cites_others = array();
	while ($row = $res->fetch_assoc()) {
		if ($selectedId == $row['original_article_id']) {
			array_push($cited_by, $row['derived_article_id']);
		} else {
			array_push($cites_others, $row['original_article_id']);
		}
	}
	$res->close();
	$mysqli->close();
	
	$citationData = array(
		"citedBy" => $cited_by,
		"citesOthers" => $cites_others
	);
	
	header('Content-type: application/json');
	echo json_encode($citationData, JSON_PRETTY_PRINT);
	
	
?>
