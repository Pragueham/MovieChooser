<?php 

error_reporting(-1);
// Check for post
if($_SERVER['REQUEST_METHOD'] == "POST") {
//get post 
$thisMovie = $_POST['thisMovie'];
$nextMovie = $_POST['nextMovie'];
$dRating = $_POST['dRating'];
$cRating = $_POST['cRating'];
$watchDate = $_POST['watchDate'];

//Confirm message
echo '<p>Good show watching ' . $thisMovie . '!</p>';
//Updates current movie to movie selected by previous page
function updateWatching($nextMovie) {
// load the document
// the root node is <info/> so we load it into $info
$movielist = simplexml_load_file('watching.xml');
// update
$movielist->movie->title = $nextMovie;
// save the updated document
$movielist->asXML('watching.xml');
//clears stuff up
unset($movielist);
}

//Removes watched movie from original list
function updateOld($thisMovie) {
$dom = new DOMDocument;
$dom->load('movielist.xml');
$xpath = new DOMXPath($dom);
//looks for movie name in old list
$query = sprintf('/movielist/movie[./title = "%s"]', $thisMovie);
//removes old movie 
foreach($xpath->query($query) as $record) {
    $record->parentNode->removeChild($record);
}
$dom->saveXml();
$dom->save("movielist.xml");
}

//Save watched movie details
function updateNew($thisMovie, $dRating, $cRating, $watchDate) {
$xml = simplexml_load_file('watched-list.xml');
$watched = $xml->addChild('movie');
$watched->addChild('title', $thisMovie);
$watched->addChild('drating', $dRating);
$watched->addChild('crating', $cRating);
$watched->addChild('datewatched', $watchDate);

file_put_contents('watched-list.xml', $xml->asXML());
unset($xml);
}
//run functions
updateWatching($nextMovie);
updateOld($thisMovie);
updateNew($thisMovie, $dRating, $cRating, $watchDate);	
}
?>
<html>
<head>
<link type="text/css" href="css/custom-theme/jquery-ui-1.8.18.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
		<script type="text/javascript">
			$(function(){

				// Datepicker
				$('#datepicker').datepicker({
					inline: true
				});
				
				//hover states on the static widgets
				$('#dialog_link, ul#icons li').hover(
					function() { $(this).addClass('ui-state-hover'); }, 
					function() { $(this).removeClass('ui-state-hover'); }
				);
				
			});
		</script>
<style>
/* http://meyerweb.com/eric/tools/css/reset/ 
   v2.0 | 20110126
   License: none (public domain)
*/

html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section {
	display: block;
}
body {
	line-height: 1;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}

/* Don't use this font if you don't own the license… */
@font-face { 
	font-family: HNC; 
	src: url('HelveticaNeueLTStd-BdCn.otf'); 
}


h1 {
	position: relative; 
	top: -50%; 
	font-family: HNC, sans-serif; 
	font-size: 55px; 
	line-height:1.614em; 
	text-transform: uppercase;
}
h2 {
 	font-family: Helvetica, Arial, sans-serif; 
 	font-weight:light !important;
 	font-size: 30px; 
 	line-height:1.614em; 
 	padding-left: 20px; 
 	color:#000;
}
p {
	font-family: Helvetica, Arial, sans-serif; 
	font-size: 16px; 
	line-height:1.614em; 
	padding-left: 20px;
	margin-top:17px;
}
a {
	text-decoration:none;
}
br {
	clear:both;
}
.button {
	-moz-border-radius: 6px; 
	border-radius: 6px; 
	background-color:#000; 
	padding:10px; 
	margin-left:20px; 
	margin-top:10px;
	display:inline;
	color:#fff; 
	font-family: Helvetica, Arial, sans-serif; 
	font-size: 16px; 
	line-height:1.614em;
}

.outercontainer {
	height:168px; 
	min-height:168px; 
	width:756px; 
	padding: 10px; 
	border-left:10px 
	solid #FFC200;
	display: table;
	overflow: hidden;
}

