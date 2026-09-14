$(document).ready(function () {
	$("#dialog_qrgen")
	  .dialog({
		width: 5/10 * $(window).width(),
		modal: true,
		autoOpen: false,
		open: function( event, ui ) {

		},
		close: function( event, ui ) {

		}
	  });

    $('#btn_qrpreepis').click(function(){
    	let param = {
	        qrurl: 'qrcode',
	    }
	    
	    let newurl = './qrcode_gen'+"?"+$.param(param);
        let cururl = $('iframe#qrgen_iframe').attr('src');
	    
	    if(newurl != cururl){
	        $("iframe#qrgen_iframe").attr('src',newurl);
	    }
        $('#dialog_qrgen').dialog('open');
    });
});