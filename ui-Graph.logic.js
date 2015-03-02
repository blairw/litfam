function bodyDidLoad() {
	// neighbours function
	sigma.classes.graph.addMethod('neighbors', function(nodeId) {
		var k,
		neighbors = {},
		index = this.allNeighborsIndex[nodeId] || {};

		for (k in index)
		neighbors[k] = this.nodesIndex[k];

		return neighbors;
	});
	
	$.get("db-GetAllCitations.php", function(ajaxResponse) {
		
		var prepareRenderer = {
			container: document.getElementById('sigmajsContainer'),
			type: 'canvas'
		}
		var prepareGraph = prepareTheGraph(ajaxResponse);
		
		// sigmajs
		s = new sigma({
			graph: prepareGraph,
			container: 'sigmajsContainer',
			renderer: prepareRenderer,
			settings: {
				edgeColor: 'default',
				defaultEdgeColor: 'gray',
				minNodeSize: 7,
				maxNodeSize: 40,
				minEdgeSize: 1,
				maxEdgeSize: 10,
				arrowSizeRatio: 2.5,
				minArrowSize: 2.5,
				zoomMin: 1.0,
				zoomMax: 1.0
			}
		});
		//s.startForceAtlas2({gravity:0, edgeWeightInfluence:9000, jitterTolerance:0, outboundAttractionDistribution:90});
		// s.stopForceAtlas2();

		
		s.bind('clickNode', function(e) {
		var nodeId = e.data.node.id,
		toKeep = s.graph.neighbors(nodeId);
		toKeep[nodeId] = e.data.node;

		s.graph.nodes().forEach(function(n) {
		if (toKeep[n.id])
		n.color = n.originalColor;
		else
		n.color = '#eee';
		});

		s.graph.edges().forEach(function(e) {
			if (toKeep[e.source] && toKeep[e.target])
			e.color = e.originalColor;
			else
			e.color = '#eee';
			});

			// Since the data has been modified, we need to
			// call the refresh method to make the colors
			// update effective.
			s.refresh();
			});

			// When the stage is clicked, we just color each
			// node and edge with its original color.
			s.bind('clickStage', function(e) {
			s.graph.nodes().forEach(function(n) {
			n.color = n.originalColor;
			});

			s.graph.edges().forEach(function(e) {
			e.color = e.originalColor;
			});

			// Same as in the previous event:
			s.refresh();
		});
	});
}
