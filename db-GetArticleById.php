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
			a.article_id      article_id,
			a.title           title,
			a.doi             doi,
			a.abstract        abstract,
			a.url             url,
			a.bwanalysis_relevant,
			a.bwanalysis_synopsis,
			a.bwanalysis_empirical,
			a.bwanalysis_quantitative,
			a.bwanalysis_samplesize,
			a.bwanalysis_samplesource,
			aus.authorship_id authorship_id,
			aus.author_id     author_id,
			au.author_lname   author_lname,
			au.author_fname   author_fname,
			au.author_mname   author_mname,
			au.university,
			au.department,
			j.journal_id,
			jr.jr_id
		FROM 3971thesis_articles a
			LEFT JOIN 3971thesis_authorship aus ON aus.article_id = a.article_id
			LEFT JOIN 3971thesis_authors au ON au.author_id = aus.author_id
			LEFT JOIN 3971thesis_journal_releases jr ON jr.jr_id = a.jr_id
			LEFT JOIN 3971thesis_journals j ON j.journal_id = jr.journal_id
		WHERE a.article_id = ".$selectedId."
		ORDER BY a.article_id ASC, aus.sequence ASC, aus.authorship_id ASC
	");
	
	$arr = array();
	while ($row = $res->fetch_assoc()) {
		array_push($arr, $row);
	}
	$res->close();
	$mysqli->close();
	
	// cleanup arrays
	$narr = array();
	for ($i = 0; $i < count($arr); $i++) {
		// first try add authors to existing
		$found = false;
		for ($j = 0; $j < count($narr); $j++) {
			if (isset($narr[$j]["article_id"]) && $narr[$j]["article_id"] == $arr[$i]["article_id"]) {
				array_push($narr[$j]["authors"], array(
					"authorship_id" => $arr[$i]["authorship_id"],
					"author_id"     => $arr[$i]["author_id"],
					"author_lname"  => $arr[$i]["author_lname"],
					"author_fname"  => $arr[$i]["author_fname"],
					"author_mname"  => $arr[$i]["author_mname"],
					"university"    => $arr[$i]["university"],
					"department"    => $arr[$i]["department"],
				));
				$found = true;
			}
		}
		// if no existing then create new and add children if applicable
		if (!$found) {
			array_push($narr, array(
				"article_id"             => $arr[$i]["article_id"],
				"title"                  => $arr[$i]["title"],
				"doi"                    => $arr[$i]["doi"],
				"url"                    => $arr[$i]["url"],
				"abstract"               => $arr[$i]["abstract"],
				"journal_id"               => $arr[$i]["journal_id"],
				"jr_id"                    => $arr[$i]["jr_id"],
				"bwanalysis_relevant"        => $arr[$i]["bwanalysis_relevant"],
				"bwanalysis_synopsis"        => $arr[$i]["bwanalysis_synopsis"],
				"bwanalysis_empirical"       => $arr[$i]["bwanalysis_empirical"],
				"bwanalysis_quantitative"    => $arr[$i]["bwanalysis_quantitative"],
				"bwanalysis_samplesize"      => $arr[$i]["bwanalysis_samplesize"],
				"bwanalysis_samplesource"    => $arr[$i]["bwanalysis_samplesource"],
			));
			if ($arr[$i]["authorship_id"]) {
				$narr[count($narr)-1]["authors"] = [];
				array_push($narr[count($narr)-1]["authors"], array(
					"authorship_id" => $arr[$i]["authorship_id"],
					"author_id"     => $arr[$i]["author_id"],
					"author_lname"  => $arr[$i]["author_lname"],
					"author_fname"  => $arr[$i]["author_fname"],
					"author_mname"  => $arr[$i]["author_mname"],
					"university"    => $arr[$i]["university"],
					"department"    => $arr[$i]["department"],
				));
			}
		}
	}
	
	header('Content-type: application/json');
	echo json_encode($narr, JSON_PRETTY_PRINT);
	
	
?>
