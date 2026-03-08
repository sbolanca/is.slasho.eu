<?

$SETTINGS['tbl_kategorija_fields_all'] = 'id,naziv,cnt,cntu';
$SETTINGS['tbl_kategorija_header'] = 'ID,Naziv,#modela,#uredjaja';
$SETTINGS['tbl_kategorija_widths'] = '50,300,100,100';
$SETTINGS['tbl_kategorija_aligns'] = 'right,left,center,center';
$SETTINGS['tbl_kategorija_types'] = 'ro,ro,ro,ro';
$SETTINGS['tbl_kategorija_fields'] = 'id,naziv,cnt,cntu';

$SETTINGS['tbl_kategorijastavke_fields_all'] = 'id,naziv,cnt';
$SETTINGS['tbl_kategorijastavke_header'] = 'ID,Naziv,#stavki';
$SETTINGS['tbl_kategorijastavke_widths'] = '50,400,100';
$SETTINGS['tbl_kategorijastavke_aligns'] = 'right,left,center';
$SETTINGS['tbl_kategorijastavke_types'] = 'ro,ro,ro';
$SETTINGS['tbl_kategorijastavke_fields'] = 'id,naziv,cnt';

$SETTINGS['tbl_kategorijaslike_fields_all'] = 'id,naziv,cnt';
$SETTINGS['tbl_kategorijaslike_header'] = 'ID,Naziv,#slika';
$SETTINGS['tbl_kategorijaslike_widths'] = '50,400,100';
$SETTINGS['tbl_kategorijaslike_aligns'] = 'right,left,center';
$SETTINGS['tbl_kategorijaslike_types'] = 'ro,ro,ro';
$SETTINGS['tbl_kategorijaslike_fields'] = 'id,naziv,cnt';

$SETTINGS['tbl_klijent_fields_all'] = 'id,naziv,puni_naziv,oib,telefon,email';
$SETTINGS['tbl_klijent_header'] = 'ID,Naziv,Puni naziv,OIB,Telefon,Email';
$SETTINGS['tbl_klijent_widths'] = '50,160,250,120,150,200';
$SETTINGS['tbl_klijent_aligns'] = 'right,left,left,center,left,left';
$SETTINGS['tbl_klijent_types'] = 'ro,ro,ro,ro,ro,ro';
$SETTINGS['tbl_klijent_fields'] = 'id,naziv,puni_naziv,oib,telefon,email';

$SETTINGS['tbl_log_fields_all'] = 'fcreated,user,title,subject,dbindex,ip,app,importance';
$SETTINGS['tbl_log_header'] = 'Datum,Korisnik,Akcija,Subjekt,Index,IP,Program,Važnost';
$SETTINGS['tbl_log_widths'] = '95,100,200,390,40,70,50,60';
$SETTINGS['tbl_log_aligns'] = 'left,left,left,left,center,left,center,center';
$SETTINGS['tbl_log_types'] = 'ro,ro,ro,ro,ro,ro,ro,ro';
$SETTINGS['tbl_log_fields'] = 'fcreated,user,title,subject,dbindex,ip,app,importance';

$SETTINGS['tbl_mail_fields_all'] = 'id,title,subject,emfrom,emfromname,body,html,fcreated,cnt';
$SETTINGS['tbl_mail_header'] = 'ID,Naziv template-a,Subject,Adresa slanja,Naziv pošiljatelja,Sadržaj,HTML,Datum,#sent';
$SETTINGS['tbl_mail_widths'] = '30,220,240,110,110,150,50,100,70';
$SETTINGS['tbl_mail_aligns'] = 'left,left,left,left,left,left,center,center,center';
$SETTINGS['tbl_mail_types'] = 'ro,ro,ro,ro,ro,ro,ro,ro,ro';
$SETTINGS['tbl_mail_fields'] = 'id,title,subject,emfrom,emfromname,body,html,fcreated,cnt';


