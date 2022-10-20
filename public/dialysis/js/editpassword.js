$(document).ready(function() {
	$('.ui.checkbox')
	  .checkbox()
	;
	$('#showpwd').click(function(){
      if(!$(this).hasClass('slash')){
        $(this).addClass('slash');
        $('#inputPassword').attr('type','text');
      }else{
        $(this).removeClass('slash');
        $('#inputPassword').attr('type','password');
      }
    });

  $('#showpwd2').click(function(){
      if(!$(this).hasClass('slash')){
        $(this).addClass('slash');
        $('#inputPassword2').attr('type','text');
      }else{
        $(this).removeClass('slash');
        $('#inputPassword2').attr('type','password');
      }
    });
});