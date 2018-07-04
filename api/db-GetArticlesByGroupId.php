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
	include ('../secret/db-MysqlAccess.php');
	
	//
	// GROUP DETAILS
	//
	$resGroup = $mysqli->query("
		SELECT *
		FROM litfam_groups
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
		SELECT
			group_concat(DISTINCT au.author_lname ORDER BY aus.sequence, aus.authorship_id) as lastnames,
			IFNULL(jr.pub_year,
				IFNULL(a.newspaper_date, a.book_year
				)
			) as year,
			a.disambig_letter,
			a.article_id, a.title, a.abstract, a.bwanalysis_synopsis,
			-- j.is_basket_of_8,
			-- j.abdc_rank,
			j.is_conference
		FROM litfam_articles a
			LEFT JOIN litfam_journal_releases jr on jr.jr_id = a.jr_id
				LEFT JOIN litfam_journals j on j.journal_id = jr.journal_id
			LEFT JOIN litfam_authorship aus on aus.article_id = a.article_id
				LEFT JOIN litfam_authors au on au.author_id = aus.author_id
		WHERE a.article_id IN (
			SELECT article_id
			FROM litfam_membership
			WHERE group_id = ".$selectedId."
		)
		GROUP BY a.article_id
		ORDER BY 1, 2, 3 ASC
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
		FROM litfam_authorship ash
		LEFT JOIN litfam_authors a ON a.author_id = ash.author_id
		WHERE ash.article_id IN (
			SELECT article_id
			FROM litfam_membership
			WHERE group_id = ".$selectedId."
		)
		ORDER BY ash.article_id, ash.sequence, ash.authorship_id, ash.author_id
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
