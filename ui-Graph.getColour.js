function getColour(ajaxResponseItem, context) {
	returnObject = "rgb(100,100,100)";
	
	if (context == 'up') {
		thisUpB8 = ajaxResponseItem.up_b8;
		thisConf = ajaxResponseItem.up_conf;
		thisYear = ajaxResponseItem.up_year;
	} else if (context == 'down') {
		thisUpB8 = ajaxResponseItem.down_b8;
		thisConf = ajaxResponseItem.down_conf;
		thisYear = ajaxResponseItem.down_year;
	}
	
	if (thisUpB8 == 1) {
		if (thisYear > 2009) {
			returnObject = "rgba(78,154,255,0.5)";
		} else {
			returnObject = "rgba(80,86,147,0.5)";
		}
	} else if (thisUpB8 == 0) {
		if (thisConf == 1) {
			if (thisYear > 2009) {
				returnObject = "rgba(165,228,51,0.5)";
			} else {
				returnObject = "rgba(94,178,49,0.5)";
			}
		} else if (thisConf == 0) {
			if (thisYear > 2009) {
				returnObject = "rgba(255,223,0,0.5)";
			} else {
				returnObject = "rgba(229,159,0,0.5)";
			}
		}
	}

	return returnObject;
}
