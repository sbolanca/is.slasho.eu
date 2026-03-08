var topen=null;
var acolstyles=['','red','green','blue','ljub','color5','color6'];
function setImpResult(el,t,u,i) {
	var suf=el;
	$('.imr_'+suf+' .impprocessed').html($('.imr_'+suf+' .impprocessed').html()*1+t);
	$('.imr_'+suf+' .impupdated').html($('.imr_'+suf+' .impupdated').html()*1+u);
	$('.imr_'+suf+' .impinserted').html($('.imr_'+suf+' .impinserted').html()*1+i);

}
function copyDateFields(f,from,to) {
	$('#'+f+" input[name='"+to+"_dan']").val($('#'+f+" input[name='"+from+"_dan']").val());
	$('#'+f+" input[name='"+to+"_mjesec']").val($('#'+f+" input[name='"+from+"_mjesec']").val());
	$('#'+f+" input[name='"+to+"_godina']").val($('#'+f+" input[name='"+from+"_godina']").val());
}
function startImport(el,typ,fld,fil) {
	$('.impbtnstart').hide();
	$('.imr_'+el).show();	
	$('.imr_'+el+' .progressbar').progressbar({value: 0});
	canRefresh=false;
	aCMC(el,'admin','pcs-'+typ,'folder='+fld+'&file='+fil);
	showEditPopup('popupdescription');
}
function makeDrag(sl,o){
	if(!o) var o=0;
	$(sl).draggable({ 
		revert: true,
		revertDuration: 200, 
		helper: "clone",
		start: function(){$(this).addClass("tdyellow")},
		stop: function(){$(this).removeClass("tdyellow")} 
	});
	if(o) $(sl).draggable('option','scroll',false).draggable('option','appendTo','body').draggable('option','zIndex',500000);
	
}
function testID(v) {
	if (!v || (v=="0")) return false;
	else return true;
}
function setAutoCompletedClasses(fldname,noID,incCode) {
	var jCode=$( "#"+fldname+"ACCode");
	var jFld=$( "#"+fldname+"AC");
	var jBtn=$("#"+fldname+"ACBtn" );
	if(!noID) var noID=false;
	if(noID) {
		jCode.removeClass( 'mysukob');
		if(incCode>1) for(var i=2;i<=incCode;i++) $( "#"+fldname+"ACCode"+i ).removeClass( 'mysukob');
		jFld.removeClass( 'own');
		jBtn.removeClass( 'hide');
	} else {
		if($(document.activeElement).attr('id')==(fldname+'ACBtn')) $('#'+fldname+'ACFld').focus();
		jCode.addClass( 'mysukob');
		if(incCode>1) for(var i=2;i<=incCode;i++) $( "#"+fldname+"ACCode"+i ).addClass( 'mysukob');
		jFld.addClass( 'own');
		jBtn.addClass( 'hide');
	}
}
function setAutoCompleteData(fldname,code,incCode) {
	var fld=$( "#"+fldname+"AC");
	var fldID=$( "#"+fldname+"ACFld" );
	var fldCode=$( "#"+fldname+"ACCode" );
	fldCode.data( code,fldCode.val() );
	if(incCode>1) for(var i=2;i<=incCode;i++) $( "#"+fldname+"ACCode"+i ).data( code,$( "#"+fldname+"ACCode"+i ).val() );
	fldID.data( code,fldID.val() );
	fld.data( code,fld.val() );	
}
function setAutoCompleteValues(fldname,v,t,c) {
	$('#'+fldname+'ACFld').val(v);
	$('#'+fldname+'ACCode').val(c);
	if (arguments.length>4) var incCode=arguments.length-2;
	else incCode=0;
	if(incCode>1) for(var i=2;i<=incCode;i++) $("#"+fldname+"ACCode"+i).val(arguments[2+i]);
	if(testID(v)) {
		$('#'+fldname+'AC').val(t);
		setAutoCompleteData(fldname,'currv',incCode);
		setAutoCompletedClasses(fldname,false,incCode);
	} else setAutoCompletedClasses(fldname,true,incCode);
}
function setAutoCompleteValues2(fldname,v,t,c) {
	$('#'+fldname+'AC').val(t);
	$('#'+fldname+'ACCode').val(c);
	$('#'+fldname+'ACFld').val(v);
	if (arguments.length>4) var incCode=arguments.length-2;
	else incCode=0;
	if(incCode>1) for(var i=2;i<=incCode;i++) $("#"+fldname+"ACCode"+i).val(arguments[2+i]);
	if(t) {
		setAutoCompleteData(fldname,'currv',incCode);
		setAutoCompletedClasses(fldname,false,incCode);
	} else setAutoCompletedClasses(fldname,true,incCode);

}
function autocompleteBtnField(fldname,ml,jopt,incCode,incCodeEscape,jact,func,params) {
	var fld=$( "#"+fldname+"AC");
	var fldID=$( "#"+fldname+"ACFld" );
	var fldCode=$( "#"+fldname+"ACCode" );
	var cBtn=$("#"+fldname+"ACBtn" );
	if(!ml) var ml=2;
	if(!incCode) var incCode=0;
	if(!incCodeEscape) var incCodeEscape=0;
	if(!jopt) var jopt='izvodjac';
	if(!jact) var jact='getvalue';
	if(!params) var pA=new Array();
	else pA=params.split("|");
	
	
	fld.keydown( function( event ) {
			if(!(event.which==9)) { 
				fldID.val('');
				fldCode.val('');
				if(incCode>1) for(var i=2;i<=incCode;i++) $( "#"+fldname+"ACCode"+i).val( '' );				
			}
	}).keyup( function( event ) {
			if(!testID(fldID.val())) {
				var testOld=(testID(fldID.data("oldv")) && (fld.val()==fld.data( "oldv")))?1:0;
				if( testOld	|| (testID(fldID.data("currv")) && (fld.val()==fld.data( "currv"))) ) {					
					fldID.val( fldID.data(testOld?"oldv":"currv") );
					fldCode.val( fldCode.data(testOld?"oldv":"currv") );
					if(incCode>1) for(var i=2;i<=incCode;i++) $( "#"+fldname+"ACCode"+i ).val(  $( "#"+fldname+"ACCode"+i ).data(testOld?"oldv":"currv") );
			
					setAutoCompletedClasses(fldname,false,incCode);
				} else setAutoCompletedClasses(fldname,true,incCode);
			} else setAutoCompletedClasses(fldname,false,incCode);
	}).autocomplete({
		appendTo: 'body',
		minLength: ml,
		source: "json.php?opt="+jopt+"&act="+jact+'&inccode='+incCode,
		select: function( event, ui ) {
			fldID.val( ui.item.id );
			fldCode.val( ui.item.code );
			
			if(incCode>1) for(var i=2;i<=incCode;i++) eval('$( "#"+fldname+"ACCode'+i+'" ).val( ui.item.code'+i+' )');
			setAutoCompleteData(fldname,"currv",incCode);
			setAutoCompletedClasses(fldname,false,incCode);	
			if(func) eval(func+'(ui.item)');
		},
		create: function() {
			setAutoCompleteData(fldname,"oldv",incCode);
		}
	}).on( "autocompletesearch", function( event, ui ) {
		if(pA.length) {
			var p='';
			for(var i=0;i<pA.length;i++) {
				var pAA=pA[i].split('?');
				if(pAA.length>1) p+='&'+pAA[0]+'='+$('#'+pAA[1]).val();
			}
			$(this).autocomplete( "option", "source","json.php?opt="+jopt+"&act="+jact+'&inccode='+incCode+p)
		}
	}).data("ui-autocomplete")._renderItem = function( ul, item ) {
	  var addlbl=(incCode && !incCodeEscape && item.code?" [<span class=\"red\">" + item.code +"</span>]":'');
	  if(incCode>1) for(var i=2;i<=incCode;i++)  eval('if(item.code'+i+') addlbl+=" [<span class=\\""+acolstyles[i]+"\\">"+item.code'+i+'+"</span>]"');
      return $( "<li>" )
        .append( "<a>" + item.label + addlbl+"</a>" )
        .appendTo( ul );
    };
}
function testACValues(n,s) {
	if(!s) var s='Fld';
	if($('#'+n+'AC').val() && !($('#'+n+'AC'+s).val()*1)) return false;
	else return true;
}
function setIzvDataFld(obj) {
	if (typeof obj.trajanje != 'undefined') {
		$('#trSatFld').val(obj.trajanje.substr(0,2));
		$('#trMinFld').val(obj.trajanje.substr(3,2));
		$('#trSecFld').val(obj.trajanje.substr(6,2));
	}
	if (typeof obj.puta != 'undefined') $('#putaFld').val(obj.puta);
	//if (typeof obj.ordering != 'undefined') $('#rbrFld').val(obj.ordering);
	if (typeof obj.type != 'undefined') $('#typeSel').val(obj.type);
}
function setInputMode(ix,pref,model) {
	var td=$('#'+pref+ix);
	var ttl=td.html();

	if (ttl.indexOf('<input')<0) {
		var input=$('#'+model).html().replace(/\[ID\]/g,ix);
		td.html(input);
		td.find('input').val(ttl).focus();
	}
}
function resetSrc(nm) {
	if (!nm) nm='searchForm';
	var frm=document.getElementById(nm);
	for (var i=0;i<frm.elements.length;i++) {
		switch (frm.elements[i].type) {
			case "radio": case "checkbox": frm.elements[i].checked=false; break;
			case "select": case "select-one": frm.elements[i].selectedIndex=0; break;
			case "text": case "text":  frm.elements[i].value='';
		}
	}
	$('#'+nm+' .mlt').multipleSelect('uncheckAll');
	$('#'+nm+' .mltv').multipleSelect('uncheckAll');
}

