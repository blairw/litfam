function bodyDidLoad() {
	$.get("../api/db-GetAuthorsByTeam.php?id="+selectedTeam, function(ajaxResponse) {
		$("#pGroup").html(
			ajaxResponse[0].name
		);
		$("#h1Group").html(
			ajaxResponse[0].name
		);
		
		for (i=0;i<ajaxResponse.length;i++) {
			$("#tbodyForArticles").append(
				"<tr>"
					+"<td>"+ajaxResponse[i].author_id+"</td>"
					+ "<td><a href='ui-ListArticlesByAuthor.php?id=" + ajaxResponse[i].author_id + "'>"
							+ "<img class='litfam_author' src='../files/author_"
							+ ajaxResponse[i].author_id
							+ ".jpg' title=\"#" + ajaxResponse[i].author_id + "\" /></a></td>"
					+"<td><strong><a href='ui-ListArticlesByAuthor.php?id="+ajaxResponse[i].author_id+"'>"
						+ ajaxResponse[i].author_fname + " "
						+ (ajaxResponse[i].author_mname ? ajaxResponse[i].author_mname + " " : "")
						+ ajaxResponse[i].author_lname
						+"</a></td>"
					+"<td>"+ajaxResponse[i].role+"</td>"
				+"</tr>"
			);
		}
	});
}
