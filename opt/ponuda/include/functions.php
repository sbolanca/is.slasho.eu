<?php
function getPonudaStatusTitle($status) {
	switch($status) {
		case 1: $title='KREIRANO'; break;
		case 2: $title='OBRAĐENO'; break;
		case 3: $title='ARHIVIRANO'; break;
		case 4: $title='PROBLEM'; break;
		case 0: $title='-bez statusa-'; break;
		default: $title='';
	}
	return $title;
}
function getPonudaStatusCls($s) {
	switch($s) {
		case 1: $cls='own'; break;
		case 2: $cls='my'; break;
		case 3: $cls='mysukob'; break;
		case 4: $cls='sukob'; break;
		default: $cls=''; 
	}
	return $cls;
}

?>