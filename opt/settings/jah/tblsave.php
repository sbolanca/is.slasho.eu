<?
$table=trim(simGetParam($_REQUEST,'table',''));

	
	$f=new simSettings($database);
	$f->userID=$myID;
	$f->type=$table."_fields";
	$f->value=$_SESSION[$table."_fields"];
	$f->store();
	
	$w=new simSettings($database);
	$w->userID=$myID;
	$w->type=$table."_widths";
	$w->value=$_SESSION[$table."_widths"];
	$w->store();

	
	$res->alert("Postavke tablice su spremljene u vaše osobne podatke.");	


	
?>
