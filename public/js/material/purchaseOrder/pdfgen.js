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
				style: 'header',
				text: [
					'Purchase Order Header'
					]
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
		    },{
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
					]
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