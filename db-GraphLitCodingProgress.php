<?php
	// connect to mysql
	include ('../3971thesis-db/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		select
			articles.create_date,
			ifnull(counter_indexed,0) counter_indexed,
			ifnull(counter_incl,0) counter_incl,
			ifnull(counter_excl,0) counter_excl,
			ifnull(counter_incl+counter_excl,0) as total_coded
		from
			(
				select
					count(a.article_id) counter_indexed,
					date(a.create_ts) as create_date
				from 3971thesis_articles a
					left join 3971thesis_journal_releases jr on a.jr_id = jr.jr_id
					left join 3971thesis_journals j on j.journal_id = jr.journal_id
				where j.is_basket_of_8 = 1
				group by date(create_ts)
			) articles
		left join
			(
				select
					count(membership_id) counter_incl,
					date(create_ts) as create_date
				from 3971thesis_membership
				where group_id = 1
				group by date(create_ts)
			) includes on articles.create_date = includes.create_date
		left join
			(
				select
					count(membership_id) counter_excl,
					date(create_ts) as create_date
				from 3971thesis_membership
				where group_id = 5
				group by date(create_ts)
			) excludes on includes.create_date = excludes.create_date
		order by 1 asc
	");
	
	$arr = array();
	while ($row = $res->fetch_assoc()) {
		array_push($arr, $row	);
	}
	$res->close();
	$mysqli->close();
	
	header('Content-type: application/json');
	echo json_encode($arr, JSON_PRETTY_PRINT);
?>