function btw(val,x,y) {
	return ((val>x) && (val<y));
}
function doSearch(fld,tbl,indx) {
		var reo=new RegExp("&amp;","g");
		var frm=document.getElementById('searchForm');
		resetSrc();
		var v=tbl.cells(tbl.getSelectedId(),indx).getValue();
		if (fld=='albumID') { 
			fld='album'; 
			frm['albumID'].value=tbl.getSelectedId(); 
			document.getElementById('srcalbumfield').style.color="#dd0000";
		}
		frm[fld].value=v.replace(reo,"&");
		frm.exact.value="1";
		frm.submit();
		frm.exact.value="0";
}
function setButtonsHighlight(id) {
		var allLIs= document.getElementById(id).childNodes;
		for (var i=0;i<allLIs.length;i++) if (!(allLIs[i].id=='menuseparator') && (allLIs[i].tagName=='LI') ){
		   tLI = allLIs[i];
		   tLI.onselectstart = function(){return false}
		   tLI.onmouseover=function(){
			   this.style.borderLeft = "1px white  solid";
			   this.style.borderTop = "1px white  solid";
			   this.style.borderRight = "1px ButtonShadow  solid";
			   this.style.borderBottom = "1px ButtonShadow  solid";
			   if (this.childNodes.length>2) this.childNodes[2].style.display="block";
		   }
		   //attachFunction(tLI,'mouseover',f);		
		   tLI.onmouseout = function(){
			   this.style.border = "1px solid #ECE9D8";
			   if (this.childNodes.length>2) this.childNodes[2].style.display="none";
		   }
		   tLI.onmousedown = function(){
			   this.style.border = "1px white inset";
		   }
		   tLI.onmouseup = function(){
			     this.style.borderLeft = "1px white  solid";
			   this.style.borderTop = "1px white  solid";
			   this.style.borderRight = "1px ButtonShadow  solid";
			   this.style.borderBottom = "1px ButtonShadow  solid";
		   }
	   }
}

