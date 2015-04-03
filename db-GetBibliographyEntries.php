<?php
	// connect to mysql
	include ('../3971thesis-db/db-MysqlAccess.php');
	include ('db-HelperTools.php');
	
	$resArticles = $mysqli->query("
		select
			a.article_id AS article_id,
			a.book_year AS book_year,
			a.book_publisher AS book_publisher,
			jr.pub_year AS pub_year,
			jr.custom_journal_name,
			jr.custom_conf_period,
			jr.custom_conf_location,
			a.title AS title,
			a.doi,
			a.url, a.display_url,
			j.journal_name AS journal_name,
			j.is_conference,
			jr.volume AS volume,
			jr.issue AS issue,
			jr.part AS part,
			a.pg_begin AS pg_begin,
			a.pg_end AS pg_end,
			a.wp_ssrn_no,
			a.create_ts
		from 3971thesis_articles a
			left join 3971thesis_journal_releases jr on jr.jr_id = a.jr_id
			left join 3971thesis_journals j          on j.journal_id = jr.journal_id
		order by jr.journal_id,jr.pub_year,jr.pub_month,a.pg_begin
	");
	
	$arrArticles = array();
	while ($row = $resArticles->fetch_assoc()) {
		array_push($arrArticles, $row);
		$arrArticles[count($arrArticles)-1]['authors'] = array();
	}
	$resArticles->close();
	
	$resAuthors = $mysqli->query("
		select au.author_id, author_lname, author_fname, author_minitials, aus.article_id
		from 3971thesis_authors au
			join 3971thesis_authorship aus on aus.author_id = au.author_id
		order by aus.article_id, aus.sequence, aus.authorship_id
	");
	while ($row = $resAuthors->fetch_assoc()) {
		for ($i=0; $i<count($arrArticles); $i++) {
			if ($arrArticles[$i]['article_id'] == $row['article_id']) {
				// matched
				array_push($arrArticles[$i]['authors'], $row);
				$arrArticles[$i]['authors'][count($arrArticles[$i]['authors'])-1]['authorLine'] = ''
					.$arrArticles[$i]['authors'][count($arrArticles[$i]['authors'])-1]['author_lname']
					.(
						isset($arrArticles[$i]['authors'][count($arrArticles[$i]['authors'])-1]['author_fname'])
						? ' '.substr($arrArticles[$i]['authors'][count($arrArticles[$i]['authors'])-1]['author_fname'],0,1).'.'
						: ""
					)
					.(
						isset($arrArticles[$i]['authors'][count($arrArticles[$i]['authors'])-1]['author_minitials'])
						? substr($arrArticles[$i]['authors'][count($arrArticles[$i]['authors'])-1]['author_minitials'],0,1).'.'
						: ""
					)
				;
			}
		}
	}
	$resAuthors->close();
	
	$mysqli->close();
	
	//
	// SUMMARISE AUTHORS INTO A SINGLE AUTHORSHIP LINE
	//
	for ($i=0;$i<count($arrArticles);$i++) {
		$arrArticles[$i]['authorsLine'] = "";
		for ($j=0;$j<count($arrArticles[$i]['authors']);$j++) {
			$arrArticles[$i]['authorsLine'] .= $arrArticles[$i]['authors'][$j]['authorLine'];
			if ($j == count($arrArticles[$i]['authors'])-2) {
				$arrArticles[$i]['authorsLine'] .= " & ";
			} else if ($j < count($arrArticles[$i]['authors'])-1) {
				$arrArticles[$i]['authorsLine'] .= ", ";
			}
		}
	}
	
	$arrOutput = array();
	foreach ($arrArticles as $row) {
		array_push($arrOutput, array(
			"article_id" => $row['article_id'],
			"html_citation"
				=> (isset($row['authorsLine']) ? $row['authorsLine'] : "")
				.' '.($row['book_year'] ? $row['book_year'] : $row['pub_year']).", "
				.($row['book_year'] ? "<em>" : "'")
				.$row['title']
				.($row['book_year'] ? "</em>" : "'")
				.($row['wp_ssrn_no'] ? ", Social Science Research Network working paper series no. ".$row['wp_ssrn_no'] : "")
				.($row['book_publisher'] ? ", ".$row['book_publisher'] : "")
				.(
					$row['custom_journal_name']
					? ', <em>'.$row['custom_journal_name'].'</em>'
					: (
						$row['journal_name']
						? ", <em>".(
							$row['is_conference'] == 1
							? "Proceedings of the ".(
								$row['volume']
								? getOrdinalFromNumber($row['volume'])
								: ""
							)." "
							: ""
						).$row['journal_name'].'</em>'
						: ""
					)
				)
				.($row['custom_conf_location'] ? ', '.$row['custom_conf_location'] : '')
				.($row['custom_conf_period'] ? ', '.$row['custom_conf_period'].' '.$row['pub_year'] : '')
				.($row['volume'] && !$row['is_conference'] == 1 ? ', vol. '.$row['volume'] : '')
				.($row['issue'] ? ', no. '.$row['issue'] : '')
				.($row['pg_begin'] ? ', pp. '.$row['pg_begin'].'-'.$row['pg_end'] : '')
				.(
					isset($row['doi'])
					? ', viewed '.date("j F Y", strtotime($row['create_ts'])).", &lt;"."http://dx.doi.org/".$row['doi']."&gt;"
					: (
						$row['display_url'] == 1
						? ', viewed '.date("j F Y", strtotime($row['create_ts'])).', &lt;'.$row['url']."&gt;"
						: ""
					)
				)
		));
	}
	
	header('Content-type: application/json');
	echo json_encode($arrOutput, JSON_PRETTY_PRINT);
	
	
?>
