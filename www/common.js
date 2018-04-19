function articleSourceType(isb8, abdcRank) {
	var journal_quality = null;
	var journal_quality_css = null;

	if (abdcRank != null) {
		switch (abdcRank) {
			case "1":
				journal_quality = "ABDC A* journal";
				journal_quality_css = "abcd1cell";
				break;
			case "2":
				journal_quality = "ABDC A journal";
				journal_quality_css = "abcd2cell";
				break;
			case "3":
				journal_quality = "ABDC B journal";
				journal_quality_css = "abcd3cell";
				break;
			case "4":
				journal_quality = "ABDC C journal";
				journal_quality_css = "abcd4cell";
				break;
			default:
				break;
		}
	}
	if (1 == isb8) {
		journal_quality = "bo8 journal";
		journal_quality_css = "bo8cell";
	}

	return {
		"journal_quality": journal_quality,
		"journal_quality_css": journal_quality_css
	};
}