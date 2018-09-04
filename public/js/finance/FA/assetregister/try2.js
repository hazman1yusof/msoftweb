			function getinvdate(document){
				var param={
					action:'get_value_default',
					field:['actdate'],
					table_name:'finance.fatemp',
					table_id:'auditno',
					filterCol:['document'],
                    filterVal:[document],
                    
                    action:'get_value_default',
					field: ['*'],
					table_name:'finance.fatemp',
					table_id:'idno'
				}
				$.get( "../../../../assets/php/entry.php?"+$.param(param), function( data ) {
						
					},'json').done(function(data) {
						if(!$.isEmptyObject(data)){
							$('#invdate').val(moment(data.rows[0].actdate).format("YYYY-MM-DD"));
						}
					});
			}		