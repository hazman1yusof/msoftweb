
$(document).ready(function () {
	$("body").show();


	$('#submit').click(function(){

		$.post(  '/test/form',$( "#testform" ).serialize(), function( data ) {
			
		}).fail(function(data) {
			
		}).success(function(data){
			
		});

	});

});