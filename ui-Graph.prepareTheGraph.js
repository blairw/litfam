function prepareTheGraph(ajaxResponse) {
	var prepareGraph = { nodes: [], edges: [] };
	for (i = 0; i < ajaxResponse.length; i++) {
		thisOriginalArticleId = ajaxResponse[i].original_article_id;
		thisDerivedArticleId = ajaxResponse[i].derived_article_id;
		
		originalArticleIsFound = false;
		// scan for original article id
		for (j = 0; j < prepareGraph.nodes.length; j++) {
			if (prepareGraph.nodes[j].id == thisOriginalArticleId) {
				originalArticleIsFound = true;
				prepareGraph.nodes[j].size += 1;
			}
		}
		if (!originalArticleIsFound) {
			prepareGraph.nodes.push({
				id: thisOriginalArticleId.toString(),
				label: '#' + thisOriginalArticleId + (ajaxResponse[i].up_year ? " ('"+ajaxResponse[i].up_year.substring(2,4)+')' : ''),
				size: 1,
				color: getColour(ajaxResponse[i],'up'),
				originalColor: getColour(ajaxResponse[i],'up'),
				x: Math.floor(Math.random() * 6) + 1,
				y: Math.floor(Math.random() * 6) + 1
			});
		}
		
		// scan for derived article id
		derivedArticleIdIsFound = false;
		for (j = 0; j < prepareGraph.nodes.length; j++) {
			if (prepareGraph.nodes[j].id == thisDerivedArticleId) {
				derivedArticleIdIsFound = true;
				// disabled this - it would make the blob
				// bigger for the article citing others
				// prepareGraph.nodes[j].size += 1;
			}
		}
		if (!derivedArticleIdIsFound) {
			prepareGraph.nodes.push({
				id: thisDerivedArticleId.toString(),
				label: '#' + thisDerivedArticleId + (ajaxResponse[i].down_year ? " ('"+ajaxResponse[i].down_year.substring(2,4)+')' : ''),
				size: 1,
				color: getColour(ajaxResponse[i],'down'),
				originalColor: getColour(ajaxResponse[i],'down'),
				x: Math.floor(Math.random() * 10) + 1,
				y: Math.floor(Math.random() * 10) + 1
			});
		}
		
		
		// add edge
		prepareGraph.edges.push({
			id: i.toString(),
			source: thisOriginalArticleId.toString(),
			target: thisDerivedArticleId.toString(),
			type: 'arrow'
		});
		
	}
	prepareGraph = applyDagre(prepareGraph);
	return prepareGraph;
}
