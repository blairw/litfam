function bodyDidLoad() {
	// select2
	$("#selectAuthors").select2({
		placeholder: "New author"
	});
	
	getArticleDetails();
	getArticleBibRefs();
	generateAuthorsSelectBox();	
}

function getArticleDetails() {
	$.get("db-GetArticleById.php?id="+selectedArticleId, function(data) {
		$("#pForJournalDetails").html(
			"<a href='ui-ListJournals.php'>"
				+'<i class="fa fa-level-up"></i>&nbsp;'
				+"All Journals</a>"
				+"<br />"
				+"<a href='ui-ListJournalReleases.php?id="
				+data[0].journal_id+"'>"
				+'<i class="fa fa-level-up"></i>&nbsp;'
				+"All Releases of this Journal</a>"
				+"<br />"
				+"<a href='ui-ListArticlesInJournalRelease.php?id="
				+data[0].jr_id+"'>"
				+'<i class="fa fa-level-up"></i>&nbsp;'
				+"All Articles in this Release</a>"
		);
		
		// clear existing
		$("#divArticleName").html("");
		$("#divArticleDoi").html("<strong>DOI</strong>: ");
		$("#ulAuthors").html("");
		$("#divPanelBodyAbstract").html("");
		$("#ulListGroupReadThisArticle").html("");
		$("#ulListGroupAnalysisOverview").html("");
		$("#ulListGroupGroups").html("");
		
		// add from db
		document.title = '[#' + data[0].article_id + '] ' + data[0].title;
		$("#divArticleName").append(data[0].title);
		$("#divArticleDoi").append('<code>'+data[0].doi+'</code>');
		if (data[0].authors) {
			for (var i = 0; i < data[0].authors.length; i++) {
				$("#ulAuthors").append(
					'<li>'
					+data[0].authors[i].author_lname
					+(data[0].authors[i].author_fname ? ', '+data[0].authors[i].author_fname : '')
					+(data[0].authors[i].author_mname ? ' '+data[0].authors[i].author_mname : '')
					+(data[0].authors[i].university ? ' <small>(' : '')
					+(data[0].authors[i].department ? data[0].authors[i].department +', ' : '')
					+(data[0].authors[i].university ? data[0].authors[i].university : '')
					+(data[0].authors[i].university ? ')</small>' : '')
					+'</li>');
			}
		}
		$("#divPanelBodyAbstract").append(data[0].abstract);
		$("#ulListGroupReadThisArticle").append(
			"<li class='list-group-item'><a target='_blank' href='../3971thesis-files/"
			+data[0].article_id
			+".pdf'>"
			+"Local File"
			+"</a></li>"
		);
		if (data[0].url) {
			$("#ulListGroupReadThisArticle").append(
				"<li class='list-group-item'><a target='_blank' href='"
				+data[0].url
				+"'>"
				+data[0].url
				+"</a></li>"
			);
		}
		$("#ulListGroupAnalysisOverview").append(
			"<li class='list-group-item'><strong>Context + Scope of Application:</strong> "
			+(null == data[0].bwanalysis_synopsis ? '<em>No details written.</em>' : data[0].bwanalysis_synopsis)
			+"</li>"
		);
		$("#ulListGroupAnalysisOverview").append(
			"<li class='list-group-item'><strong>Is Empirical?</strong> "
			+(null == data[0].bwanalysis_empirical ? '<em>Not classified.</em>' : (
				data[0].bwanalysis_empirical == 0
				? "No.&nbsp;<i class='fa fa-times'></i>"
				: "Yes.&nbsp;<i class='fa fa-check'></i>"
			))
			+"</li>"
		);
		groupsFound = false;
		for (i = 0; i < data[0].groups.length; i++) {
			$("#ulListGroupGroups").append(
				'<li class="list-group-item">'+data[0].groups[i].group_name+'</li>'
			);
			groupsFound = true;
		}
		if (!groupsFound) {
			$("#ulListGroupGroups").append(
				'<li class="list-group-item"><em>No groups.</em></li>'
			);
		}
		
		if (data[0].doi) {
			getDoiDetails(data[0].doi);
		}
	});
}

