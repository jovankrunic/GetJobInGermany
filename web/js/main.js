var urlKeyword = Routing.generate('GetJobInGermanyBundle_completeJob', {keywordPart: 'WILDCARD'});

var keywordJobs = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  remote: {
            url: urlKeyword,
            wildcard: 'WILDCARD'
        }
});
 
keywordJobs.initialize();
 
$('#keyword').typeahead(null, {
  name: 'keyword-jobs',
  displayKey: 'title',
  source: keywordJobs.ttAdapter()
});

var urlLocation = Routing.generate('GetJobInGermanyBundle_completeLocation', {locationPart: 'WILDCARD'});

var bestPictures = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('city'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  remote: {
            url: urlLocation,
            wildcard: 'WILDCARD'
        }
});
 
bestPictures.initialize();
 
$('#location').typeahead(null, {
  name: 'location-jobs',
  displayKey: 'city',
  source: bestPictures.ttAdapter()
});

// With JQuery
$("#timeLimitVal").slider({
	formatter: function(value) {
		return value;
	}                 
}, initializeFields());

/*
$(document).ready(function() {
  initializeFields();
});
*/

$(function() {
  $("body").fadeIn(220);
  
  $('#to-top').click(function(e){
    e.preventDefault();
    $('body').animate({
        scrollTop: 0
    }, 2000);
  });
});

$("#timeLimitVal").on("change", function(changeEvt) {
	$("#timeLimitValText").text(changeEvt.value.newValue);
});

$("#useTimeLimit").click(function() {
	if(this.checked) {
		// With JQuery
		$("#timeLimitVal").slider("enable");
    $("#timeLimitVal").attr("disabled", false);
	}
	else {
		// With JQuery
		$("#timeLimitVal").slider("disable");
    $("#timeLimitVal").attr("disabled", true);
	}
});


function initializeFields() {
  timeLimitValText = ($("#timeLimitVal").val()!= "")?$("#timeLimitVal").val():45;
  $("#timeLimitValText").text(timeLimitValText);
}

/*
function isSliderEnabled() {
  if ($("#useTimeLimit").checked) {
    $("#timeLimitVal").slider("enable");
  }
  	else {
  		// With JQuery
  		$("#timeLimitVal").slider("disable");
    }
}
*/