function fitH() {
	
		document.getElementById('gridbox').style.height=tdgridh;
		document.getElementById('treebox').style.height=tdgridh;
	
}
function initH() {
	tdgridh=document.getElementById('tdgrid').offsetHeight;
}

function setFieldsPair(f,n,v){
	$(document).ready(function () {
		$('#'+f).attr('name',n);
		$('#'+f).val(v);
	})
}
function mR(tID,rID) {
	var pom=$('#'+tID+' tr.bgyellow');
	$('#'+tID+' tr').removeClass('bgyellow');
	if(rID==1) {
		if(pom.length) pom.next().addClass('bgyellow');
		else $('#'+tID+' tbody tr:first-child').addClass('bgyellow');
	} if(rID==-1) {
		if(pom.length) pom.prev().addClass('bgyellow');
		else $('#'+tID+' tbody tr:last-child').addClass('bgyellow');
	} else {
	$(document).off( "keydown" );
		if($('#'+tID).length) {
			$('#'+tID+' #'+rID).addClass('bgyellow');
			$(document).keydown(function(event) {
				switch(event.which){
					case 40: mR(tID,1); break;
					case 38: mR(tID,-1); break;
				}
			});
		}
	}
}
function setViewedImg(tbl,rid,fld,img,com){
	eval("t="+tbl+";f="+tbl+"_fields");
	ix=$.inArray( fld,f.split(",") );
	if(ix>-1) t.cells(rid,ix).setValue('<img src="i/'+img+'" alt="'+com+'"/>');
}
function colTH(f,v,cls) {
	if(!cls) var cls='ljub';
	var p=$('#'+f);
	p.removeClass(cls);
	if(v) p.addClass(cls)
}
mainCM=['admin',
		[
		 	['get','Konfiguracija','configuration|param']
			
		],"","isSuper==1"
	];
	
