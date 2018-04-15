var minYear;
var maxYear;
var minArticles;
var maxArticles;

function shadeBySeverity(min, max, thisOne, myPower, myInvert) {
	var percentageSeverity = Math.pow((thisOne-min)/(max-min),myPower);
	if (myInvert == "invert") {
		percentageSeverity = 1 - percentageSeverity;
	}
	return "rgba(255,100,100,"+(percentageSeverity)+")";
}

function monthToString(monthInt) {
	var monthNames = ["", "January", "February", "March", "April", "May", 
	"June", "July", "August", "September", "October", "November", 
	"December"];
	
	return monthNames[monthInt];
}

function bodyDidLoad() {
	$.get("../api/db-GetJrByJournalId.php?id="+selectedJournalId, function(ajaxResponse) {
		minYear     = parseInt(ajaxResponse[0].pub_year);
		maxYear     = parseInt(ajaxResponse[0].pub_year);
		minArticles = parseInt(ajaxResponse[0].count_article);
		maxArticles = parseInt(ajaxResponse[0].count_article);
		for (i=0;i<ajaxResponse.length;i++){
			if (parseInt(ajaxResponse[i].pub_year)      < minYear)     minYear     = parseInt(ajaxResponse[i].pub_year);
			if (parseInt(ajaxResponse[i].pub_year)      > maxYear)     maxYear     = parseInt(ajaxResponse[i].pub_year);
			if (parseInt(ajaxResponse[i].count_article) < minArticles) minArticles = parseInt(ajaxResponse[i].count_article);
			if (parseInt(ajaxResponse[i].count_article) > maxArticles) maxArticles = parseInt(ajaxResponse[i].count_article);
		}
		
		$("#h1ForJournalName").html(ajaxResponse[0].journal_name);
		$("#pForJournalDetails").html(
			"<a href='ui-ListJournals.php'>"
				+'<i class="fa fa-level-up"></i>&nbsp;'
				+"All Journals</a>"
		);
		document.title = ajaxResponse[0].journal_code;
		
		for (i=0;i<ajaxResponse.length;i++) {
			$("#tbodyForJournalReleasesTable").append(
				"<tr>"
					+"<td>"+ajaxResponse[i].jr_id+"</td>"
					+"<td style='background-color: "+shadeBySeverity(minYear, maxYear, ajaxResponse[i].pub_year, 5, 'no-invert')+";'>"
						+ajaxResponse[i].pub_year
						+"</td>"
					+"<td>"+monthToString(ajaxResponse[i].pub_month)+"</td>"
					+"<td><strong><a href='ui-ListArticlesInJournalRelease.php?id="+ajaxResponse[i].jr_id+"'>"
						+"vol. "+ajaxResponse[i].volume
						+(ajaxResponse[i].issue ? ", no. "+ajaxResponse[i].issue : "")
						+(ajaxResponse[i].part ? ", part "+ajaxResponse[i].part : "")
						+"</a></strong></td>"
					+"<td style='background-color: "+shadeBySeverity(minArticles, maxArticles, ajaxResponse[i].count_article, 2, 'invert')+";'>"
						+ajaxResponse[i].count_article
						+"</td>"
					+ "<td>"
						+(
							null == ajaxResponse[i].url
							? "<em>No link.</em>"
							: "<a target='_blank' href='"+ajaxResponse[i].url+"'>Link"+'&nbsp;<i class="fa fa-external-link"></i></a>'
						)
						+"</td>"
				+"</tr>"
			);
		}
	});
}
