			function getdelorddate(data){
				var param={
					action:'get_value_default',
					field:['delorddate'],
					table_name:'finance.fatemp',
					table_id:'idno',
				}
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
						
					},'json').done(function(data) {
						if(!$.isEmptyObject(data)){
							$('#invdate').val(moment(data.rows[0].actdate).format("YYYY-MM-DD"));
						}
					});
			}	
    var delorddate = new Date();
    
    var minDate = new Date("#purdate");
    var maxDate =  new Date("#invdate");

    if (delorddate > minDate && delorddate < maxDate ){
         alert("Invalid date")
    }
    else{
        alert("Good")
    }