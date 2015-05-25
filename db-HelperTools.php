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

// http://stackoverflow.com/questions/2541616/how-to-escape-strip-special-characters-in-the-latex-document
function latexSpecialChars( $string )
{
    $map = array( 
            "#"=>"\\#",
            "$"=>"\\$",
            "%"=>"\\%",
            "&"=>"\\&",
            "~"=>"\\~{}",
            "_"=>"\\_",
            "^"=>"\\^{}",
            "\\"=>"\\textbackslash",
            "{"=>"\\{",
            "}"=>"\\}",
    );
    return str_replace(
		'Ö',
		'\"{O}',
		str_replace(
			"ß",
			"{\ss}",
			preg_replace("/([\^\%~\\\\#\$%&_\{\}])/e", "\$map['$1']",$string)
		)
	);
}
?>
