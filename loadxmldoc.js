function loadXMLDoc(dname)
{
if (window.XMLHttpRequest)
  {
  xhttp=new XMLHttpRequest();
  }
else
  {
  xhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  
xhttp.onreadystatechange=function()
  {
  if (xhttp.readyState==4 && xhttp.status==200)
    {
    document.getElementsByTagName("movie");    }
  }
  
  
xhttp.open("POST",dname,false);
xhttp.send();
xhttp.onreadystatechange
return xhttp.responseXML;
}