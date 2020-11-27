<?php
date_default_timezone_set('UTC');
function formatDate($t)
{
	// $tt = time();
	$parsedt = date('j M Y',$t);
	// $parsednow = date('j F, Y',$tt);
	// $parsedyes = date('j F, Y',$tt-24*60*60);
	// if($parsedt==$parsednow) return "Today";
	// if($parsedt==$parsedyes) return "Yesterday";
	return $parsedt;
}

function formatDateTime($t)
{
	#return date('H:i',$t).", ".formatDate($t);
	return formatDate($t)." à ".date('H:i',$t);
}
?>
