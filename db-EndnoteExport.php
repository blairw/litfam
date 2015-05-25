<?php
	
	// connect to mysql
	include ('../3971thesis-db/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		SELECT
			a.article_id, a.title, a.pg_begin, a.pg_end, a.url as article_url, a.doi,
			au.author_lname, au.author_fname, au.author_mname, au.author_minitials,
			j.journal_name,
			jr.pub_year, jr.pub_month, jr.volume, jr.issue
		FROM 3971thesis_articles a
		JOIN 3971thesis_journal_releases jr on jr.jr_id = a.jr_id
			LEFT JOIN 3971thesis_journals j on j.journal_id = jr.journal_id
		JOIN 3971thesis_authorship aus on aus.article_id = a.article_id
			LEFT JOIN 3971thesis_authors au on au.author_id = aus.author_id
		WHERE a.article_id IN (
			SELECT article_id
			FROM 3971thesis_membership
			WHERE group_id = 19
		)
	");
	
	$arr = array();
	while ($row = $res->fetch_assoc()) {
		array_push($arr, $row);
	}
	$res->close();
	$mysqli->close();
	
	$narr = array();
	for ($i=0;$i<count($arr);$i++) {
		// search for existing article
		$found = false;
		for ($j=0;$j<count($narr);$j++) {
			if (isset($narr[$j]["article_id"]) && $narr[$j]["article_id"] == $arr[$i]["article_id"]) {
				$found = true;
				array_push($narr[$j]['authors'], array(
					'author_lname'      => $arr[$i]['author_lname'],
					'author_fname'      => $arr[$i]['author_fname'],
					'author_mname'      => $arr[$i]['author_mname'],
					'author_minitials'  => $arr[$i]['author_minitials'],
				));
			}
		}
		if (!$found) {
			array_push($narr, array(
				'article_id'    => $arr[$i]['article_id'],
				'article_url'   => $arr[$i]['article_url'],
				'doi'           => $arr[$i]['doi'],
				'title'         => $arr[$i]['title'],
				'authors'       => array(),
				'journal_name'  => $arr[$i]['journal_name'],
				'pub_year'      => $arr[$i]['pub_year'],
				'pub_month'     => $arr[$i]['pub_month'],
				'volume'        => $arr[$i]['volume'],
				'issue'         => $arr[$i]['issue'],
				'pg_begin'      => $arr[$i]['pg_begin'],
				'pg_end'        => $arr[$i]['pg_end'],
			));
			if (null != $arr[$i]['author_lname']) {
				array_push($narr[count($narr)-1]['authors'], array(
					'author_lname'     => $arr[$i]['author_lname'],
					'author_fname'     => $arr[$i]['author_fname'],
					'author_mname'     => $arr[$i]['author_mname'],
					'author_minitials' => $arr[$i]['author_minitials'],
				));
			}
		}
		
	}
	
	header('Content-type: application/xml');
	echo '<?xml version="1.0" encoding="UTF-8" ?>';
	echo '<xml>';
	echo '<records>';
	for ($i=0;$i<count($narr);$i++) {
		echo '<record>';
		echo '<ref-type name="Journal Article">17</ref-type>';
		echo '<contributors><authors>';
		for ($j=0;$j<count($narr[$i]['authors']);$j++) {
			echo '<author>'
				.$narr[$i]['authors'][$j]['author_lname']
				.', '.$narr[$i]['authors'][$j]['author_fname']
				.' '.$narr[$i]['authors'][$j]['author_mname']
				.'</author>';
		}
		echo '</authors></contributors>';
		echo '<titles>';
		echo '<title>'.htmlspecialchars($narr[$i]['title']).'</title>';
		echo '<secondary-title>'.htmlentities($narr[$i]['journal_name']).'</secondary-title>';
		echo '</titles>';
		echo '<periodical><full-title>'.htmlentities($narr[$i]['journal_name']).'</full-title></periodical>';
		if (isset($narr[$i]['pg_begin'])) {
			echo '<pages>'.$narr[$i]['pg_begin'].'-'.$narr[$i]['pg_end'].'</pages>';
		}
		echo '<volume>'.$narr[$i]['volume'].'</volume>';
		echo '<number>'.$narr[$i]['issue'].'</number>';
		echo '<dates><year>'.$narr[$i]['pub_year'].'</year></dates>';
		if (isset($narr[$i]['article_url'])) {
			echo '<urls><related-urls><url>http://localhost/3971thesis-files/'.htmlentities($narr[$i]['article_id']).".pdf".'</url></related-urls></urls>';
		}
		echo '<electronic-resource-num>'.htmlentities($narr[$i]['doi']).'</electronic-resource-num>';
		echo '</record>';
		echo "\r\n";
	}
	echo '</records>';
	echo '</xml>';
?>
