function bodyDidLoad() {
	$.get("../api/db-GetBibliographyEntries.php", function(ajaxBibliography) {
		$.get("../api/db-GetArticlesByGroupId.php?id="+selectedGroup, function(ajaxGroupArticles) {
			for (i=0; i<ajaxGroupArticles.articles.length;i++) {
				for (j=0;j<ajaxBibliography.length;j++) {
					if (ajaxGroupArticles.articles[i].article_id == ajaxBibliography[j].article_id) {
						$("#documentBody").append(
							'<p>'
							+ ajaxBibliography[j].html_citation
							+'</p>'
						);
					}
				}
			}
		});
	});
}
