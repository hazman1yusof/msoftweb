var delorddateObj = new setdelorddate(["#delorddate"]);
delorddateObj.getdata().set();
function setdelorddate(target){
	this.delorddateopen=[];
	this.purdate;
	this.invdate;
	this.target=target;
	this.param={
		action:'get_value_default',
		field: ['*'],
		table_name:'finance.fatemp',
		table_id:'idno'
	}

	this.getdata = function(){
		var self=this;
		$.get( "../../../../assets/php/entry.php?"+$.param(this.param), function( data ) {
			
		},'json').done(function(data) {
			if(!$.isEmptyObject(data.rows)){
				self.purdate = data.rows[0]["#purdate"];
				self.invdate = data.rows[data.rows.length-1]["#invdate"];
				data.rows.forEach(function(element){
					$.each(element, function( index, value ) {
						if(index.match('#delorddate') && value == 'O'){
							self.delorddateopen.push({
								from:element["#purdate"+index.match(/\d+/)[0]],
								to:element["#invdate"+index.match(/\d+/)[0]]
							})
						}
					});
				});
			}
		});
		return this;
	}

	this.set = function(){
		this.target.forEach(function(element){
			$(element).on('delorddate',validate_delorddate);
		});
	}

	function validate_delorddate(obj){
		var permission = false;
		delorddateObj.delorddateopen.forEach(function(element){
			 if(moment(obj.target.value).isAfter(element.from,element.to, null, '[]')) {
				permission=true
			}else{
				(permission)?permission=true:permission=false;
			}
		});
		if(!moment(obj.target.value).isAfter(delorddateObj.purdate,delorddateObj.invdate)){
			bootbox.alert('Invalid');
			$(obj.currentTarget).val('').addClass( "error" ).removeClass( "valid" );
		}else if(!permission){
			bootbox.alert('Accounting Period Has been Closed');
			$(obj.currentTarget).val('').addClass( "error" ).removeClass( "valid" );
		}		
	}

}