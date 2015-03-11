var minReleases;
var maxReleases;
var minArticles;
var maxArticles;

function shadeBySeverity(min, max, thisOne) {
	var percentageSeverity = Math.pow(((thisOne-min)/(max-min)),0.5);
	return "rgba(255,100,100,"+(percentageSeverity)+")";
}

function bodyDidLoad() {
	$.get("db-getAllJournals.php", function(ajaxResponse) {
		minReleases = parseInt(ajaxResponse[0].count_jr);
		maxReleases = parseInt(ajaxResponse[0].count_jr);
		minArticles = parseInt(ajaxResponse[0].count_article);
		maxArticles = parseInt(ajaxResponse[0].count_article);
		for (i=0;i<ajaxResponse.length;i++) {				
			console.log("minReleases "+minReleases);
			console.log("maxReleases "+maxReleases);
			console.log("minArticles "+minArticles);
			console.log("maxArticles "+maxArticles);

			if (parseInt(ajaxResponse[i].count_jr) < minReleases)      minReleases = parseInt(ajaxResponse[i].count_jr);
			if (parseInt(ajaxResponse[i].count_jr) > maxReleases)      maxReleases = parseInt(ajaxResponse[i].count_jr);
			if (parseInt(ajaxResponse[i].count_article) < minArticles) minArticles = parseInt(ajaxResponse[i].count_article);
			if (parseInt(ajaxResponse[i].count_article) > maxArticles) maxArticles = parseInt(ajaxResponse[i].count_article);
			
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