function generateAuthorsSelectBox() {
	$.get("db-GetAllAuthors.php", function(data) {
		// clear existing
		$("#selectAuthors").html("<option></option>");
		
		// add from db
		for (var i = 0; i < data.length; i++) {
			$("#selectAuthors").append(
				'<option value="' + data[i].author_id + '">'
				+data[i].author_lname
				+(data[i].author_fname ? ', '+data[i].author_fname : '')
				+(data[i].author_mname ? ' '+data[i].author_mname : '')
				+ '</option>'
			);
		}
	});
}

function getDoiDetails(doi) {
	$.get("http://api.crossref.org/works/"+doi, function(data) {
		$("#divDoiDetailsPanelBody").html("");
		console.log(data);
		if (data.message.author) {
			$("#divDoiDetailsPanelBody").append('<ul id="ulDoiAuthors"></ul>');
			for (var i = 0; i < data.message.author.length; i++) {
				$("#ulDoiAuthors").append(
					"<li>"
					+ "<strong>Family</strong>: "+data.message.author[i].family+"; "
					+ "<strong>Given</strong>: "+data.message.author[i].given+""
					+ "</li>"
				);
			}
		}
		
		$("#divDoiDetailsPanelFooter").append('<a target="_blank" href="'+data.message.URL+'">'+data.message.URL+'</a>');
	});
}

function submitNewAuthorship() {
	$.post("db-SetAuthorshipToArticle.php", {
		articleId: selectedArticleId,
		'authorIds[]': $("#selectAuthors").val()
	}).done(
		function(data) {
			console.log(data);
			getArticleDetails();
			generateAuthorsSelectBox();
		}
	);
}



function getArticleBibRefs() {
	$.get("db-GetBibliographyEntries.php", function(arrayOfBibRefs) {
		for (var i = 0; i < arrayOfBibRefs.length; i++) {
			if (arrayOfBibRefs[i].article_id == selectedArticleId) {
				// clear existing
				$("#pArticleSubtitle").html("");
				
				// add from db
				$("#pArticleSubtitle").append(arrayOfBibRefs[i].html_citation);
			}
		}
		
		getCitationsBackAndForth(arrayOfBibRefs);
	});
}

function getCitationsBackAndForth(arrayOfBibRefs) {
	$.get("db-GetCitationsById.php?id="+selectedArticleId, function(data) {
		$("#ulListGroupCitations").html("");
		
		if (data.citedBy.length > 0) {
			for (var i = 0; i < data.citedBy.length; i++) {
				$("#citationsDownstream").append(
					"<li class='list-group-item'><strong>Cited by:</strong> "
					+"<a href='ui-ArticleDetail.php?id="+data.citedBy[i]+"'>[#"
					+data.citedBy[i]
					+"]</a> "
					+makeBibRefFromId(arrayOfBibRefs,data.citedBy[i])
					+"</li>"
				);
			}
		} else {
			$("#citationsDownstream").append(
				"<li class='list-group-item list-group-item-warning'><em>"
				+"<i class='fa fa-times-circle'></i>&nbsp;&nbsp;"
				+"None found."
				+"</em></li>"
			);
		}
		
		if (data.citesOthers.length > 0) {
			for (var i = 0; i < data.citesOthers.length; i++) {
				$("#citationsUpstream").append(
					"<li class='list-group-item'><strong>Cites:</strong> "
					+"<a href='ui-ArticleDetail.php?id="+data.citesOthers[i]+"'>[#"
					+data.citesOthers[i]
					+"]</a> "
					+makeBibRefFromId(arrayOfBibRefs,data.citesOthers[i])
					+"</li>"
				);
			}
		} else {
			$("#citationsUpstream").append(
				"<li class='list-group-item list-group-item-warning'><em>"
				+"<i class='fa fa-times-circle'></i>&nbsp;&nbsp;"
				+"None found."
				+"</em></li>"
			);
		}
	});
}

function makeBibRefFromId(arrayOfBibRefs, id) {
	var returnItem = '';
	for (var i = 0; i < arrayOfBibRefs.length; i++) {
		if (arrayOfBibRefs[i].article_id == id) {
			returnItem = arrayOfBibRefs[i].html_citation;
		}
	}
	
	return returnItem;
}
