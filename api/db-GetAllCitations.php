<?php
	if (isset($_GET['id']) && is_int((int) $_GET['id'])) {
		// only continue if id is set and it is an int
		$selectedId = $_GET['id'];
	}
	
	// connect to mysql
	include ('../secret/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		SELECT
			c.citation_id,
			c.original_article_id,
			c.derived_article_id,
			c.ref_number,
			upj.is_basket_of_8 as up_b8,
			upj.is_conference as up_conf,
			upjr.pub_year as up_year,
			upa.book_year as up_book_year,
			downj.is_basket_of_8 as down_b8,
			downj.is_conference as down_conf,
			downjr.pub_year as down_year,
			downa.book_year as down_book_year
		FROM litfam_citations c
			LEFT JOIN litfam_articles upa on upa.article_id = c.original_article_id
				LEFT JOIN litfam_journal_releases upjr on upjr.jr_id = upa.jr_id
					LEFT JOIN litfam_journals upj on upj.journal_id = upjr.journal_id
			LEFT JOIN litfam_articles downa on downa.article_id = c.derived_article_id
				LEFT JOIN litfam_journal_releases downjr on downjr.jr_id = downa.jr_id
					LEFT JOIN litfam_journals downj on downj.journal_id = downjr.journal_id
		".(
			isset($selectedId)
			? "
				WHERE upa.article_id IN (
					SELECT article_id FROM litfam_membership WHERE group_id = ".$selectedId."
				) OR downa.article_id IN (
					SELECT article_id FROM litfam_membership WHERE group_id = ".$selectedId."
				)
			"
			: ""
		)
		."
			ORDER BY ref_number ASC
	");
	
	$citations = array();
	while ($row = $res->fetch_assoc()) {
		array_push($citations, $row);
	}
	$res->close();
	$mysqli->close();
	
	header('Content-type: application/json');
	echo json_encode($citations, JSON_PRETTY_PRINT);
	
	
?>
