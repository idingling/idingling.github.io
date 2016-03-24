var linksone=getClass("titleone")[0].getElementsByTagName("a");
var listsone=getClass("listone");
tab(linksone,listsone);

function tab (links,lists) {
for (var i=0; i<links.length; i++) {
    links[i].index=i;
    links[i].onclick=function  () {
	  for (var j=0; j<lists.length; j++) {
	    lists[j].style.display="none";
		links[j].style.background="";
		links[j].style.color="#000"
	  }
      lists[this.index].style.display="block";
	  this.style.background="";
	  this.style.color="#F00";
    }
  }
}
function getClass (classname,obj) {
  var obj=obj||document
  var arr=[];
  if(obj.getElementsByClassName){
    return obj.getElementsByClassName(classname);
  }else{
    var all=obj.getElementsByTagName("*");
    for (var i=0; i<all.length; i++) {
		  if(checkClass(all[i].className,classname)){
		    arr.push(all[i]);
		  }
    }
	return arr;
  }
}
function checkClass (oldclass,newclass) {
 var arr=oldclass.split(" ");
 for (var i=0; i<arr.length; i++) {
	   if(arr[i]==newclass){
	     return true;
	   }
 }
 return false;
}