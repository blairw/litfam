var NOT_RELEVANT_GROUP = 5;

function bodyDidLoad() {
	refreshAllArticlesList();
}

function refreshAllArticlesList() {
	$.get("../api/db-GetAllBwAnalysisStatus.php", function(ajaxResponse) {
		
		
		$("#tbodyForAllArticles").html(
			"<tr><th>ID"
			+ "</th><th>Authors"
			+ "</th><th>Title"
			+ "</th><th>Journal"
			+ "</th><th>Volume"
			+ "</th><th>Issue"
			+ "</th><th>Literature Coding?"
			+ "</th><th>Synopsis?"
			+ "</th><th>Empirical?"
			+ "</th><th>Sample Size"
			+ "</th><th>Sample Source"
			+ "</th>"
			+ "<th>PDF</th>"
			+ "</tr>"
		);
		
		for (i=0;i<ajaxResponse.length;i++) {
			$("#tbodyForAllArticles").append(
				"<tr id='trForArticleId"+ajaxResponse[i].article_id+"'><td>"
				+ ajaxResponse[i].article_id
				+ "</td>" + (ajaxResponse[i].authors != ' ' ? '<td>'+ajaxResponse[i].authors : "<td class='warning'><em>No authors listed</em>")
				+ "</td><td>" 
					+ "<strong><a target='_blank' href='ui-ArticleDetail.php?id="+ajaxResponse[i].article_id+"'>"
					+ (ajaxResponse[i].title != ' ' ? ajaxResponse[i].title : "<em>No title listed</em>")
					+ "</a></strong>"
				+ "</td><td>" + (ajaxResponse[i].journal_code != ' ' ? ajaxResponse[i].journal_code : "<em>No Journal listed</em>")
				+ "</td><td>" + (ajaxResponse[i].volume != ' ' ? ajaxResponse[i].volume : "<em>No volume listed</em>")
				+ "</td><td>" + (ajaxResponse[i].issue != ' ' ? ajaxResponse[i].issue : "<em>No issue listed</em>")
				+ "</td>" + (
					ajaxResponse[i].group_id != 5 && ajaxResponse[i].group_id != 1
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
				+ "</td>"
				+ "<td>" + "<strong><a target='_blank' href='../files/"+ajaxResponse[i].article_id+".pdf'>PDF"+"</a></strong>" + "</td>"
				+ "<td>" + "<button class='btn btn-success' onclick='addItemToGroup(1,"+ajaxResponse[i].article_id+")'>Accept"+"</button>" + "</td>"
				+ "<td>" + "<button class='btn btn-danger'  onclick='addItemToGroup(5,"+ajaxResponse[i].article_id+")'>Reject"+"</button>" + "</td>"
				+ "</tr>"
			);
		}	
	});
}

function addItemToGroup(thisGroupId, thisArticleId) {
	$("#trForArticleId"+thisArticleId).remove();
	$.post("../api/db-SetMembershipToArticle.php", {
		articleId: thisArticleId,
		groupId: thisGroupId
	}).done(
		function(data) {
			console.log(data);
			refreshAllArticlesList();
		}
	);
}
