var NOT_RELEVANT_VALUE = 5;

function bodyDidLoad() {
	console.log("bodyDidLoad");
	// selects
	$("#selectGroups").select2({
		placeholder: "Add to group"
	});
	$("#selectAuthors").select2({
		placeholder: "New author"
	});
	
	
	getArticleDetails();
	getArticleBibRefs();
	generateAuthorsSelectBox();	
	console.log("PREPARE: generateGroupsSelectBox");
	generateGroupsSelectBox();	
}

function getArticleDetails() {
	$.get("../api/db-GetArticleById.php?id="+selectedArticleId, function(data) {
		$("#pForJournalDetails").html(
			"<a href='ui-ListJournals.php'>"
				+'<i class="fa fa-level-up"></i>&nbsp;'
				+"All Journals</a>"
				+"<br />"
				+"<a href='ui-ListJournalReleases.php?id="
				+data.articleDetails.journal_id+"'>"
				+'<i class="fa fa-level-up"></i>&nbsp;'
				+"All Releases of this Journal</a>"
				+"<br />"
				+"<a href='ui-ListArticlesInJournalRelease.php?id="
				+data.articleDetails.jr_id+"'>"
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
		document.title = '[#' + data.articleDetails.article_id + '] ' + data.articleDetails.title;
		$("#divArticleName").append(data.articleDetails.title);
		$("#divArticleDoi").append('<code>'+data.articleDetails.doi+'</code>');
		for (var i = 0; i < data.authors.length; i++) {
			$("#ulAuthors").append(
				'<li><a href="ui-ListArticlesByAuthor.php?id=' + data.authors[i].author_id + '">'
				+data.authors[i].author_lname
				+(data.authors[i].author_fname ? ', '+data.authors[i].author_fname : '')
				+(data.authors[i].author_mname ? ' '+data.authors[i].author_mname : '')
				+(data.authors[i].university ? ' <small>(' : '')
				+(data.authors[i].department ? data.authors[i].department +', ' : '')
				+(data.authors[i].university ? data.authors[i].university : '')
				+(data.authors[i].university ? ')</small>' : '')
				+'</a></li>');
		}
		$("#divPanelBodyAbstract").append(data.articleDetails.abstract);
		$("#ulListGroupReadThisArticle").append(
			"<li class='list-group-item'><a target='_blank' href='../litfam-files/"
			+data.articleDetails.article_id
			+".pdf'>"
			+"Local File"
			+"</a></li>"
		);
		if (data.articleDetails.url) {
			$("#ulListGroupReadThisArticle").append(
				"<li class='list-group-item'><a target='_blank' href='"
				+data.articleDetails.url
				+"'>"
				+data.articleDetails.url
				+"</a></li>"
			);
		}
		$("#ulListGroupAnalysisOverview").append(
			"<li class='list-group-item'><strong>Context + Scope of Application:</strong> "
			+(null == data.articleDetails.bwanalysis_synopsis ? '<em>No details written.</em>' : data.articleDetails.bwanalysis_synopsis)
			+"</li>"
		);
		$("#ulListGroupAnalysisOverview").append(
			"<li class='list-group-item'><strong>Is Empirical?</strong> "
			+(null == data.articleDetails.bwanalysis_empirical ? '<em>Not classified.</em>' : (
				data.articleDetails.bwanalysis_empirical == 0
				? "No.&nbsp;<i class='fa fa-times'></i>"
				: "Yes.&nbsp;<i class='fa fa-check'></i>"
			))
			+"</li>"
		);
		groupsFound = false;
		for (i = 0; i < data.groups.length; i++) {
			prepareClassName = "list-group-item";
			if (data.groups[i].group_id == 1) {
				prepareClassName += " list-group-item-success";
			} else if (data.groups[i].group_id == NOT_RELEVANT_VALUE) {
				prepareClassName += " list-group-item-danger";
			}
			$("#ulListGroupGroups").append(
				'<li class="'+prepareClassName+'">'
				+'<a target="_blank" href="ui-ListArticlesInGroup.php?id='+data.groups[i].group_id+'">'
				+data.groups[i].group_name
				+'</a></li>'
			);
			groupsFound = true;
		}
		if (!groupsFound) {
			$("#ulListGroupGroups").append(
				'<li class="list-group-item"><em>No groups.</em></li>'
			);
		}
		
		// add Theories
		theoriesFound = false;
		for (i = 0; i < data.theories.length; i++) {
			$("#ulListGroupTheories").append(
				'<li class="list-group-item">'
				+'<strong>'+data.theories[i].theory_name+'</strong>'
				+'<p>'+data.theories[i].theory_details+'</p>'
				+(data.theories[i].how_is_used ? '<p>'+data.theories[i].how_is_used+'</p>' : '')
				+'</li>'
			);
			theoriesFound = true;
		}
		if (!theoriesFound) {
			$("#ulListGroupTheories").append(
				'<li class="list-group-item"><em>No theories.</em></li>'
			);
		}
		
		if (data.articleDetails.doi) {
			getDoiDetails(data.articleDetails.doi);
		}
	});
}

function generateAuthorsSelectBox() {
	$.get("../api/db-GetAllAuthors.php", function(data) {
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

function generateGroupsSelectBox() {
	console.log("generateGroupsSelectBox");
	$.get("../api/db-GetAllGroups.php", function(data) {
		// clear existing
		$("#selectGroups").html("<option></option>");
		
		// add from db
		for (var i = 0; i < data.length; i++) {
			$("#selectGroups").append(
				'<option value="' + data[i].group_id + '">'
				+ data[i].group_name
				+ '</option>'
			);
		}
	});
}

function getDoiDetails(doi) {
	$.get("../api/http://api.crossref.org/works/"+doi, function(data) {
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

function submitNewMembership() {
	let groups = $("#selectGroups").val()
	for (var i = 0; i < groups.length; i++) {
		let thisGroup = groups[i];
		addItemToGroup(thisGroup, selectedArticleId);
	}
}



function getArticleBibRefs() {
	$.get("../api/db-GetBibliographyEntries.php", function(arrayOfBibRefs) {
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
	$.get("../api/db-GetCitationsById.php?id="+selectedArticleId, function(data) {
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



function addItemToGroup(thisGroupId, thisArticleId) {
	$("#trForArticleId"+thisArticleId).remove();
	$.post("db-SetMembershipToArticle.php", {
		articleId: thisArticleId,
		groupId: thisGroupId
	}).done(
		function(data) {
			console.log(data);
			getArticleDetails();
			generateGroupsSelectBox();
		}
	);
}
