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
	include ('db-HelperTools.php');
	
	$resArticles = $mysqli->query("
		select
			a.article_id AS article_id,
			a.newspaper_name,
			a.newspaper_date,
			a.book_title,
			a.book_year AS book_year,
			a.book_publisher AS book_publisher,
			jr.pub_year AS pub_year,
			jr.custom_journal_name,
			jr.custom_papersforthe,
			jr.custom_conf_name,
			jr.custom_conf_period,
			jr.custom_conf_location,
			a.title AS title,
			a.disambig_letter,
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
		from litfam_articles a
			left join litfam_journal_releases jr on jr.jr_id = a.jr_id
			left join litfam_journals j          on j.journal_id = jr.journal_id
		where a.article_id in (select article_id from litfam_membership where group_id = ".$selectedId.")
		order by a.article_id
	");
	
	$arrArticles = array();
	while ($row = $resArticles->fetch_assoc()) {
		array_push($arrArticles, $row);
		$arrArticles[count($arrArticles)-1]['authors'] = array();
	}
	$resArticles->close();
	
	$resAuthors = $mysqli->query("
		select au.author_id, author_lname, author_fname, author_mname, aus.article_id
		from litfam_authors au
			join litfam_authorship aus on aus.author_id = au.author_id
		order by aus.article_id, aus.sequence, aus.authorship_id
	");
	while ($row = $resAuthors->fetch_assoc()) {
		for ($i=0; $i<count($arrArticles); $i++) {
			if ($arrArticles[$i]['article_id'] == $row['article_id']) {
				// matched
				array_push($arrArticles[$i]['authors'], $row);
				$arrArticles[$i]['authors'][count($arrArticles[$i]['authors'])-1]['authorLine'] = ''
					.'{'.latexSpecialChars($arrArticles[$i]['authors'][count($arrArticles[$i]['authors'])-1]['author_lname']).'}'
					.(
						isset($arrArticles[$i]['authors'][count($arrArticles[$i]['authors'])-1]['author_fname'])
						? ', '.$arrArticles[$i]['authors'][count($arrArticles[$i]['authors'])-1]['author_fname']
						: ""
					)
					.(
						isset($arrArticles[$i]['authors'][count($arrArticles[$i]['authors'])-1]['author_mname'])
						? ' '.$arrArticles[$i]['authors'][count($arrArticles[$i]['authors'])-1]['author_mname']
						: ""
					)
				;
			}
		}
	}
	$resAuthors->close();
	$mysqli->close();
	
	// SUMMARISE AUTHORS INTO A SINGLE AUTHORSHIP LINE
	for ($i=0;$i<count($arrArticles);$i++) {
		$arrArticles[$i]['authorsLine'] = "";
		for ($j=0;$j<count($arrArticles[$i]['authors']);$j++) {
			$arrArticles[$i]['authorsLine'] .= $arrArticles[$i]['authors'][$j]['authorLine'];
			if ($j < count($arrArticles[$i]['authors'])-1) {
				$arrArticles[$i]['authorsLine'] .= ' and ';
			}
		}
	}
	
	// DOCUMENT TYPE and YEAR
	for ($i = 0; $i < count($arrArticles); $i++) {
		if ($arrArticles[$i]['is_conference'] == 1) {
			$arrArticles[$i]['documentType'] = 'misc';
		} else if (isset($arrArticles[$i]['book_title'])) {
			$arrArticles[$i]['documentType'] = 'incollection';
		} else if (isset($arrArticles[$i]['book_year'])) {
			$arrArticles[$i]['documentType'] = 'book';
		} else if (isset($arrArticles[$i]['journal_name']) || isset($arrArticles[$i]['newspaper_name']) ) {
			$arrArticles[$i]['documentType'] = 'article';
		} else {
			$arrArticles[$i]['documentType'] = 'misc';
		}
		
		// YEAR
		$arrArticles[$i]['documentYear'] = (
			$arrArticles[$i]['newspaper_date']
			? date("Y", strtotime($arrArticles[$i]['newspaper_date']))
			: (
				$arrArticles[$i]['book_year']
				? $arrArticles[$i]['book_year']
				: (
					$arrArticles[$i]['pub_year']
					? $arrArticles[$i]['pub_year']
					: null
				)
			)
		);
		
		// JOURNAL NAME
		$arrArticles[$i]['documentJournal'] = (
			$arrArticles[$i]['custom_journal_name']
			? $arrArticles[$i]['custom_journal_name']
			: (
				$arrArticles[$i]['newspaper_name']
				? $arrArticles[$i]['newspaper_name']
				: $arrArticles[$i]['journal_name']
			)
		);
		
		// URL
		if (isset($arrArticles[$i]['doi'])) {
			$arrArticles[$i]['documentUrl'] = 'http://dx.doi.org/'.$arrArticles[$i]['doi'];
		} else if (isset($arrArticles[$i]['url']) && $arrArticles[$i]['display_url'] == 1) {
			$arrArticles[$i]['documentUrl'] = $arrArticles[$i]['url'];
		}
		
		// NOTES
		$arrArticles[$i]['documentNote'] = '';
		if (isset($arrArticles[$i]['documentUrl'])) {
			$arrArticles[$i]['documentNote'] = 'Viewed '.date("j F Y", strtotime($arrArticles[$i]['create_ts']));
		}
		if ($arrArticles[$i]['is_conference'] == 1 || isset($arrArticles[$i]['custom_papersforthe'])) {
			$arrArticles[$i]['documentNote'] = 'Paper presented at the '
				.(
					$arrArticles[$i]['custom_papersforthe']
					? $arrArticles[$i]['custom_papersforthe']
					: (
						$arrArticles[$i]['custom_conf_name']
						? $arrArticles[$i]['custom_conf_name']
						: (
							$arrArticles[$i]['volume']
							? getOrdinalFromNumber($arrArticles[$i]['volume']).' '
							: ''
						).$arrArticles[$i]['journal_name']
					)
				)
				.(
					$arrArticles[$i]['custom_conf_location']
					? ', '.$arrArticles[$i]['custom_conf_location']
					: ''
				)
				.(
					$arrArticles[$i]['custom_conf_period']
					? ', '.$arrArticles[$i]['custom_conf_period'].' '.$arrArticles[$i]['pub_year']
					: ''
				)
				.'. '
				.$arrArticles[$i]['documentNote'];
		}
		if (isset($arrArticles[$i]['wp_ssrn_no'])) {
			$arrArticles[$i]['documentNote'] = 'Social Science Research Network working paper series no. '.$arrArticles[$i]['wp_ssrn_no'].'. '
				.$arrArticles[$i]['documentNote'];
		}
		
		// PUBLISHER
		if (isset($arrArticles[$i]['book_publisher'])) {
			$arrArticles[$i]['documentPublisher'] = $arrArticles[$i]['book_publisher'];
		} else if (isset($arrArticles[$i]['wp_ssrn_no'])) {
			$arrArticles[$i]['documentPublisher'] = 'Social Science Electronic Publishing';
		}
	}
	
	// OUTPUT
	header('Content-type: text/plain');
	foreach ($arrArticles as $row) {
		echo '@'.$row['documentType'].'{'.$row['article_id'].'';
		echo "\r\n\t".', author = {'.$row['authorsLine'].'}';
		echo "\r\n\t".', year = {'.$row['documentYear'].'}';
		echo "\r\n\t".', title = {{'.latexSpecialChars($row['title']).'}}';
		echo (
			$row['doi']
			? "\r\n\t".', doi = {'.latexSpecialChars($row['doi']).'}'
			: ''
		);
		echo (
			isset($row['documentUrl'])
			? "\r\n\t".', url = {\\\\\\url{'.latexSpecialChars($row['documentUrl']).'}}'
			: ''
		);
		echo (
			$row['documentType'] == 'article'
			? "\r\n\t".', journal = {'.latexSpecialChars($row['documentJournal']).'}'
			: ''
		);
		echo (
			isset($row['volume']) && $row['is_conference'] != 1
			? "\r\n\t".', volume = {'.$row['volume'].'}'
			: ''
		);
		echo (
			isset($row['issue']) && $row['is_conference'] != 1
			? "\r\n\t".', number = {'.$row['issue'].'}'
			: (
				isset($row['newspaper_date'])
				? "\r\n\t".', number = {'.date("j F", strtotime($row['newspaper_date'])).'}'
				: ''
			)
		);
		echo (
			$row['book_title']
			? "\r\n\t".', booktitle = {'.latexSpecialChars($row['book_title']).'}'
			: ''
		);
		echo (
			isset($row['documentPublisher'])
			? "\r\n\t".', publisher = {'.latexSpecialChars($row['documentPublisher']).'}'
			: ''
		);
		echo (
			$row['pg_begin'] && !$row['custom_conf_name'] && $row['is_conference'] != 1
			? "\r\n\t".', pages = {'.$row['pg_begin'].'-'.$row['pg_end'].'}'
			: ''
		);
		echo (
			$row['documentNote']
			? "\r\n\t".', note = {'.latexSpecialChars($row['documentNote']).'}'
			: ''
		);
		echo "\r\n}\r\n\r\n";
	}
	
/*
				.($row['custom_papersforthe'] ? ', papers for the '.$row['custom_papersforthe'] : '')
				
*/
	
?>
