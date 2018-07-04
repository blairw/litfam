<?php
	if (isset($_GET['id']) && is_int((int) $_GET['id'])) {
		// only continue if id is set and it is an int
		$selectedId = $_GET['id'];
	} else {
		// send them to the index page whatever it may be
		header('Location: ./');
	}
	
	$narr = array(
		'articleDetails' => array(),
		'authors' => array(),
		'groups' => array(),
		'theories' => array(),
	);
	// connect to mysql
	include ('../secret/db-MysqlAccess.php');
	
	//
	// ARTICLE DETAILS
	//

	// TODO: fix baskets
	$resArticle = $mysqli->query("
		SELECT a.*, j.journal_id, j.journal_name,
			-- j.is_basket_of_8,
			-- j.abdc_rank,
			j.is_conference
		FROM
			litfam_articles a
			LEFT JOIN litfam_journal_releases jr ON jr.jr_id = a.jr_id
			LEFT JOIN litfam_journals j ON j.journal_id = jr.journal_id
		WHERE a.article_id = ".$selectedId."
	");
	while ($rowArticle = $resArticle->fetch_assoc()) {
		foreach ($rowArticle as $key => $value) {
			// $rowArticle[$key] = htmlspecialchars($value);
		}
		$narr['articleDetails'] = $rowArticle;
	}
	$resArticle->close();
	
	//
	// AUTHOR DETAILS
	//
	$resAuthors = $mysqli->query("
		SELECT *
		FROM litfam_authorship aus
			LEFT JOIN litfam_authors au ON au.author_id = aus.author_id
		WHERE aus.article_id = ".$selectedId."
		ORDER BY aus.sequence ASC, aus.authorship_id ASC
	");
	while ($rowAuthors = $resAuthors->fetch_assoc()) {
		array_push($narr['authors'], $rowAuthors);
	}
	$resAuthors->close();
	
	//
	// GROUP DETAILS
	//
	$resGroups = $mysqli->query("
		SELECT *
		FROM litfam_membership m
		LEFT JOIN litfam_groups g ON g.group_id = m.group_id
		WHERE m.article_id = ".$selectedId."
	");
	while ($rowGroups = $resGroups->fetch_assoc()) {
		array_push($narr['groups'], $rowGroups);
	}
	$resGroups->close();
	
	
	//
	// THEORY DETAILS
	//
	$resTheory = $mysqli->query("
		SELECT *
		FROM litfam_theory_usage tu
		LEFT JOIN litfam_theory t ON t.theory_id = tu.theory_id
		WHERE tu.article_id = ".$selectedId."
	");
	while ($rowTheory = $resTheory->fetch_assoc()) {
		array_push($narr['theories'], $rowTheory);
	}
	$resTheory->close();


	$mysqli->close();
	header('Content-type: application/json');
	echo json_encode($narr, JSON_PRETTY_PRINT);
	
	
?>