.innercontainer { 
	display: table-cell; 
	vertical-align: middle;
	width: 100%;
	margin: 0 auto;
}

/*here be ratings code */
legend {
	font-family: Helvetica, Arial, sans-serif; 
	font-size: 16px; 
	line-height:1.614em; 
	padding-left: 10px;
}

.rating {
    float:left;
    border: 0;
    padding-left: 10px;
}

/* :not(:checked) is a filter, so that browsers that don’t support :checked don’t 
   follow these rules. Every browser that supports :checked also supports :not(), so
   it doesn’t make the test unnecessarily selective */
.rating:not(:checked) > input {
    position:absolute;
    top:-9999px;
    clip:rect(0,0,0,0);
}

.rating:not(:checked) > label {
    float:right;
    width:.9em;
    padding:0.1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:200%;
    line-height:1.2;
    color:#000;
}

.rating:not(:checked) > label:before {
    content: '\2605 ';
}

.rating > input:checked ~ label {
    color: #FFC200;

}

.rating:not(:checked) > label:hover,
.rating:not(:checked) > label:hover ~ label {
    color: gold;

}

.rating > input:checked + label:hover,
.rating > input:checked + label:hover ~ label,
.rating > input:checked ~ label:hover,
.rating > input:checked ~ label:hover ~ label,
.rating > label:hover ~ input:checked ~ label {
    color: #FFC200;

}

.rating > label:active {
    position:relative;
    top:2px;
    left:2px;
}
</style>

<script type="text/javascript" src="loadxmldoc.js" /> </script>

<script type="text/javascript"> 
//Select random new movie to watch next (Why here? Don't ask)
//Get new movie xml
xmlDoc=loadXMLDoc("/MovieChooser/movielist.xml");
i=xmlDoc.getElementsByTagName("title");
// Select a random film from list
var movieCount = i.length;
var movieChoice = (Math.floor(Math.random()*movieCount));
//The first 40 films should be watched in order.  If you don't want to do this, remove this if then stuff.
if (movieCount < 425) {
	x=xmlDoc.getElementsByTagName("title")[movieChoice].childNodes[0];
} else {
	x=xmlDoc.getElementsByTagName("title")[1].childNodes[0];
}
	var nextMovie = x.nodeValue;
	
window.onload = thisMovie(movieChoice);

//Gets current movie
function thisMovie(movieChoice) {	
		watchDoc=loadXMLDoc("/MovieChooser/watching.xml?c=" + movieChoice);
		var watchMovie = watchDoc.getElementsByTagName("title")[0].childNodes[0];
		return watchMovie.nodeValue;
}
//Creates hidden form to post details to XML
function post_to_url(cRating, dRating, watchDate) {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "/MovieChooser/index.php");

            var thisHiddenField = document.createElement("input");
            thisHiddenField.setAttribute("type", "hidden");
            thisHiddenField.setAttribute("name", "thisMovie");
            thisHiddenField.setAttribute("value", thisMovie());
            form.appendChild(thisHiddenField);
            
			var nextHiddenField = document.createElement("input");
            nextHiddenField.setAttribute("type", "hidden");
            nextHiddenField.setAttribute("name", "nextMovie");
            nextHiddenField.setAttribute("value", nextMovie);
			form.appendChild(nextHiddenField);
            
			var cRatingHiddenField = document.createElement("input");
            cRatingHiddenField.setAttribute("type", "hidden");
            cRatingHiddenField.setAttribute("name", "cRating");
            cRatingHiddenField.setAttribute("value", cRating);
            form.appendChild(cRatingHiddenField);
            
			var dRatingHiddenField = document.createElement("input");
            dRatingHiddenField.setAttribute("type", "hidden");
            dRatingHiddenField.setAttribute("name", "dRating");
            dRatingHiddenField.setAttribute("value", dRating);
            form.appendChild(dRatingHiddenField);
            
            var watchDateHiddenField = document.createElement("input");
            watchDateHiddenField.setAttribute("type", "hidden");
            watchDateHiddenField.setAttribute("name", "watchDate");
            watchDateHiddenField.setAttribute("value", watchDate);
            form.appendChild(watchDateHiddenField);
            
    document.body.appendChild(form);
   	form.submit();
}

