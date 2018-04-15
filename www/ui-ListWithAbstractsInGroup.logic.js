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

			$("#output").append(
				"<h3><strong>"
					+"[#"+ajaxResponse.articles[i].article_id+"] "
					+"<a href='http://localhost/litfam-files/"+ajaxResponse.articles[i].article_id+".pdf'>"
						+ajaxResponse.articles[i].title
						+"</a>"
				+"</strong></h3>"
				+"<p><strong>Synposis: "
				+ajaxResponse.articles[i].bwanalysis_synopsis
				+"</p></strong>"
				+"<p><strong>Abstract: </strong>"
				+ajaxResponse.articles[i].abstract
				+"</p>"
			);
		}
	});
}
