function bodyDidLoad() {
	$.get("../api/db-GetArticlesByAuthor.php?id="+selectedAuthor, function(ajaxResponse) {
		let fullName = ajaxResponse.author.author_lname + ", " + ajaxResponse.author.author_fname;
		
		// add image
		let image = "<img class='litfam_author pull-right litfam_author_for_profile' src='../litfam-files/author_"
		+ ajaxResponse.author.author_id
		+ ".jpg' title=\"" + fullName + "\" />";
		
		$("#h1Group").html(fullName + image);
		
		$("#pGroup").html(
			"<a href='ui-ListGroups.php'>"
				+'<i class="fa fa-level-up"></i>&nbsp;'
				+"All Groups</a>"
			+ "<br /><strong>Author ID:</strong> " + ajaxResponse.author.author_id
			+ "<br /><strong>Author University:</strong> " + ajaxResponse.author.university
			+ "<br /><strong>Author Department:</strong> " + ajaxResponse.author.department
			+ "<br /><strong>URL:</strong> <a href=\"" + ajaxResponse.author.url + "\">" + ajaxResponse.author.url + "</a>"
			+ "<br />"
			+ "<br />"
			+ "<br />"
		);
		
		document.title = "[#"+ajaxResponse.author.author_id + "] " + fullName;
		
		for (i=0;i<ajaxResponse.articles.length;i++) {
			authorsString = "";
			authorsFound = false;
			for (j = 0; j < ajaxResponse.articles[i].authors.length; j++) {
				authorsFound = true;
				authorsString += 
					ajaxResponse.articles[i].authors[j].author_lname
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
					+"<td>"+ajaxResponse.articles[i].year+"</td>"
					+ "<td>" + authorsString + "</td>"
					+"<td><strong><a href='ui-ArticleDetail.php?id="+ajaxResponse.articles[i].article_id+"'>"
						+ajaxResponse.articles[i].title
						+"</a></td>"
				+"</tr>"
			);
		}
	});
}
