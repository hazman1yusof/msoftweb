
$(document).ready(function () {

	function emptyFormdata(errorField,form,except=[]){
		var temp=[];
		except.push(form+' input[name="_token"]');
		if(except!=null){
			$.each(except, function( index, value ) {
				temp.push($(value).val());
			});
		}
		errorField.length=0;
		$(form).trigger('reset');
		$(form+' input[type=hidden]').val('');
		$(form+' .help-block').html('');
		if(except!=null){
			$.each(except, function( index, value ) {
				$(value).val(temp[index]);
			});
		}
	}

	$('#closemodalfp').click(function(){
		emptyFormdata([],"form#myform");
        $("img#image").attr('src',$("img#image").attr("defaultsrc"));
        $("img#fingerprint").attr('src',$("img#fingerprint").attr("defaultsrc"));
		if (window.frameElement) {
			parent.closemodalfp();
		}
	});

	$('#readmykad').click(function(){
		chg_msg('read','Processing.. Please Wait..');

		$('.ui.basic.modal#read').modal({closable: false,transition:{
		    showMethod   : 'fade',
		    showDuration : 200,
		    hideMethod   : 'fade',
		    hideDuration : 200,}
		}).modal('show');

		$.get( "http://localhost:2020/BioPakWeb/v2/readMyKad?EnablePhoto=true&ShowSplash=false&PhotoOnly=false&ValidateCard=false")
		  .done(function( data ) {
		  	var msg = data.StatusMessage;
		  	var StatusCode = data.StatusCode;
		  	if(StatusCode!="0"){
		  		chg_msg('fail',msg);

				delay(function(){
		  			$('.ui.basic.modal#read').modal('hide');
				}, 1000 );
		  	}else{
		  		
		  		var ret = data.Data;
			    $("input[name='name']").val(ret.GMPCName);
			    $("input[name='icnum']").val(ret.IDNumber);
			    $("input[name='gender']").val(ret.Gender);
			    $("input[name='dob']").val(ret.BirthDate);
			    $("input[name='birthplace']").val(ret.BirthPlace);
			    $("input[name='race']").val(ret.Race);
			    $("input[name='citizenship']").val(ret.Citizenship);
			    $("input[name='religion']").val(ret.Religion);
			    $("input[name='address1']").val(ret.Address1);
			    $("input[name='address2']").val(ret.Address2);
			    $("input[name='address3']").val(ret.Address3);
			    $("input[name='city']").val(ret.City);
			    $("input[name='state']").val(ret.State);
			    $("input[name='postcode']").val(ret.Postcode);
			    $("img#image").attr('src','data:image/png;base64,'+ret.Picture);
			    // $("img#leftfp").attr('src','data:image/jpeg;base64,'+ret.LeftFinger);
			    // $("img#rightfp").attr('src','data:image/jpeg;base64,'+ret.RightFinger);

				var objdata = {
					'type' : 'mykad',
					'_token': $("input#_token").val(),
	                'name' : ret.GMPCName,
	                'icnum' : ret.IDNumber,
	                'gender' : ret.Gender,
	                'dob' : ret.BirthDate,
	                'birthplace' : ret.BirthPlace,
	                'race' : ret.Race,
	                'citizenship' : ret.Citizenship,
	                'religion' : ret.Religion,
	                'address1' : ret.Address1,
	                'address2' : ret.Address2,
	                'address3' : ret.Address3,
	                'city' : ret.City,
	                'state' : ret.State,
	                'postcode' : ret.Postcode,
	                'base64' : ret.Picture
	            }

				if (window.frameElement) {
	            	parent.populatefromfp(objdata);
				}

	            // $.post( "./mykadfp_store",objdata, function( data ) {
	            //     $('#overlay').fadeOut();
	            // });

	            scanthumb();
		  	}
		  	

		}).fail(function() {
	  		chg_msg('fail', "Service not installed");
	  		
			delay(function(){
	  			$('.ui.basic.modal#read').modal('hide');
			}, 1000 );

  		});
	});

	function scanthumb(){
		chg_msg('fp',"Put thumbprint <br> into biometric scanner");

		$.get( "http://localhost:2020/BioPakWeb/v2/matchMyKadFP?Timeout=10&FFDLevel=2&ShowSplash=false&Bitmap=true&Template=false")
		  .done(function( data ) {
		  	var msg = data.StatusMessage;
		  	var StatusCode = data.StatusCode;
		  	if(StatusCode != "0"){
		  		chg_msg('fail',msg);

				delay(function(){
		  			$('.ui.basic.modal#read').modal('hide');
				}, 1000 );
		  	}else{
			    $("img#fingerprint").attr('src','data:image/jpeg;base64,'+data.Data.Bitmap);

				chg_msg('success',"Success - "+msg);

				delay(function(){
		  			$('.ui.basic.modal#read').modal('hide');
				}, 1000 );
		  	}

		}).fail(function() {
	  		chg_msg('fail', "Service not installed");
	  		
			delay(function(){
	  			$('.ui.basic.modal#read').modal('hide');
			}, 1000 );

  		});

	}

	function PrintElem(elem){
	    var mywindow = window.open('', 'PRINT', 'height=400,width=600');

	    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
	    mywindow.document.write('</head><body >');
	    mywindow.document.write('<h1>' + document.title  + '</h1>');
	    mywindow.document.write(document.getElementById(elem).innerHTML);
	    mywindow.document.write('</body></html>');

	    mywindow.document.close(); // necessary for IE >= 10
	    mywindow.focus(); // necessary for IE >= 10*/

	    mywindow.print();
	    mywindow.close();

	    return true;
	}

	$('#download').click(function(){
		$("form#myform").printThis();
	})

	function chg_msg(state,msg){
		if(state == 'fail'){
			$('i.green,i.yellow,i.violet').hide();
			$('i.red').fadeIn();

			$('span#msg').html(msg);
		}else if(state == 'success'){
			$('i.red,i.yellow,i.violet').hide();
			$('i.green').fadeIn();

			$('span#msg').html(msg);
		}else if(state == 'fp'){
			$('i.red,i.yellow,i.green').hide();
			$('i.violet').fadeIn();

			$('span#msg').html(msg);
		}else{
			$('i.red,i.green,i.violet').hide();
			$('i.yellow').fadeIn();

			$('span#msg').html(msg);
		}
	}

});

var delay = (function(){
	var timer = 0;
	return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	};
})();