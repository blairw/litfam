function bodyDidLoad() {
	$.get("../api/db-GetAllGroups.php", function(ajaxResponse) {
		for (i=0;i<ajaxResponse.length;i++) {
			$("#tbodyForGroups").append(
				"<tr>"
					+ "<td>"+ajaxResponse[i].group_id+"</td>"
					+ "<td><strong><a href='ui-ListArticlesInGroup.php?id="+ajaxResponse[i].group_id+"'>"+ajaxResponse[i].group_name+"</a></strong></td>"
				+ "</tr>"
			);
		}
	});
}
