<?php
	
	// connect to mysql
	include ('../3971thesis-db/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		SELECT
			a.article_id, a.title,
			au.author_lname, au.author_fname, au.author_mname, au.author_minitials,
			j.journal_name, 
			jr.pub_year, jr.pub_month, jr.volume, jr.issue
		FROM 3971thesis_articles a
		JOIN 3971thesis_journal_releases jr on jr.jr_id = a.jr_id
			LEFT JOIN 3971thesis_journals j on j.journal_id = jr.journal_id
		JOIN 3971thesis_authorship aus on aus.article_id = a.article_id
			LEFT JOIN 3971thesis_authors au on au.author_id = aus.author_id
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
				'title'         => $arr[$i]['title'],
				'authors'       => array(),
				'journal_name'  => $arr[$i]['journal_name'],
				'pub_year'      => $arr[$i]['pub_year'],
				'pub_month'     => $arr[$i]['pub_month'],
				'volume'        => $arr[$i]['volume'],
				'issue'         => $arr[$i]['issue'],
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
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	echo '<xml>';
	echo '<records>';
	for ($i=0;$i<count($narr);$i++) {
		echo '<record>';
		echo '<ref-type name="Journal Article">17</ref-type>';
		echo '<contributors><authors>';
		for ($j=0;$j<count($narr[$i]['authors']);$j++) {
			echo '<author>';
			echo '<style face="normal" font="default" size="100%">'
				.$narr[$i]['authors'][$j]['author_lname']
				.', '.$narr[$i]['authors'][$j]['author_fname']
				.' '.$narr[$i]['authors'][$j]['author_mname']
				.'</style>';
			echo '</author>';
		}
		echo '</authors></contributors>';
		echo '<titles>';
		echo '<title>';
		echo '<style face="normal" font="default" size="100%">'.$narr[$i]['title'].'</style>';
		echo '</title>';
		echo '</titles>';
		echo '<periodical><full-title>';
		echo '<style face="normal" font="default" size="100%">'.htmlentities($narr[$i]['journal_name']).'</style>';
		echo '</full-title></periodical>';
		echo '</record>';
	}
	echo '</records>';
	echo '</xml>';
?>
