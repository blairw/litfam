<?php
	// connect to mysql
	include ('../3971thesis-db/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		SELECT
			j.journal_id,
			j.journal_code,
			j.journal_name,
			j.is_basket_of_8,
			j.is_conference,
			j.url,
			count(distinct jr.jr_id) as count_jr,
			count(distinct a.article_id) as count_article
		FROM 3971thesis_journals j
		LEFT JOIN 3971thesis_journal_releases jr on jr.journal_id = j.journal_id
		LEFT JOIN 3971thesis_articles a on a.jr_id = jr.jr_id
		GROUP BY
			j.journal_id,
			j.journal_code,
			j.journal_name,
			j.is_basket_of_8,
			j.is_conference,
			j.url
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
