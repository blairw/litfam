<?php
$selectedId = (
	isset($_GET['groupId']) && is_int((int) $_GET['groupId'])
	? $_GET['groupId']
	: '0');
$showLabels = (
	isset($_GET['showLabels'])
	? $_GET['showLabels']
	: "false");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>3971t-Graph</title>
		<link rel="icon" type="image/png" href="frameworks/led-icon-set/chart_line.png?v=2" />
		<!--jquery-->
		<script src="frameworks/jquery-1.11.2.min.js"></script>
		<!--bootstrap-->
		<script src="frameworks/bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="frameworks/bootstrap-3.3.2-dist/css/bootstrap.min.css">
		<!--sigmajs-->
		<script src="frameworks/sigmajs/sigma.min.js"></script>
		<script src="frameworks/sigmajs/plugins/sigma.parsers.json.min.js"></script>
		<script src="frameworks/sigmajs/plugins/sigma.layout.forceAtlas2.min.js"></script>
		<script src="frameworks/dagre.min.js"></script>
		
		<script>
			var selectedGroupId = <?php echo $selectedId; ?>;
			var showLabels = <?php echo $showLabels; ?>
		</script>
		<script src="common.js"></script>
		<script src="ui-Graph.getColour.js"></script>
		<script src="ui-Graph.applyDagre.js"></script>
		<script src="ui-Graph.prepareTheGraph.js"></script>
		<script src="ui-Graph.logic.js"></script>
		<style>
			body { font-family: sans-serif; padding: 20px; }
			.panel-heading { font-size: 120%; font-weight: bold; }
			h1 { margin-top: 0em; margin-bottom: 0.5em; }
			.page-header { margin-top: 0; padding-top: 0; }
			@media print {
				#sidebar { display: none; }
			}
		</style>
	</head>
	<body onload="bodyDidLoad()">
		<h1 class="page-header">LitFam paper dependencies/citations Graph</h1>
		<div class="row">
			<div class="col-md-10">
				<div class="panel panel-default">
					<div class="panel-body">
						<div id="sigmajsContainer" style="height:85vh;margin:10px;"></div>
					</div>
				</div>
			</div>
			<div class="col-md-2" id="sidebar">
				<div class="panel panel-default">
					<div class="panel-heading">Guide</div>
					<ul class="list-group">
						<li class="list-group-item"><span style="color: #505693;">&#9608;&nbsp;&nbsp;</span>bo8 journal, &lt;2010</li>
						<li class="list-group-item"><span style="color: #4E9AFF;">&#9608;&nbsp;&nbsp;</span>bo8 journal, &gt;2010</li>
						<li class="list-group-item"><span style="color: #5EB231;">&#9608;&nbsp;&nbsp;</span>conference, &lt;2010</li>
						<li class="list-group-item"><span style="color: #A5E433;">&#9608;&nbsp;&nbsp;</span>conference, &gt;2010</li>
						<li class="list-group-item"><span style="color: #E59F00;">&#9608;&nbsp;&nbsp;</span>other journal, &lt;2010</li>
						<li class="list-group-item"><span style="color: #FFDF00;">&#9608;&nbsp;&nbsp;</span>other journal, &gt;2010</li>
						<li class="list-group-item"><span style="color: rgb(100,100,100);">&#9608;&nbsp;&nbsp;</span>no journal info</li>
					</ul>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">Group Filter</div>
					<ul class="list-group" id="ulGroups">
					</ul>
				</div>
			</div>
		</div>
	</body>
</html>
