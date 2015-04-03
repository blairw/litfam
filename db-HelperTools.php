<?php

function getOrdinalFromNumber($inputNumber) {
	if ($inputNumber % 10 == 3) {
		return $inputNumber."rd";
	} else if ($inputNumber % 10 == 2) {
		return $inputNumber."nd";
	} else if ($inputNumber % 10 == 1) {
		return $inputNumber."st";
	} else {
		return $inputNumber."th";
	}	
}

?>
