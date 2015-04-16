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