$SETTINGS['tbl_ponuda_fields_all'] = 'id,code,naziv,datum_izdavanja,datum_dospijeca,cnt,osnovica,pdv,iznos,operater,created,modified';
$SETTINGS['tbl_ponuda_header'] = 'ID,Broj,Naziv,Datum izr.,Vrijedi do,#stavki,Osnovica (€),PDV (€),Iznos (€),Sastavio,Kreirano,Zadnja izmjena';
$SETTINGS['tbl_ponuda_widths'] = '40,80,180,80,80,50,80,70,80,135,120,120';
$SETTINGS['tbl_ponuda_aligns'] = 'right,left,left,center,center,center,right,right,right,left,left,left,left';
$SETTINGS['tbl_ponuda_types'] = 'ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro';
$SETTINGS['tbl_ponuda_fields'] = 'id,code,naziv,datum_izdavanja,datum_dospijeca,cnt,osnovica,pdv,iznos,operater,modified';

$SETTINGS['tbl_proizvodjac_fields_all'] = 'id,naziv';
$SETTINGS['tbl_proizvodjac_header'] = 'ID,Naziv';
$SETTINGS['tbl_proizvodjac_widths'] = '50,300';
$SETTINGS['tbl_proizvodjac_aligns'] = 'right,left';
$SETTINGS['tbl_proizvodjac_types'] = 'ro,ro';
$SETTINGS['tbl_proizvodjac_fields'] = 'id,naziv';

$SETTINGS['tbl_racun_fields_all'] = 'id,code,naziv,datum_izdavanja,vrijeme_izdavanja,datum_isporuke,datum_dospijeca,cnt,osnovica,pdv,iznos,operater,created,modified,napomena';
$SETTINGS['tbl_racun_header'] = 'ID,Broj,Naziv,Datum izd.,Vrijeme izd.,Isporuka,Dospijeće,#stavki,Osnovica (€),PDV (€),Iznos (€),Operater,Kreirano,Zadnja izmjena,Tip napomene';
$SETTINGS['tbl_racun_widths'] = '40,80,180,80,80,80,80,50,80,70,80,135,120,120,200';
$SETTINGS['tbl_racun_aligns'] = 'right,left,left,center,center,center,center,center,right,right,right,left,left,left,left';
$SETTINGS['tbl_racun_types'] = 'ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro';
$SETTINGS['tbl_racun_fields'] = 'id,code,naziv,datum_izdavanja,vrijeme_izdavanja,datum_isporuke,datum_dospijeca,cnt,osnovica,pdv,iznos,napomena';


$SETTINGS['tbl_racunnapomena_fields_all'] = 'id,naziv,tekst,cnt';
$SETTINGS['tbl_racunnapomena_header'] = 'ID,Naziv,Tekst,#računa';
$SETTINGS['tbl_racunnapomena_widths'] = '50,150,400,80';
$SETTINGS['tbl_racunnapomena_aligns'] = 'right,left,left,center';
$SETTINGS['tbl_racunnapomena_types'] = 'ro,ro,ro,ro';
$SETTINGS['tbl_racunnapomena_fields'] = 'id,naziv,tekst,cnt';



$SETTINGS['tbl_regija_fields_all'] = 'id,code,naziv,cnt';
$SETTINGS['tbl_regija_header'] = 'ID,Kod,Naziv,#zupanija';
$SETTINGS['tbl_regija_widths'] = '50,80,300,100';
$SETTINGS['tbl_regija_aligns'] = 'right,left,left,center';
$SETTINGS['tbl_regija_types'] = 'ro,ro,ro,ro';
$SETTINGS['tbl_regija_fields'] = 'id,code,naziv,cnt';




$SETTINGS['tbl_specijalniupit_fields_all'] = 'id,qopt,naziv,opis';
$SETTINGS['tbl_specijalniupit_header'] = 'ID,Komponenta,Naziv,Opis';
$SETTINGS['tbl_specijalniupit_widths'] = '30,150,300,*';
$SETTINGS['tbl_specijalniupit_aligns'] = 'right,left,left,left';
$SETTINGS['tbl_specijalniupit_types'] = 'ro,ro,ro,ro';
$SETTINGS['tbl_specijalniupit_fields'] = 'id,qopt,naziv,opis';

