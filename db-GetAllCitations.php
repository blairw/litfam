<?php	 
	// connect to mysql
	include ('../3971thesis-db/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		SELECT
			c.citation_id,
			c.original_article_id,
			c.derived_article_id,
			c.ref_number,
			upj.is_basket_of_8 as up_b8,
			upj.is_conference as up_conf,
			upjr.pub_year as up_year,
			downj.is_basket_of_8 as down_b8,
			downj.is_conference as down_conf,
			downjr.pub_year as down_year
		FROM 3971thesis_citations c
			LEFT JOIN 3971thesis_articles upa on upa.article_id = c.original_article_id
			LEFT JOIN 3971thesis_journal_releases upjr on upjr.jr_id = upa.jr_id
			LEFT JOIN 3971thesis_journals upj on upj.journal_id = upjr.journal_id
			LEFT JOIN 3971thesis_articles downa on downa.article_id = c.derived_article_id
			LEFT JOIN 3971thesis_journal_releases downjr on downjr.jr_id = downa.jr_id
			LEFT JOIN 3971thesis_journals downj on downj.journal_id = downjr.journal_id
		ORDER BY ref_number ASC
	");
	
	$citations = array();
	while ($row = $res->fetch_assoc()) {
		array_push($citations,
			array(
				'original_article_id' => $row['original_article_id'],
				'derived_article_id'  => $row['derived_article_id'],
				'up_b8'               => $row['up_b8'],
				'up_conf'             => $row['up_conf'],
				'up_year'             => $row['up_year'],
				'down_b8'             => $row['down_b8'],
				'down_conf'           => $row['down_conf'],
				'down_year'           => $row['down_year'],
		));
	}
	$res->close();
	$mysqli->close();
	
	header('Content-type: application/json');
	echo json_encode($citations, JSON_PRETTY_PRINT);
	
	
?>
