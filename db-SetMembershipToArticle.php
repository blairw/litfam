<?php
	if (
		isset($_POST['articleId'])
		&& isset($_POST['groupIds'])
	) {
		$selectedArticleId = intval($_POST['articleId']);
		$selectedGroupIds = $_POST['groupIds'];
		var_dump($_POST);
	}
	
	// connect to mysql
	include ('../3971thesis-db/db-MysqlAccess.php');
	if (!($stmt = $mysqli->prepare("INSERT INTO 3971thesis_groups (group_id, article_id) VALUES (?, ?)"))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	
	for ($i = 0; $i < count($selectedGroupIds); $i++) {
		$thisGroupId = intval($selectedGroupIds[$i]);
		if (!$stmt->bind_param("ii", $thisGroupId, $selectedArticleId)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
	}
	
	$stmt->close();
	$mysqli->close();
?>
