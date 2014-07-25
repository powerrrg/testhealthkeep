function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};
function isEmpty(ele){
	if($('#'+ele).val().trim()==""){
		return true;
	}else{
		return false;
	}
}
var pushedfooter=false;

function pushFooterDown() {
    if ($(window).height() > $('body').innerHeight())
    {
        $('#main,#mainBlue').height(
	        $(window).height() - (
	            $('body').innerHeight() - $('#main,#mainBlue').outerHeight(true)
	        )
        );
        pushedfooter=true;
    }else{
    	if(pushedfooter){
    		$('#main,#mainBlue').height('auto');
    	}
    }

}
window.onresize = function() {
	pushFooterDown();
}

function cleanHash(){
	window.location.hash='';
	history.pushState('', document.title, window.location.pathname);
	e.preventDefault();
}

function getHash() {
  var hash = window.location.hash;
  return hash.substring(1);
}

var waiting=false;
var pageNum=1;

var ENDurl;
var ENDele;
var ENDtval;

function endlessScroll(url0,ele0,tval0,tval1,tval2,tval3,tval4,tval5){
	$("#iMLoader").hide();
	ENDurl=url0;
	ENDele=ele0;
	ENDtval=tval0;
	ENDtval1=tval1;
	ENDtval2=tval2;
	ENDtval3=tval3;
	ENDtval4=tval4;
	ENDtval5=tval5;
	ENDtval = typeof ENDtval !== 'undefined' ? ENDtval : '';
	ENDtval1 = typeof ENDtval1 !== 'undefined' ? ENDtval1 : '';
	ENDtval2 = typeof ENDtval2 !== 'undefined' ? ENDtval2 : '';
	ENDtval3 = typeof ENDtval3 !== 'undefined' ? ENDtval3 : '';
	ENDtval4 = typeof ENDtval4 !== 'undefined' ? ENDtval4 : '';
	ENDtval5 = typeof ENDtval5 !== 'undefined' ? ENDtval5 : '';
	$(window).scroll(function() {
		var tot=$(document).height()-$(window).height();
		var goAjax=tot-$(window).height();
		var pos=$(document).scrollTop();
		
		if(pos>goAjax){
			if(!waiting){
				$("#iMLoader").show();
				pageNum++;
				waiting=true;
				$.ajax({
				type: "POST",
				url: ENDurl,
				data: { p: pageNum, t:ENDtval, x:ENDtval1, y:ENDtval2, j:ENDtval3, g:ENDtval4, h:ENDtval5 },
				 statusCode: {
					404: function() {
					$("#iMLoader").hide();
					waiting=true;
					}
				}
				}).done(function( msg ) {
				$("#iMLoader").hide();
				if(msg==""){
					waiting=true;
					console.log("finito!!!");
				}else{
					ENDele.append(msg);
					waiting=false;
					if ( $.isFunction(loadRaty) ) {
					loadRaty();
					}
				}
				
				});
				
			}
		}
	
	});
}

function isValidDate(dtMonth,dtDay,dtYear)
{

  if (dtMonth < 1 || dtMonth > 12)
      return false;
  else if (dtDay < 1 || dtDay> 31)
      return false;
  else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      return false;
  else if (dtMonth == 2)
  {
     var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
     if (dtDay> 29 || (dtDay ==29 && !isleap))
          return false;
  }
  return true;
}

function isValidImage(ele)
{
    var extensions = new Array("jpg","jpeg","gif","png","bmp");

    var image_file = $('#'+ele).val(); //document.form.image.value;

    var image_length = $('#'+ele).val().length; //document.form.image.value.length;

    var pos = image_file.lastIndexOf('.') + 1;

    var ext = image_file.substring(pos, image_length);

    var final_ext = ext.toLowerCase();

    for (i = 0; i < extensions.length; i++)
    {
        if(extensions[i] == final_ext)
        {
        return true;
        }
    }

    return false;
}

function isValidPhone(phoneNumber){
	
	var regexObj = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;

	if (regexObj.test(phoneNumber)) {
	    var formattedPhoneNumber =
	        phoneNumber.replace(regexObj, "($1) $2-$3");
	    return formattedPhoneNumber;
	} else {
	    return false;
	}
}

function isValidURL(ele) {

	var value = $('#'+ele).val();
	return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);

}

function isOverflowHidden(ele,parent_ele){
	return ele.height() > parent_ele.height();
}


/*!
* Bootstrap.js by @fat & @mdo
* Copyright 2012 Twitter, Inc.
* http://www.apache.org/licenses/LICENSE-2.0.txt
*/
/* TRANSITIONS */
!function(a){a(function(){a.support.transition=(function(){var b=(function(){var e=document.createElement("bootstrap"),d={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"},c;for(c in d){if(e.style[c]!==undefined){return d[c]}}}());return b&&{end:b}})()})}(window.jQuery);
/* DROPDOWN */
!function(f){var b="[data-toggle=dropdown]",a=function(h){var g=f(h).on("click.dropdown.data-api",this.toggle);f("html").on("click.dropdown.data-api",function(){g.parent().removeClass("open")})};a.prototype={constructor:a,toggle:function(j){var i=f(this),h,g;if(i.is(".disabled, :disabled")){return}h=e(i);g=h.hasClass("open");d();if(!g){h.toggleClass("open")}i.focus();return false},keydown:function(l){var k,m,g,j,i,h;if(!/(38|40|27)/.test(l.keyCode)){return}k=f(this);l.preventDefault();l.stopPropagation();if(k.is(".disabled, :disabled")){return}j=e(k);i=j.hasClass("open");if(!i||(i&&l.keyCode==27)){if(l.which==27){j.find(b).focus()}return k.click()}m=f("[role=menu] li:not(.divider):visible a",j);if(!m.length){return}h=m.index(m.filter(":focus"));if(l.keyCode==38&&h>0){h--}if(l.keyCode==40&&h<m.length-1){h++}if(!~h){h=0}m.eq(h).focus()}};function d(){f(b).each(function(){e(f(this)).removeClass("open")})}function e(i){var g=i.attr("data-target"),h;if(!g){g=i.attr("href");g=g&&/#/.test(g)&&g.replace(/.*(?=#[^\s]*$)/,"")}h=g&&f(g);if(!h||!h.length){h=i.parent()}return h}var c=f.fn.dropdown;f.fn.dropdown=function(g){return this.each(function(){var i=f(this),h=i.data("dropdown");if(!h){i.data("dropdown",(h=new a(this)))}if(typeof g=="string"){h[g].call(i)}})};f.fn.dropdown.Constructor=a;f.fn.dropdown.noConflict=function(){f.fn.dropdown=c;return this};f(document).on("click.dropdown.data-api",d).on("click.dropdown.data-api",".dropdown form",function(g){g.stopPropagation()}).on(".dropdown-menu",function(g){g.stopPropagation()}).on("click.dropdown.data-api",b,a.prototype.toggle).on("keydown.dropdown.data-api",b+", [role=menu]",a.prototype.keydown)}(window.jQuery);