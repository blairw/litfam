<?php
	if (isset($_GET['id']) && is_int((int) $_GET['id'])) {
		// only continue if id is set and it is an int
		$selectedId = $_GET['id'];
	} else {
		// send them to the index page whatever it may be
		header('Location: ./');
	}
	
	// connect to mysql
	include ('../litfam-db/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		SELECT
			j.journal_id,
			j.journal_code,
			j.journal_name,
			jr.jr_id,
			jr.pub_year,
			jr.pub_month,
			jr.volume,
			jr.issue,
			jr.part,
			jr.url,
			count(distinct a.article_id) as count_article
		FROM litfam_journal_releases jr
		LEFT JOIN litfam_journals j on j.journal_id = jr.journal_id
		LEFT JOIN litfam_articles a on a.jr_id = jr.jr_id
		WHERE j.journal_id = ".$selectedId."
		GROUP BY jr.jr_id
		ORDER BY volume DESC, issue DESC, part DESC
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
