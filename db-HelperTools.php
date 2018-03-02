<?php

date_default_timezone_set('Australia/Sydney');
mysqli_set_charset($mysqli,"utf8");


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


set_error_handler('exceptions_error_handler');

function exceptions_error_handler($severity, $message, $filename, $lineno) {
  if (error_reporting() == 0) {
    return;
  }
  if (error_reporting() & $severity) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
}

function utf8ize($d) {
    if (is_array($d) || is_object($d)) {
        foreach ($d as &$v) $v = utf8ize($v);
    } else {
        $enc   = mb_detect_encoding($d);
		$value = iconv($enc, 'UTF-8', $d);
        return $value;
    }

    return $d;
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
