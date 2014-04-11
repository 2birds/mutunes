function thanks() {
  var thankYou = "You have contributed!\nI am eternally grateful.\nIf you haven't done so already, leave your details to enter a prize draw!";
  alert(thankYou);
}
// Code from..
// http://www.codeproject.com/Tips/492632/Email-Validation-in-JavaScript 

function checkSelected(){
    if(document.comparison.choice[0].checked || document.comparison.choice[1].checked) {
  var thankYou = "You have contributed!\nI am eternally grateful.\nIf you haven't done so already, leave your details to enter a prize draw!";
	//alert(thankYou);
	return true;
    }else{
	alert("You haven't made a choice! Don't leave us hanging!");
	return false;
    }
}

function selectThis(event) {
    event.target.children.namedItem('choice').checked = true;
}

function selectThisSiblings(event) {
    event.target.parentNode.children.namedItem('choice').checked = true;
}

function checkEmail() {

    //alert('Checking email..');
    var email = document.getElementById('contributorEmail');
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if (!filter.test(email.value)) {
	//alert('Please provide a valid email address');
	alert('There\'s something fishy about your email address. Please try again.');
	email.focus;
	return false;
    }
}

function setSpeed(amount)
{ 
    for(var i = 0; i < 2; i++)
    {
	var myAudio = document.getElementsByTagName('audio')[i]; 
	myAudio.playbackRate = amount;
	myAudio.defaultPlaybackRate = amount;
    }
    document.getElementById('speedcache').value = amount.toFixed(1);
}

function changeSpeed(amount)
{ 
    for(var i = 0; i < 2; i++)
    {
	var myAudio = document.getElementsByTagName('audio')[i]; 
	var newVal = myAudio.playbackRate + amount;
	myAudio.playbackRate = newVal;
	myAudio.defaultPlaybackRate = newVal;
    }
    document.getElementById('speedValue').innerHTML = newVal.toFixed(1);
    document.getElementById('speedcache').value = newVal.toFixed(1);
}

function playFast() 
{ 
    changeSpeed(0.1);
}

function playSlow() 
{
    changeSpeed(-0.1);
}