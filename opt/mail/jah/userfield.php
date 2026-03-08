<?



$form=trim(simGetParam($_REQUEST,'form',''));
$fld=trim(simGetParam($_REQUEST,'fld',''));
$pos=intval(simGetParam($_REQUEST,'pos',0));

$fields=array('id','mbg','oib','ipn','ime','prezime','name','username','password','spol','email','mobitel','telefon','datum_rodjenja','drzava_rodjenja','mjesto_rodjenja','drzavljanstvo','datum_uclanjenja','drzava_boravka','mjesto_boravka','posta_boravka','adresa_boravka','drzava_za_postu','mjesto_za_postu','posta_za_postu','adresa_za_postu','vrsta','naziv_banke','iban');
	$tmpl->readTemplatesFromInput( "opt/mail/jah/userfields.html");

	$tmpl->addVar("opt_mail_u", 'form',$form);
	$tmpl->addVar("opt_mail_u", 'pos',$pos);
	$tmpl->addVar("opt_mail_u", 'fld',$fld);
	$tmpl->addVar("opt_mail_u", 'field',$fields);
	
	
	$cont= $tmpl->getParsedTemplate("opt_mail");
	
	
	$res->openSimpleDialog("Dodaj naziv korisničkog podatka u $fld maila",$cont,500,5,'blue-full');

?>