var IdsVarRS="'ids='+getSelectedIds(tbl_stavke,currentItem)";
CM_opt_racun_stavke=['racun',
		[
			['get-1','Mijenjaj','stv-edit'],			
			['delp','Briši','stv-delete','Jeste li sigurni da želite izbrisati stavku računa?',"'racunID='+currentVar1+'&'+"+IdsVarRS],
			['sep'],
			['get-1','Dodaj novu stavku','stv-new',"'racunID='+currentVar1"]
		],"tbl_stavke"
	];
function stavke_cm(i,c,e) {doOnTblCM(i,c,e,tbl_stavke,'CM_opt_racun_stavke'); }	

function Init_tbl_stavke(cnt,rid) {
		InitStandardTable('tbl_stavke',"opt=racun&act=stavke&id="+rid,cnt,'stavke_cm','s');
}
function onStavkeEdit(mod,rid,cid) {
		if (mod==0) old_obrada=tbl_stavke.cells(rid,cid).getValue();
		else if (mod==2) {
			var val=tbl_stavke.cells(rid,cid).getValue();
			if (!(val==old_obrada)) {
				var fld=tbl_stavke_fields.split(",");
				aCMCP(rid,'racun','stv-setvalue','fld='+fld[cid]+'&val='+val);
			}
		}
}

var IdsVarPS="'ids='+getSelectedIds(tbl_pstavke,currentItem)";
CM_opt_ponuda_stavke=['ponuda',
		[
			['get-1','Mijenjaj','stv-edit'],			
			['delp','Briši','stv-delete','Jeste li sigurni da želite izbrisati stavku ponude?',"'ponudaID='+currentVar1+'&'+"+IdsVarPS],
			['sep'],
			['get-1','Dodaj novu stavku','stv-new',"'ponudaID='+currentVar1"]
		],"tbl_pstavke"
	];
function pstavke_cm(i,c,e) {doOnTblCM(i,c,e,tbl_pstavke,'CM_opt_ponuda_stavke'); }	

function Init_tbl_pstavke(cnt,rid) {
		InitStandardTable('tbl_pstavke',"opt=ponuda&act=stavke&id="+rid,cnt,'pstavke_cm','p');
}
function onPStavkeEdit(mod,rid,cid) {
		if (mod==0) old_obrada=tbl_pstavke.cells(rid,cid).getValue();
		else if (mod==2) {
			var val=tbl_pstavke.cells(rid,cid).getValue();
			if (!(val==old_obrada)) {
				var fld=tbl_pstavke_fields.split(",");
				aCMCP(rid,'ponuda','stv-setvalue','fld='+fld[cid]+'&val='+val);
			}
		}
}

function calcStavka(frm) {
	if (!frm) frm='racunStavkaForm'
	var o=$("#"+frm+" #rsi_k").val()*$("#"+frm+" #rsi_c").val()*(1-$("#"+frm+" #rsi_p").val()/100);
	$("#"+frm+" #rso").html(o);
	var p=$("#"+frm+" #rsi_s").val()*o/100;
	$("#"+frm+" #rsp").html(p);
	$("#"+frm+" #rsi").html(o+p);
}
function setRacunIznosi(o,p,i,frm) {
	if (!frm) frm='racunForm';
	$("#"+frm+" #osnFld").html(o);
	$("#"+frm+" #pdvFld").html(p);
	$("#"+frm+" #iznFld").html(i);
}