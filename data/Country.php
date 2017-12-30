<?php



function getCountryList($dbh) {
	$sql = 'SELECT ABBR, FullName FROM Country ORDER BY FullName';
	return $dbh->query($sql);
}

?>