var NOT_RELEVANT_GROUP = 5;

function bodyDidLoad() {
	refreshAllArticlesList();
}

function refreshAllArticlesList() {
	$.get("db-GetAllBwAnalysisStatus.php", function(ajaxResponse) {
		var countersForEachField = {
			authors:      0,
			litcoding:    0,
			synopsis:     0,
			empirical:    0,
			samplesize:   0,
			samplesource: 0,
			itemsCounted: 0
		};
		for (i=0;i<ajaxResponse.length;i++) {
			countersForEachField.itemsCounted++;
			if (ajaxResponse[i].authors != ' ')                  countersForEachField.authors++;
			if (ajaxResponse[i].group_id != null)                countersForEachField.litcoding++;
			if (ajaxResponse[i].bwanalysis_synopsis != null)     countersForEachField.synopsis++;
			if (ajaxResponse[i].bwanalysis_empirical != null)    countersForEachField.empirical++;
			if (ajaxResponse[i].bwanalysis_samplesize != null)   countersForEachField.samplesize++;
			if (ajaxResponse[i].bwanalysis_samplesource != null) countersForEachField.samplesource++;
		}
		
		
		$("#tbodyForAllArticles").html(
			"<tr><th>ID"
			+ "</th><th>Authors"
				+ " <small class='myRegularWeight'>("
				+countersForEachField.authors+"/"+countersForEachField.itemsCounted
				+"&nbsp;=&nbsp;"+Math.floor(100*countersForEachField.authors/countersForEachField.itemsCounted)+"%"
				+")</small>"
			+ "</th><th>Title"
			+ "</th><th>Journal"
			+ "</th><th>Volume"
			+ "</th><th>Issue"
			+ "</th><th>Literature Coding?"
				+ " <small class='myRegularWeight'>("
				+countersForEachField.litcoding+"/"+countersForEachField.itemsCounted
				+"&nbsp;=&nbsp;"+Math.floor(100*countersForEachField.litcoding/countersForEachField.itemsCounted)+"%"
				+")</small>"
			+ "</th><th>Synopsis?"
				+ " <small class='myRegularWeight'>("
				+countersForEachField.synopsis+"/"+countersForEachField.itemsCounted
				+"&nbsp;=&nbsp;"+Math.floor(100*countersForEachField.synopsis/countersForEachField.itemsCounted)+"%"
				+")</small>"
			+ "</th><th>Empirical?"
				+ " <small class='myRegularWeight'>("
				+countersForEachField.empirical+"/"+countersForEachField.itemsCounted
				+"&nbsp;=&nbsp;"+Math.floor(100*countersForEachField.empirical/countersForEachField.itemsCounted)+"%"
				+")</small>"
			+ "</th><th>Sample Size"
				+ " <small class='myRegularWeight'>("
				+countersForEachField.samplesize+"/"+countersForEachField.itemsCounted
				+"&nbsp;=&nbsp;"+Math.floor(100*countersForEachField.samplesize/countersForEachField.itemsCounted)+"%"
				+")</small>"
			+ "</th><th>Sample Source"
				+ " <small class='myRegularWeight'>("
				+countersForEachField.samplesource+"/"+countersForEachField.itemsCounted
				+"&nbsp;=&nbsp;"+Math.floor(100*countersForEachField.samplesource/countersForEachField.itemsCounted)+"%"
				+")</small>"
			+ "</th></tr>"
		);
		
		for (i=0;i<ajaxResponse.length;i++) {
			$("#tbodyForAllArticles").append(
				"<tr><td>"
				+ "<strong><a target='_blank' href='ui-ArticleDetail.php?id="+ajaxResponse[i].article_id+"'>#"+ajaxResponse[i].article_id+"</a></strong>"
				+ "</td>" + (ajaxResponse[i].authors != ' ' ? '<td>'+ajaxResponse[i].authors : "<td class='warning'><em>No authors listed</em>")
				+ "</td><td>" + (ajaxResponse[i].title != ' ' ? ajaxResponse[i].title : "<em>No title listed</em>")
				+ "</td><td>" + (ajaxResponse[i].journal_code != ' ' ? ajaxResponse[i].journal_code : "<em>No Journal listed</em>")
				+ "</td><td>" + (ajaxResponse[i].volume != ' ' ? ajaxResponse[i].volume : "<em>No volume listed</em>")
				+ "</td><td>" + (ajaxResponse[i].issue != ' ' ? ajaxResponse[i].issue : "<em>No issue listed</em>")
				+ "</td>" + (
					ajaxResponse[i].group_id == null
					? "<td class='myAlignCenter warning'><i class='warning fa fa-question-circle'></i>" : (
						ajaxResponse[i].group_id != 1
						? "<td class='myAlignCenter danger'><i class='danger fa fa-times-circle'></i>"
						: "<td class='myAlignCenter success'><i class='success fa fa-check-circle'></i>"
					)
				)
				+ "</td>" + (
					ajaxResponse[i].bwanalysis_synopsis == null
					? "<td class='myAlignCenter warning'><span class='warning fa fa-question-circle'></span>"
					: "<td class='myAlignCenter success'><span class='success fa fa-check-circle'></span>"
				)
				+ "</td>" + (
					ajaxResponse[i].group_id == NOT_RELEVANT_GROUP
					? "<td class='myAlignCenter info'><i class='warning fa fa-minus-circle'></i>" : (
						ajaxResponse[i].bwanalysis_empirical == null
						? "<td class='myAlignCenter warning'><span class='warning fa fa-question-circle'></span>" : (
							ajaxResponse[i].bwanalysis_empirical == 0	
							? "<td class='myAlignCenter danger'><i class='danger fa fa-times-circle'></i>"
							: "<td class='myAlignCenter success'><i class='success fa fa-check-circle'></i>"
						)
					)
				)
				+ "</td>" + (
					ajaxResponse[i].group_id == NOT_RELEVANT_GROUP
					? "<td class='myAlignCenter info'><i class='warning fa fa-minus-circle'></i>" : (
						ajaxResponse[i].bwanalysis_samplesize == null
						? "<td class='myAlignCenter warning'><i class='warning fa fa-question-circle'></i>"
						: "<td class='myAlignCenter success'>"+ajaxResponse[i].bwanalysis_samplesize
					)
				)
				+ "</td>" + (
					ajaxResponse[i].group_id == NOT_RELEVANT_GROUP
					? "<td class='myAlignCenter info'><i class='warning fa fa-minus-circle'></i>" : (
						ajaxResponse[i].bwanalysis_samplesource == null
						? "<td class='myAlignCenter warning'><i class='warning fa fa-question-circle'></i>"
						: "<td class='myAlignCenter success'>"+ajaxResponse[i].bwanalysis_samplesource
					)
				)
				+ "</td></tr>"
			);
		}	
	});
}
