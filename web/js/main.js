/*

var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substrRegex;
 
    // an array that will be populated with substring matches
    matches = [];
 
    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');
 
    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        // the typeahead jQuery plugin expects suggestions to a
        // JavaScript object, refer to typeahead docs for more info
        matches.push({ value: str });
      }
    });
 
    cb(matches);
  };
};
 
var states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
  'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
  'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
  'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
  'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
  'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
  'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
  'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
  'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
];
 
$('#keyword').typeahead({
  hint: true,
  highlight: true,
  minLength: 1
},
{
  name: 'states',
  displayKey: 'value',
  source: substringMatcher(states)
});



$('#location').typeahead({
    source: function (query, process) {
        return $.get('http://getjobingermany.skyresource.com/complete-location/', { locationPart: query }, function (data) {
            return process(data.locations);
        });
    }
});


    $('#location').typeahead({
        source: function (query, process) {
            return $.get('http://getjobingermany.skyresource.com/app_dev.php/complete-location/' + query, function (data) {
                return process(data.locations);
            });
        }
    });
    
*/

var keywordJobs = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
/*  prefetch: 'http://getjobingermany.skyresource.com/app_dev.php/complete-location/', */
  remote: 'http://getjobingermany.com/complete-job/%QUERY'
});
 
keywordJobs.initialize();
 
$('#keyword').typeahead(null, {
  name: 'keyword-jobs',
  displayKey: 'title',
  source: keywordJobs.ttAdapter()
});

var bestPictures = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('city'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
/*  prefetch: 'http://getjobingermany.skyresource.com/app_dev.php/complete-location/', */
  remote: 'http://getjobingermany.com/complete-location/%QUERY'
});
 
bestPictures.initialize();
 
$('#location').typeahead(null, {
  name: 'best-pictures',
  displayKey: 'city',
  source: bestPictures.ttAdapter()
});