<?php
	if (
		isset($_POST['articleId'])
		&& isset($_POST['groupId'])
	) {
		$selectedArticleId = intval($_POST['articleId']);
		$selectedGroupId = intval($_POST['groupId']);
		var_dump($_POST);
	}
	
	// connect to mysql
	include ('../secret/db-MysqlAccess.php');
	if (!($stmt = $mysqli->prepare("INSERT INTO litfam_membership (group_id, article_id) VALUES (?, ?)"))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	
	if (!$stmt->bind_param("ii", $selectedGroupId, $selectedArticleId)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	$stmt->close();
	$mysqli->close();
?>