$SETTINGS['tbl_stavka_fields_all'] = 'id,tip,kategorija,code,naziv,mjera,iznos,stopa_pdv';
$SETTINGS['tbl_stavka_header'] = 'ID,Tip,Kategorija,Kod,Naziv,Mjera,Iznos (€),Stopa PDV-a';
$SETTINGS['tbl_stavka_widths'] = '50,120,120,80,*,70,100,80';
$SETTINGS['tbl_stavka_aligns'] = 'right,left,left,left,left,center,right,right';
$SETTINGS['tbl_stavka_types'] = 'ro,ro,ro,ro,ro,ro,ro,ro';
$SETTINGS['tbl_stavka_fields'] = 'id,tip,kategorija,code,naziv,mjera,iznos';

$SETTINGS['tbl_stavke_fields_all'] = 'ordering,opis,mjera,kolicina,stopa_pdv,cijena,popust,osnovica,pdv,iznos';
$SETTINGS['tbl_stavke_header'] = '#,Opis,MJ,Kol.,PDV(%),Cijena (€),Popust(%),Iznos (€),PDV (€),Ukupno (€)';
$SETTINGS['tbl_stavke_widths'] = '30,320,60,55,50,70,65,80,70,80';
$SETTINGS['tbl_stavke_aligns'] = 'right,left,center,center,center,right,center,right,right,right';
$SETTINGS['tbl_stavke_types'] = 'ed,ed,ed,ed,ed,ed,ed,ro,ro,ro';
$SETTINGS['tbl_stavke_fields'] = 'ordering,opis,mjera,kolicina,stopa_pdv,cijena,popust,osnovica,pdv,iznos';

$SETTINGS['tbl_pstavke_fields_all'] = 'ordering,opis,mjera,kolicina,stopa_pdv,cijena,popust,osnovica,pdv,iznos';
$SETTINGS['tbl_pstavke_header'] = '#,Opis,MJ,Kol.,PDV(%),Cijena (kn),Popust(%),Iznos (kn),PDV (kn),Ukupno (kn)';
$SETTINGS['tbl_pstavke_widths'] = '30,320,60,55,50,70,65,80,70,80';
$SETTINGS['tbl_pstavke_aligns'] = 'right,left,center,center,center,right,center,right,right,right';
$SETTINGS['tbl_pstavke_types'] = 'ed,ed,ed,ed,ed,ed,ed,ro,ro,ro';
$SETTINGS['tbl_pstavke_fields'] = 'ordering,opis,mjera,kolicina,stopa_pdv,cijena,popust,osnovica,pdv,iznos';




$SETTINGS['tbl_user_fields_all'] = 'id,code,ime,prezime,name,username,email,telefon,permissions';
$SETTINGS['tbl_user_header'] = 'Šifra,Kod,Ime,Prezime,Ime i prezime,Username,Email,Telefon,Ovlaštenja';
$SETTINGS['tbl_user_widths'] = '50,45,100,100,180,80,200,150,*';
$SETTINGS['tbl_user_aligns'] = 'right,left,left,left,left,left,left,left,left';
$SETTINGS['tbl_user_types'] = 'ro,ro,ro,ro,ro,ro,ro,ro,ro';
$SETTINGS['tbl_user_fields'] = 'code,name,username,email,telefon,permissions';

$SETTINGS['tbl_tipstavke_fields_all'] = 'id,naziv,cnt';
$SETTINGS['tbl_tipstavke_header'] = 'ID,Naziv,#stavki';
$SETTINGS['tbl_tipstavke_widths'] = '50,400,100';
$SETTINGS['tbl_tipstavke_aligns'] = 'right,left,center';
$SETTINGS['tbl_tipstavke_types'] = 'ro,ro,ro';
$SETTINGS['tbl_tipstavke_fields'] = 'id,naziv,cnt';


$SETTINGS['tblF_stavka_fields_all'] = 'naziv,mjera,iznos,tip,kategorija';
$SETTINGS['tblF_stavka_header'] = 'Naziv stavke,Mjera,Iznos,Tip,Kategorija';
$SETTINGS['tblF_stavka_widths'] = '278,60,70,120,120';
$SETTINGS['tblF_stavka_aligns'] = 'left,center,right,left,left';
$SETTINGS['tblF_stavka_types'] = 'ro,ro,ro,ro,ro';
$SETTINGS['tblF_stavka_fields'] = 'naziv,mjera,iznos,tip,kategorija';

?>