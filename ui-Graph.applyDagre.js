function applyDagre(graphDataInput) {
	var g = new dagre.graphlib.Graph();
	g.setGraph({});
	g.setDefaultEdgeLabel(function() { return {}; });
	
	// extract nodes from input
	for (i = 0; i < graphDataInput.nodes.length; i++) {
		g.setNode(graphDataInput.nodes[i].id, {label: graphDataInput.nodes[i].label, width:100, height:150});
	}
	
	// extract edges from input
	for (i = 0; i < graphDataInput.edges.length; i++) {
		g.setEdge(graphDataInput.edges[i].source, graphDataInput.edges[i].target);
	}
	
	dagre.layout(g);
	for (i = 0; i < graphDataInput.nodes.length; i++) {
		graphDataInput.nodes[i].x = g.node(graphDataInput.nodes[i].id).x;
		graphDataInput.nodes[i].y = g.node(graphDataInput.nodes[i].id).y;
	}
	
	return graphDataInput;
}
