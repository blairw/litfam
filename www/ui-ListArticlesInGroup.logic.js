function bodyDidLoad() {
	$.get("../api/db-GetArticlesByGroupId.php?id="+selectedGroup, function(ajaxResponse) {
		$("#h1Group").html(
			ajaxResponse.group.group_name
		);
		
		$("#pGroup").html(
			"<a href='ui-ListGroups.php'>"
				+'<i class="fa fa-level-up"></i>&nbsp;'
				+"All Groups</a>"
		);
		
		document.title = "[#"+ajaxResponse.group.group_id + "] " + ajaxResponse.group.group_name;

		for (i=0;i<ajaxResponse.articles.length;i++) {

			// article type
			// TODO: fix colours
			let jqResponse = articleSourceType(
				ajaxResponse.articles[i].is_basket_of_8,
				ajaxResponse.articles[i].abdc_rank
			);
			var journal_quality = jqResponse["journal_quality"];
			var journal_quality_css = jqResponse["journal_quality_css"];

			authorsPixString = "";
			authorsString = "";
			authorsFound = false;

			for (j = 0; j < ajaxResponse.articles[i].authors.length; j++) {
				authorsFound = true;
				let nextAuthorString = ajaxResponse.articles[i].authors[j].author_lname
				+ (
					ajaxResponse.articles[i].authors[j].author_fname
					? " " + ajaxResponse.articles[i].authors[j].author_fname.substring(0,1) + "."
					: ""
				)
				+ (
					ajaxResponse.articles[i].authors[j].author_minitials
					? ajaxResponse.articles[i].authors[j].author_minitials + "."
					: ""
				);
				authorsString += nextAuthorString;
				
				let authorId = ajaxResponse.articles[i].authors[j].author_id;
				authorsPixString += "<a href='ui-ListArticlesByAuthor.php?id=" + authorId + "'>"
					+ "<img class='litfam_author' src='../files/author_"
					+ authorId
					+ ".jpg' title=\"#" + authorId + "\" /></a>";

				if (j < ajaxResponse.articles[i].authors.length - 1) {
					authorsString += ", ";
				}
			}
			if (!authorsFound) {
				authorsString = "<em>(none listed)</em>";
			}

			$("#tbodyForArticles").append(
				"<tr>"
					+"<td>"+ajaxResponse.articles[i].article_id+"</td>"
					+ "<td>" + authorsString + "</td>"
					+ "<td>" + authorsPixString + "</td>"
					+ "<td"
						+(
							journal_quality != null
							? " class='" + journal_quality_css +"'>" + journal_quality 
							: (
								1 == ajaxResponse.articles[i].is_conference
								? " class='confcell'>conference proceedings"
								: " class='journalcell'>other"
							)
						)
						+"</td>"
					+"<td><strong><a href='ui-ArticleDetail.php?id="+ajaxResponse.articles[i].article_id+"'>"
						+ajaxResponse.articles[i].title
						+"</a></td>"
				+"</tr>"
			);
		}
	});
}
