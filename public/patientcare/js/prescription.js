$(document).ready(function() {

    $('table.basic input[type="checkbox"]').click(function(){
    	var checked = $(this).is(':checked');
    	var id = $(this).data('id');
    	if(checked){
    		$('#card_'+id).slideDown();
    	}else{
    		$('#card_'+id).slideUp();
    	}
    });

} );