 function generatePDF(anchor,formdataHeader,gridname){
 	this.anchor = anchor;
 	this.formdataHeader = formdataHeader;
 	this.gridname = gridname;

 	function getval_from_name(array,name){
 		var retval='N/A';
 		array.forEach(function(element){
			if(element.name == name){
				retval = element.value;
			}
		});
		return retval;
 	}

 	function get_desc(name){
 		return $('#'+name).parent().next().text();
 	}

 	function getval_from_grid(grid,selarray){
 		var $grid = $(grid), rows = $grid[0].rows, cRows = rows.length, iRow, row, cellsOfRow, temp=[];
		temp.push(selarray);
		for (iRow = 0; iRow < cRows; iRow++) {
			row = rows[iRow];
			if ($(row).hasClass("jqgrow")) {
				cellsOfRow = row.cells;
				var temparray = [];
				selarray.forEach(function(element){
					let index_to_del = $(cellsOfRow[element.jq_index]).html().search('<span class="help-block">');
					if(index_to_del != -1){ //this fix is for not including help-block at jqgrid2
						temparray.push($(cellsOfRow[element.jq_index]).html().slice(0,index_to_del));
					}else{
						temparray.push($(cellsOfRow[element.jq_index]).text());
					}
				});
				temp.push(temparray);
			}
		}

		return temp;
 	}

	

	function get_pdf_dataURL(event){
		var header_data = $(event.data.data.formdataHeader).serializeArray();
		var gridname = event.data.data.gridname;
		var table_array = getval_from_grid(gridname,[	//get the index from jqgrid start with 0
								{text: 'Price Code', style: 'tableHeader', jq_index: 3},
								{text: 'Item Code', style: 'tableHeader', jq_index: 4},
								{text: 'Item Description', style: 'tableHeader', jq_index: 5},
								{text: 'Quantity Order', style: 'tableHeader', jq_index: 8},
								{text: 'Unit Price', style: 'tableHeader', jq_index: 9},
								{text: 'Tax Code', style: 'tableHeader', jq_index: 10},
								{text: 'Total GST Amount', style: 'tableHeader', jq_index: 12},
								{text: 'Total Line Amount.', style: 'tableHeader', jq_index: 13}

							]);

		docDefinition = {
			pageSize: 'A4',
		  	content: [
		  	{
		  		image: 'data:image/png;base64,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',
				width: 100,
	            height: 100,
	            alignment: 'center'		  	
		  	},
			{ 
				style: 'header',
				text: [
					'Purchase Order'
					],
					alignment: 'center'
				
			},{
		      style:'colmargin',
		      columns: [
					{ width: 80, text: 'Purchase Department: ', bold: true },
					{ width: 200, text: get_desc('purordhd_prdept')+' ( '+getval_from_name(header_data,'purordhd_prdept')+' )' },
					{ width: 80, text: 'PO No: ', bold: true },
					{ width: 50, text: getval_from_name(header_data,'purordhd_purordno') },
					{ width: 80, text: 'Record No: ', bold: true },
					{ width: 50, text: getval_from_name(header_data,'purordhd_recno') },
				],
		    },{
		      style:'colmargin',
		      columns: [
					{ width: 80, text: 'Delivery Department: ', bold: true },
					{ width: 200, text: get_desc('purordhd_deldept')+' ( '+getval_from_name(header_data,'purordhd_deldept')+' )' },
					{ width: 80, text: 'Req Dept: ', bold: true },
					{ width: 50, text: getval_from_name(header_data,'purordhd_reqdept') },
					{ width: 80, text: 'Req No: ', bold: true },
					{ width: 50, text: getval_from_name(header_data,'purordhd_purreqno') },
		      ],
		    },
		    {canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 1 }]},
		    {
		      style:'colmargin',
		      columns: [
					{ width: 80, text: 'Supplier Code: ', bold: true },
					{ width: 200, text: get_desc('purordhd_suppcode')+' ( '+getval_from_name(header_data,'purordhd_suppcode')+' )' },
					{ width: 80, text: 'Creditor: ', bold: true },
					{ width: 50, text: getval_from_name(header_data,'purordhd_credcode') },
		      ],
		      
		    },
		    {canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 1 }]},
		    {
		      style:'colmargin',
		      columns: [
					{ width: 80, text: 'PO Date: ', bold: true },
					{ width: 100, text: getval_from_name(header_data,'purordhd_purdate') },
					{ width: 80, text: 'Expected Date: ', bold: true },
					{ width: 100, text: getval_from_name(header_data,'purordhd_expecteddate') },
					{ width: 80, text: 'Payment Terms: ', bold: true },
					{ width: 100, text: getval_from_name(header_data,'purordhd_termdays') },
		      ],
		    },
		    {canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 1 }]},
		    {
		      style:'colmargin',
		      columns: [
		      		{ width: 80, text: 'Discount (%): ', bold: true },
					{ width: 100, text: getval_from_name(header_data,'purordhd_perdisc') },
					{ width: 80, text: 'Amount Discount: ', bold: true },
					{ width: 100, text: getval_from_name(header_data,'purordhd_amtdisc') },
					{ width: 80, text: 'Record Status: ', bold: true },
					{ width: 100, text: getval_from_name(header_data,'purordhd_recstatus') },
					
		      ],
		    },{
		      style:'colmargin',
		      columns: [
					{ width: 80, text: 'Sub Amount: ', bold: true },
					{ width: 100, text: getval_from_name(header_data,'purordhd_subamount') },
					{ width: 80, text: 'Total Amount: ', bold: true },
					{ width: 100, text: getval_from_name(header_data,'purordhd_totamount') },
					{ width: 80, text: 'Tax Claim: ', bold: true },
					{ width: 100, text: getval_from_name(header_data,'purordhd_taxclaimable') },
					
		      ],
		    },
		    {canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 1 }]},
		    {
		      style:'colmargin',
		      columns: [
					{ width: 120, text: 'Remark: ', bold: true },
					{ width: 450, text: getval_from_name(header_data,'purordhd_remarks') },
		      ],
		    },{ 
				style: 'headerDetail',
				text: [
					'Purchase Order Detail'
					],
				alignment: 'center'	
			},{
					style: 'tableExample',
					table: {
						headerRows: 1,
						widths: ['auto','auto','auto','auto','auto','auto','auto','auto'],//panjang standard dia 515
						body: 
							table_array ///from jqgrid2
						
					},
					layout: 'lightHorizontalLines'
				},
			
		  ],
		  styles: {
				header: {
					fontSize: 15,
					bold: true,
					alignment: 'right',
					margin: [0,0,0,20]		
				},
				headerDetail: {
					fontSize: 15,
					bold: true,
					alignment: 'right',
					margin: [0,50,0,20]		
				},
				tableExample: {
					margin: [0, 5, 0, 15]
				},
				colmargin: {
					margin: [0, 5, 0, 5]
				}
			},
			defaultStyle: {
				fontSize: 10,
			}
		};
		pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
			var win = window.open("", "_blank", "resizable=yes, scrollbars=yes, titlebar=yes, width=700, height=700, top=10, left=10");
			win.document.write('<iframe width="100%" height="100%" src="'+dataURL+'" frameborder="0"></iframe>')
		});
	}
	this.printEvent=function(){
		$(this.anchor).on('click',{data:this},get_pdf_dataURL);
	}
 }