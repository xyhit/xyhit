function ajax_request(){
	var checkcode = document.getElementById(id="mycheckcode").value;
	if (checkcode.length < 4){
		document.getElementById(id="errorpic").setAttribute("class", "non");
		return;
	}
	var xmlhttp;
	if (window.XMLHttpRequest){
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} 
	else {
		// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("get", "http://1.xyhit.applinzi.com/checkcodeajax.php?checkcode="+checkcode, true);
	xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	xmlhttp.send();
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			var recv = xmlhttp.responseText;
			if (recv != ""){
				document.getElementById(id="errorpic").setAttribute("class", "weui_icon_success");
			}
			else{
				document.getElementById(id="errorpic").setAttribute("class", "");
			}
		}
	}
}