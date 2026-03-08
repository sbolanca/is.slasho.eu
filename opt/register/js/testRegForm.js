

function testRegForm() {
	var frm=document.reg_form;
	
	if (((frm.id.value && frm.password.value ) || !(frm.id.value))
		&& (frm.passwordc.value!=frm.password.value)) alert(regfrmmsg2);
	else {
		if ((frm.name.value) &&
			 (frm.email.value) &&
			 (frm.phone.value) &&
			 (frm.aktualno.value) &&
			 (frm.username.value) 
			 ) frm.submit()  ;
		else alert(regfrmmsg) ;	
	}
}
