jQuery(document).ready(function($) {
	var apiKey = 'keylr1COV58UiXp9r';
	var appID = 'appm9rCl4jFRtRASj';

	var Airtable = require('airtable');
	Airtable.configure({
    endpointUrl: 'https://api.airtable.com',
    apiKey: apiKey
	});
	var base = Airtable.base(appID);

	base('Butterflies').find('recaWuT5HnokQK3AM', function(err, record) {
    if (err) { console.error(err); return; }
    console.log(record);
	});
});