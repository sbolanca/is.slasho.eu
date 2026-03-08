

var scrollerwidth="450px";

// Scrollers height here
var scrollerheight="100px";

// Scrollers speed here (larger is faster 1-10)
         //scroller width
        var swidth=163;

        //scroller height
        var sheight=275;

        //background color
        var sbcolor='';

        //scroller's speed
        var sspeed=1;

       

        var resumesspeed=sspeed;
        function start() {
                if (document.all) iemarquee(ticker);
                else if (document.getElementById)
                        ns6marquee(document.getElementById('ticker'));
        }
        
        function iemarquee(whichdiv){
				var cntn=document.getElementById('ntContentDiv');
                iediv=eval(whichdiv)
                sheight += 50;
                iediv.style.pixelTop=sheight
                iediv.innerHTML=cntn.innerHTML
                sizeup=iediv.offsetHeight
                ieslide()
        }
        
        function ieslide(){
                if (iediv.style.pixelTop>=sizeup*(-1)){
                        iediv.style.pixelTop-=sspeed
                        setTimeout("ieslide()",30)
                }
                else{
                        iediv.style.pixelTop=sheight
                        ieslide()
                }
        }
        
        function ns6marquee(whichdiv){
				var cntn=document.getElementById('ntContentDiv');
               
                ns6div=eval(whichdiv)
                sheight += 50;
                ns6div.style.top=sheight + "px";
                ns6div.innerHTML=cntn.innerHTML
                sizeup=ns6div.offsetHeight
                ns6slide()
        }
        function ns6slide(){
                if (parseInt(ns6div.style.top)>=sizeup*(-1)){
                        theTop = parseInt(ns6div.style.top)-sspeed
                        ns6div.style.top = theTop + "px";
                        setTimeout("ns6slide()",30)
                }
                else {
                        ns6div.style.top = sheight + "px";
                        ns6slide()
                }
        }


