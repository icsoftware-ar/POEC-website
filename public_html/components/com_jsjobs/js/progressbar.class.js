// JavaScript Document
// class: progressbar
// A customizable Progress Bar
// Dixán Santiesteban Feria, CUBA
// email: dixan_sant@yahoo.es


progressbar = function(o,opt){
	
	opt = (opt==null)?{}:opt
	this.opt = merge2(opt,{animate:false,color:'blue'});
	this.actualpercent=0;
	this.hwd = null;
	
	//alert(opt.animate1)
	this.obj = _ge(o);
	this.obj.className = "progressbar";
	
	var tit='<div class="progresstit" style="color:#FFFFFF; margin-left:-1px; margin-top:-1px;">0%</div><div class="progresstit" style="color:#FFFFFF; margin-left:1px; margin-top:1px;">0%</div><div class="progresstit" style="">0%</div>';
	this.obj.innerHTML='<div class="progressinner '+this.opt.color+'"></div>'+tit;

	this.percent = function(p){
		
		if (this.opt.animate) this.animate(p);
		else this.putpercent(p);
	}
	
	this.animate = function(w){
		clearTimeout(this.hwd);
		this.actualpercent+=(this.actualpercent<w)?1:-1;
		this.putpercent(this.actualpercent);
		var self = this;
		if (this.actualpercent!=w) this.hwd=setTimeout(function(){self.animate(w)},10);
	}
	
	this.putpercent = function(p){
		this.actualpercent = p;
		var w=this.obj;
		var wi=w.clientWidth;
		var nwi= wi/100*p;
		w.children[0].style.width=nwi+"px";
		
		for (var t=0; t<w.children.length; ++t){
			if(w.children[t].className=="progresstit"){
				w.children[t].innerHTML = p+"%";
			}
		}
	}
	

}


function merge2 (arr1,arr2){
	for (var t in arr2){
		if (arr1[t]) {}
		else { arr1[t]=arr2[t]}
	} return arr1;
}
	
function _ge(w){
	if (typeof w=='object') return w;
	else if (typeof w=='string') return document.getElementById(w);
	return false;
}
