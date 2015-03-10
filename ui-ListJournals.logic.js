var minReleases;
var maxReleases;
var minArticles;
var maxArticles;

function shadeBySeverity(min, max, thisOne) {
	var percentageSeverity = Math.pow((thisOne-min)/(max-min),1);
	return "rgba(255,100,100,"+(1-percentageSeverity)+")";
}

function bodyDidLoad() {
	$.get("db-getAllJournals.php", function(ajaxResponse) {
		minReleases = ajaxResponse[0].count_jr;
		maxReleases = ajaxResponse[0].count_jr;
		minArticles = ajaxResponse[0].count_article;
		maxArticles = ajaxResponse[0].count_article;
		for (i=0;i<ajaxResponse.length;i++) {
			if (ajaxResponse[i].count_jr < minReleases) minReleases = ajaxResponse[i].count_jr;
			if (ajaxResponse[i].count_jr > maxReleases) maxReleases = ajaxResponse[i].count_jr;
			if (ajaxResponse[i].count_article < minArticles) minArticles = ajaxResponse[i].count_article;
			if (ajaxResponse[i].count_article > maxArticles) maxArticles = ajaxResponse[i].count_article;
		}
		
		
		for (i=0;i<ajaxResponse.length;i++) {
			$("#tbodyForJournalsTable").append(
				"<tr>"
					+ "<td>"+ajaxResponse[i].journal_id+"</td>"
					+ "<td><code>"+ajaxResponse[i].journal_code+"</code></td>"
					+ "<td><strong><a href='ui-ListJournalReleases.php?id="+ajaxResponse[i].journal_id+"'>"+ajaxResponse[i].journal_name+"</a></strong></td>"
					+ "<td"
						+(
							1 == ajaxResponse[i].is_basket_of_8
							? " class='bo8cell'>bo8 journal"
							: (
								1 == ajaxResponse[i].is_conference
								? " class='confcell'>conference proceedings"
								: " class='journalcell'>other"
							)
						)
						+"</td>"
					+ "<td style='background-color: "+shadeBySeverity(minReleases, maxReleases, ajaxResponse[i].count_jr)+";'>"
						+ajaxResponse[i].count_jr
						+"</td>"
					+ "<td style='background-color: "+shadeBySeverity(minArticles, maxArticles, ajaxResponse[i].count_article)+";'>"
						+ajaxResponse[i].count_article
						+"</td>"
					+ "<td>"
						+(
							null == ajaxResponse[i].url
							? "<em>No link.</em>"
							: "<a target='_blank' href='"+ajaxResponse[i].url+"'>Link"+'&nbsp;<i class="fa fa-external-link"></i></a>'
						)
						+"</td>"
				+ "</tr>"
			);
		}
	});
}
