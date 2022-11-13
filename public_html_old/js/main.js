function switchPage(page) {
  window.location.href = page; //"http://www.w3schools.com";
}

function pa() {
  console.log(document.getElementbyID('pORa'));
  return document.getElementbyID('pORa').value;
}

function dr() {
  return document.getElementbyID('dORr').value;
}

function cookify() {
  var e = document.getElementById("sel1");
  var strUser = e.options[e.selectedIndex].text;
  $("#sel").load(location.href + " #sel");
    	  
}