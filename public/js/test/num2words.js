
$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {
	$("body").show();

$('#num').focus();
//$('#demo').num2words();
$('#demo').num2words({
  units: [ "", "One", "Two", "Three", "Four", "Five", "Six","Seven", "Eight", "Nine", "Ten" ],
  teens: [ "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen","Sixteen", "Seventeen", "Eighteen", "Nineteen", "Twenty" ],
  tens: [ "", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty","Seventy", "Eighty", "Ninety" ],
  othersIntl: [ "Thousand", "Million", "Billion", "Trillion" ]
});

});