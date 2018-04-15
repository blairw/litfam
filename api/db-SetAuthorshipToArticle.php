<?php
	if (
		isset($_POST['articleId'])
		&& isset($_POST['authorIds'])
	) {
		$selectedArticleId = intval($_POST['articleId']);
		$selectedAuthorIds = $_POST['authorIds'];
		var_dump($_POST);
	}
	
	// connect to mysql
	include ('../secret/db-MysqlAccess.php');
	if (!($stmt = $mysqli->prepare("INSERT INTO litfam_authorship (author_id, article_id) VALUES (?, ?)"))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	
	for ($i = 0; $i < count($selectedAuthorIds); $i++) {
		$thisAuthorId = intval($selectedAuthorIds[$i]);
		if (!$stmt->bind_param("ii", $thisAuthorId, $selectedArticleId)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
	}
	
	$stmt->close();
	$mysqli->close();
	
	// header('Location: ui-AddAuthorsToArticle.php?id='.$_POST['articleId']);
?>
