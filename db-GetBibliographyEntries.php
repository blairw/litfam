<?php
	// connect to mysql
	include ('../3971thesis-db/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		select
			a.article_id AS article_id,
			group_concat(
				ifnull(au.author_lname,''),' ',
				ifnull(concat(left(au.author_fname,1),'.'),''),
				ifnull(concat(au.author_minitials,'.'),'')
				order by aus.sequence ASC, aus.authorship_id ASC separator ', '
			) AS authors,
			jr.pub_year AS pub_year,
			a.title AS title,
			j.journal_name AS journal_name,
			jr.volume AS volume,
			jr.issue AS issue,
			jr.part AS part,
			a.pg_begin AS pg_begin,
			a.pg_end AS pg_end
		from 3971thesis_articles a
			left join 3971thesis_journal_releases jr on jr.jr_id = a.jr_id
			left join 3971thesis_journals j          on j.journal_id = jr.journal_id
			left join 3971thesis_authorship aus      on aus.article_id = a.article_id
			left join 3971thesis_authors au          on au.author_id = aus.author_id
		group by a.article_id
		order by jr.journal_id,jr.pub_year,jr.pub_month,a.pg_begin
	");
	
	$arr = array();
	while ($row = $res->fetch_assoc()) {
		array_push($arr, array(
			"article_id" => $row['article_id'],
			/*
			"authors"      => $row['authors'],
			"pub_year"     => $row['pub_year'],
			"title"        => $row['title'],
			"journal_name" => $row['journal_name'],
			"volume"       => $row['volume'],
			"issue"        => $row['issue'],
			"part"         => $row['part'],
			"pg_begin"     => $row['pg_begin'],
			"pg_end"       => $row['pg_end'],
			*/
			"html_citation"
				=> $row['authors'].' ('.$row['pub_year'].') \''
				.$row['title'].'\', <em>'
				.$row['journal_name'].'</em>'
				.($row['volume'] ? ', vol. '.$row['volume'] : '')
				.($row['issue'] ? ', iss. '.$row['issue'] : '')
				.($row['pg_begin'] ? ', pp. '.$row['pg_begin'].'-'.$row['pg_end'] : '')
		));
	}
	$res->close();
	$mysqli->close();
	
	header('Content-type: application/json');
	echo json_encode($arr, JSON_PRETTY_PRINT);
	
	
?>
