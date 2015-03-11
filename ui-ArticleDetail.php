<?php
$thisArticleId = (
	isset($_GET['id']) && is_int((int) $_GET['id'])
	? $_GET['id']
	: '-1');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>ArticleDetail</title>
		<script>
			var selectedArticleId = <?php echo $thisArticleId; ?>;
		</script>
		<meta charset="utf-8">
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--jquery-->
		<script src="frameworks/jquery-1.11.2.min.js"></script>
		<!--bootstrap-->
		<script src="frameworks/bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="frameworks/bootstrap-3.3.2-dist/css/bootstrap.min.css">
		<!--select2-->
		<script src="frameworks/select2-3.5.2/select2.min.js"></script>
		<link rel="stylesheet" href="frameworks/select2-3.5.2/select2.css" />
		<link rel="stylesheet" href="frameworks/select2-3.5.2/select2-bootstrap.css" />
		<!--font-awesome-->
		<link rel="stylesheet" href="frameworks/font-awesome-4.3.0/css/font-awesome.min.css">
		<!--underscore-->
		<script src="frameworks/underscore-min.js"></script>
		<!--other-->
		<script src="ui-ArticleDetail.logic.js"></script>
		<link rel="stylesheet" href="common.css">
		<link rel="stylesheet" href="ui-ArticleDetail.css">
		<link rel="icon" type="image/png" href="frameworks/fugue-subset/document-smiley.png?v=2" />
	</head>
	<body onload="bodyDidLoad()">
		<div class="row">
			<div class="col-md-6">
				<h1 id="divArticleName"></h1>
				<p id="pArticleSubtitle"></p>
				<p id="pForJournalDetails"></p>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Overview</div>
					<ul class="list-group" id="ulListGroupAnalysisOverview"></ul>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Groups</div>
					<ul class="list-group" id="ulListGroupGroups"></ul>
				</div>
			</div>
		</div>
		<h2 class="page-header">Catalogue Master Data</h2>
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Abstract</div>
					<div class="panel-body" id="divPanelBodyAbstract">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Read this article</div>
					<ul class="list-group" id="ulListGroupReadThisArticle"></ul>
				</div>
			</div>
		</div>
		<h2 class="page-header">Master Data Maintenance</h2>
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Metadata from 3971thesis database</div>
					<div class="panel-body">
						<div id="divArticleDoi"></div>
						<div><strong>Authors:</strong></div>
						<ul id="ulAuthors"></ul>
					</div>
					<div class="panel-footer">
						<div class="input-group select2-bootstrap-append">
							<select id="selectAuthors" class="form-control select2" multiple="multiple">
								<option></option>
							</select>
							<span class="input-group-btn">
								<button class="btn btn-success" onclick="submitNewAuthorship()"><i class="fa fa-upload"></i>&nbsp;&nbsp;Save</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">DOI details</div>
					<div class="panel-body" id="divDoiDetailsPanelBody"></div>
					<div class="panel-footer" id="divDoiDetailsPanelFooter"></div>
				</div>
			</div>
		</div>
		<h2 class="page-header">Citation Analysis</h2>
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Upstream</div>
					<ul class="list-group" id="citationsUpstream"></ul>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Downstream</div>
					<ul class="list-group" id="citationsDownstream"></ul>
				</div>
			</div>
		</div>
	</body>
</html>
