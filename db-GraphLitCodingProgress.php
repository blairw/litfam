<?php
	// connect to mysql
	include ('../litfam-db/db-MysqlAccess.php');
	
	$res = $mysqli->query("
		select
			alldates.create_date,
			ifnull(counter_indexed,0) counter_indexed,
			ifnull(counter_incl,0) counter_incl,
			ifnull(counter_excl,0) counter_excl,
			ifnull(counter_incl,0)+ifnull(counter_excl,0) as total_coded
		from
			(
				select distinct create_date from
				(
					select distinct date(a.create_ts) as create_date from litfam_articles a
						join litfam_journal_releases jr on a.jr_id = jr.jr_id
						join litfam_journals j on j.journal_id = jr.journal_id
						where j.is_basket_of_8 = 1 and jr.pub_year >= 2010
					union all select distinct date(create_ts) as create_date from litfam_membership where group_id = 1
					union all select distinct date(create_ts) as create_date from litfam_membership where group_id = 5
					order by 1 asc
				) temp
			) alldates
		left join
			(
				select
					count(a.article_id) counter_indexed,
					date(a.create_ts) as create_date
				from litfam_articles a
					join litfam_journal_releases jr on a.jr_id = jr.jr_id
					join litfam_journals j on j.journal_id = jr.journal_id
				where j.is_basket_of_8 = 1 and jr.pub_year >= 2010
				group by date(create_ts)
			) articles on articles.create_date = alldates.create_date
		left join
			(
				select
					count(membership_id) counter_incl,
					date(create_ts) as create_date
				from litfam_membership
				where group_id = 1
				group by date(create_ts)
			) includes on includes.create_date = alldates.create_date
		left join
			(
				select
					count(membership_id) counter_excl,
					date(create_ts) as create_date
				from litfam_membership
				where group_id = 5
				group by date(create_ts)
			) excludes on excludes.create_date = alldates.create_date
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