// Validate form and get radio button values to post_to_rul
function get_radio_value() {

            var cInputs = document.getElementsByName("crating");
            cRatingScore = function() {
	            for (var i = 0; i < cInputs.length; i++) {
	              if (cInputs[i].checked) {
					return cInputs[i].value;
	              }
	            }
            }
           	var cRating = cRatingScore();
            var dInputs = document.getElementsByName("drating");
            dRatingScore = function() {
	            for (var i = 0; i < dInputs.length; i++) {
	              if (dInputs[i].checked) {
	              	return dInputs[i].value;
	              }
	            }
            }
            var dRating = dRatingScore();
			var watchDate = document.getElementById("datepicker");

			
           	if (cRating != null && dRating != null && watchDate.value != '') {
           		
            	post_to_url(cRating, dRating, watchDate.value);
            } else {
           		alert("YOU SUCK!");
          	}
          }
	function toggle() {
		var ele = document.getElementById("toggleText");
		var text = document.getElementById("displayText");
		if(ele.style.display == "block") {
	    		ele.style.display = "none";
			text.innerHTML = "<a id='displayText' href='javascript:toggle();'><div class='button'>We've watched it!</div></a>";
	  	}
		else {
			ele.style.display = "block";
			text.innerHTML = "<h2>Awesome. What did you think?</h2>";
		}
	}  

</script>
</head>
<body style="margin:50px;">
<p>The next movie you should watch is...</p>

<div class="outercontainer">
	<div class="innercontainer">
		<h1>
			<script type="text/javascript"> 
			document.write(thisMovie());
			</script>
		</h1>
	</div>
</div>
<br />
<a id="displayText" href="javascript:toggle();"><div class="button">We've watched it!</div></a>
<br style="clear:both;" />
<div id="toggleText" style="display: none">
<fieldset class="rating">
    <legend>Dylan's rating:</legend>
    <input type="radio" id="dstar5" name="drating" value="5" /><label for="dstar5" title="Rocks!">5 stars</label>
    <input type="radio" id="dstar4" name="drating" value="4" /><label for="dstar4" title="Pretty good">4 stars</label>
    <input type="radio" id="dstar3" name="drating" value="3" /><label for="dstar3" title="Meh">3 stars</label>
    <input type="radio" id="dstar2" name="drating" value="2" /><label for="dstar2" title="Kinda bad">2 stars</label>
    <input type="radio" id="dstar1" name="drating" value="1" /><label for="dstar1" title="Sucks big time">1 star</label>
</fieldset>
<fieldset class="rating" style="margin-left:66px;">
    <legend>Clare's rating:</legend>
    <input type="radio" id="cstar5" name="crating" value="5" /><label for="cstar5" title="Rocks!">5 stars</label>
    <input type="radio" id="cstar4" name="crating" value="4" /><label for="cstar4" title="Pretty good">4 stars</label>
    <input type="radio" id="cstar3" name="crating" value="3" /><label for="cstar3" title="Meh">3 stars</label>
    <input type="radio" id="cstar2" name="crating" value="2" /><label for="cstar2" title="Kinda bad">2 stars</label>
    <input type="radio" id="cstar1" name="crating" value="1" /><label for="cstar1" title="Sucks big time">1 star</label>
</fieldset>
<script>
$(document).ready(function () {
	$(function() {
		$( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
	});
	});
	</script>


<br />
<p>When did you watch it?<br />
<input type="text" id="datepicker" style="border:1px solid #333; font-size: 16px; margin-bottom:17px;"></p>

<br />
<a href="javascript:get_radio_value();"><div class="button">Pick us a new movie!</div></a>
</div>

</body>
</html>


