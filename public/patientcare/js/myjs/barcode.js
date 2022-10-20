
function gen_barcode(token,but_print_dtl){
	this.token = token;
	this.btn = but_print_dtl;

	this.init = function(){
		let self=this;
		$(this.btn).click(function(){
			// console.log(self);
			self.print();
		});
	}

	this.print = function(){
		let obj = {
			_token:$(this.token).val(),
			recno:$(this.btn).data('recno')
		}

		$.post('/barcode/print',obj, function( data ) {
				
		}).fail(function(data) {
			
		}).success(function(data){
			
		});	
	}

}