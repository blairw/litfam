function bodyDidLoad() {
	$.get("db-GetArticlesByJrId.php?id="+selectedJrId, function(ajaxResponse) {
		$("#h1ForJrDetails").html(
			ajaxResponse[0].journal_code
			+ (ajaxResponse[0].volume ? ", vol. "+ajaxResponse[0].volume : "")
			+ (ajaxResponse[0].issue ? ", no. "+ajaxResponse[0].issue : "")
			+ (ajaxResponse[0].part ? ", part "+ajaxResponse[0].part : "")
		);
		$("#pForJrDetails").html(
			"<a href='ui-ListJournals.php'>"
				+'<i class="fa fa-level-up"></i>&nbsp;'
				+"All Journals</a>"
				+"<br />"
				+"<a href='ui-ListJournalReleases.php?id="
				+ajaxResponse[0].journal_id+"'>"
				+'<i class="fa fa-level-up"></i>&nbsp;'
				+"All Releases of this Journal</a>"
		);
		
		for (i=0;i<ajaxResponse.length;i++) {
			$("#tbodyForArticles").append(
				"<tr>"
					+"<td>"+ajaxResponse[i].article_id+"</td>"
					+"<td><strong><a href='ui-ArticleDetail.php?id="+ajaxResponse[i].article_id+"'>"
						+ajaxResponse[i].title
						+"</a></td>"
					+"<td>"+ajaxResponse[i].pg_begin+"-"+ajaxResponse[i].pg_end+"</td>"
				+"</tr>"
			);
		}
	});
}
