$.validate({
	modules: 'sanitize',
	language: {
		requiredFields: ''
	},
});

var errorField = [];
conf = {
	onValidate: function ($form) {
		if (errorField.length > 0) {
			return [{
				element: $('#'+$form.attr('id')+' input[name='+errorField[0]+']'),
				message: ' '
			}]
		}
	},
};

$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';

$(document).ready(function () {

	$('button#repost').click(function(){
		let serializedForm = trimmall('#formdata',false);
		$.post( './webservice/form?', serializedForm , function( data ) {
			
		}).fail(function(data) {
			alert('fail');
		}).success(function(data){
			alert('success');
		});
		
	});
});