<?php
	function getArrayIndexFromArray($someArray, $someArrayIdFieldName, $someId) {
		$returnObject = -1;
		for ($i = 0; $i < count($someArray); $i++) {
			if ($someArray[$i][$someArrayIdFieldName] == $someId) {
				$returnObject = $i;
			}
		}
		
		return $returnObject;
	}

	// connect to mysql
	include ('../litfam-db/db-MysqlAccess.php');
	
	$res1 = $mysqli->query("
		select distinct ca.article_id, ifnull(j.is_basket_of_8,2) as colour
		from (
			select original_article_id article_id from litfam_citations
			union all
			select derived_article_id article_id from litfam_citations
		) ca
		left join litfam_articles a on a.article_id = ca.article_id
		left join litfam_journal_releases jr on jr.jr_id = a.jr_id
		left join litfam_journals j on j.journal_id = jr.journal_id
	");
	$arr1 = array();
	while ($row1 = $res1->fetch_assoc()) {
		array_push($arr1, array(
			'name' => $row1['article_id'],
			'group' => (int) $row1['colour'] + 1,
		));
	}
	$res1->close();
	
	$res2= $mysqli->query("
		select * from litfam_citations
	");
	$arr2 = array();
	while ($row2 = $res2->fetch_assoc()) {
		array_push($arr2, array(
			'source' => getArrayIndexFromArray($arr1, 'name', $row2['original_article_id']),
			'target' => getArrayIndexFromArray($arr1, 'name', $row2['derived_article_id']),
			'value' => 3,
		));
	}
	$mysqli->close();
	
	$outputArray = array("nodes" => $arr1, "links" => $arr2);
	
	header('Content-type: application/json');
	echo json_encode($outputArray, JSON_PRETTY_PRINT);
	
	
?>
