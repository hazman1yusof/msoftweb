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
					temparray.push($(cellsOfRow[element.jq_index]).text());
				});
				temp.push(temparray);
			}
		}

		return temp;
 	}

	function get_pdf_dataURL(event){
		var header_data = $(event.data.data.formdataHeader).serializeArray();
		console.log(getval_from_name);
		var gridname = event.data.data.gridname;
		var table_array = getval_from_grid(gridname,[	//get the index from jqgrid
								{text: 'Item Code', style: 'tableHeader', jq_index: 3},// 3
								{text: 'UOM Code', style: 'tableHeader', jq_index: 4},// 4
								{text: 'Item Description', style: 'tableHeader', jq_index: 5},// 5
								{text: 'Tran Qty', style: 'tableHeader', jq_index: 9},// 9
								{text: 'Net Price', style: 'tableHeader', jq_index: 10},// 10
								{text: 'Amount', style: 'tableHeader', jq_index: 11},// 11
								{text: 'Expiry Date', style: 'tableHeader', jq_index: 12},// 12
								{text: 'Batch No.', style: 'tableHeader', jq_index: 13}// 13
							]);

		docDefinition = {
			pageSize: 'A4',
		  	content: [
			{ 
				style: 'header',
				text: [
					'Inventory Data Entry Header'
					]
			},{
		      style:'colmargin',
		      columns: [
					{ width: 120, text: 'Transaction Department: ', bold: true },
					{ width: 200, text: get_desc('txndept')+' ( '+getval_from_name(header_data,'txndept')+' )' },
					{ width: 120, text: 'Request RecNo: ', bold: true },
					{ width: 50, text: getval_from_name(header_data,'srcdocno') },
				],
		    },{
		      style:'colmargin',
		      columns: [
					{ width: 120, text: 'Transaction Type: ', bold: true },
					{ width: 200, text: get_desc('trantype')+' ( '+getval_from_name(header_data,'trantype')+' )' },
					{ width: 120, text: 'Document No: ', bold: true },
					{ width: 50, text: getval_from_name(header_data,'docno') },
		      ],
		    },{
		      style:'colmargin',
		      columns: [
					{ width: 120, text: ' ', bold: true },
					{ width: 200, text: ' ' },
					{ width: 120, text: 'Record No: ', bold: true },
					{ width: 50, text: getval_from_name(header_data,'recno') },
		      ],
		    },
		    {canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 1 }]},
		    {
		      style:'colmargin',
		      columns: [
					{ width: 120, text: 'Receiver Type: ', bold: true },
					{ width: 200, text: getval_from_name(header_data,'sndrcvtype') },
					{ width: 120, text: ' ', bold: true },
					{ width: 50, text: ' ' },
				],
		    },{
		      style:'colmargin',
		      columns: [
					{ width: 120, text: 'Receiver: ', bold: true },
					{ width: 200, text: get_desc('sndrcv')+' ( '+getval_from_name(header_data,'sndrcv')+' )' },
					{ width: 120, text: 'Amount: ', bold: true },
					{ width: 50, text: getval_from_name(header_data,'amount') },
		      ],
		    },{
		      style:'colmargin',
		      columns: [
					{ width: 120, text: 'Transaction Date: ', bold: true },
					{ width: 200, text: getval_from_name(header_data,'trandate') },
					{ width: 120, text: 'Status: ', bold: true },
					{ width: 50, text: getval_from_name(header_data,'recstatus') },
		      ],
		    },{
		      style:'colmargin',
		      columns: [
					{ width: 120, text: 'Remark: ', bold: true },
					{ width: 450, text: getval_from_name(header_data,'remarks') },
		      ],
		    },{ 
				style: 'headerDetail',
				text: [
					'Inventory DataEntry Detail'
					]
			},{
					style: 'tableExample',
					table: {
						headerRows: 1,
						widths: ['*',50,100,20,20,30,'*','*'],
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