<!DOCTYPE html>
<html>
<head>
<title>Purchase Request</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/numeral@2.0.6/numeral.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/fomantic-ui@2.9.2/dist/semantic.min.js"></script>
</object>

<script>
	var merge_key = makeid(20);
	var base64_pr = null;
	var purreqhd = {
		@foreach($purreqhd as $key => $val) 
			'{{$key}}' : `{!!str_replace('`', '', $val)!!}`,
		@endforeach 
	};

	var attachmentfiles = [
		@foreach($attachment_files as $file)
		{	
			idno:'{{$file->idno}}',
			src:'{{$file->attachmentfile}}',
		},
		@endforeach
	]

	var purreqdt=[
		@foreach($purreqdt as $key => $podt)
		[
			@foreach($podt as $key2 => $val)
				{'{{$key2}}' : `{{$val}}`},
			@endforeach
		],
		@endforeach 
	];

	var supplier = {
		@if(!empty($supplier))
			@foreach($supplier as $key => $val) 
				'{{$key}}' : '{{$val}}',
			@endforeach 
		@endif
	};

	var company = {
		@foreach($company as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var totamt_eng = '{{$totamt_eng}}';
	var total_tax = '{{$total_tax}}';
	var total_discamt = '{{$total_discamt}}';

	$(document).ready(function () {
		var docDefinition = {
			footer: function(currentPage, pageCount) {
				return [
					{
						text: 'This is a computer-generated document. No signature is required.', alignment: 'center', fontSize: 10
					},
					{ text: currentPage.toString() + ' of ' + pageCount, alignment: 'center', fontSize: 10 }
				]
			},
			pageSize: 'A4',
			// pageOrientation: 'landscape',
			pageMargins: [30,20,30,30],
			content: [
				{
					image: 'letterhead', width: 175, style: 'tableHeader', colSpan: 5, alignment: 'center'
				},
				{
					text: 'PURCHASE REQUEST (PR)',
					style: 'header',
					alignment: 'center',
					// background: 'black',
					// color: 'white',
				},
				{
					text: '(1) REQUESTED BY',
					style: 'subheader'
				},
				{
					style: 'tableExample',
					table: {
						widths: [41,41,41,41,41,40,40,41,41,41,41],
						// headerRows: 5,
						// keepWithHeaderRows: 5,
						body: [
							[
								{
									text: 'Name: ',
									colSpan: 2, alignment: 'left'
								},{},
								{
									text: '{{$purreqhd->requestby_name}}',
									colSpan: 4, alignment: 'left'
								},{},{},{},
								{
									text: 'Date: ',
									colSpan: 2, alignment: 'left'
								},{},
								{
									text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$purreqhd->purreqdt)->format('d-m-Y')}}',
									colSpan: 3, alignment: 'left'
								},{},{}
							],
							[
								{
									text: 'Designation: ',
									colSpan: 2, alignment: 'left'
								},{},
								{
									text: '{{$purreqhd->requestby_dsg}}',
									colSpan: 4, alignment: 'left'
								},{},{},{},
								{
									text: 'Department/Unit: {{$purreqhd->reqdept_name}}',
									colSpan: 5, alignment: 'left'
								},{},{},{},{}
							],
							[
								{
									text: 'Supplier / Vendor: ',
									colSpan: 2, alignment: 'left'
								},{},
								{
									text: '@if(!empty($supplier)){{$supplier->Name}}@endif',
									colSpan: 4, alignment: 'left'
								},{},{},{},
								{
									text: 'Record no: {{str_pad($purreqhd->recno, 5, '0', STR_PAD_LEFT)}}',
									colSpan: 2, alignment: 'left'
								},{},
								{
									text: 'Request No: {{$purreqhd->prdept}}-{{str_pad($purreqhd->purreqno, 5, '0', STR_PAD_LEFT)}}',
									colSpan: 3, alignment: 'left'
								},{},
							],
							[
								{
									text: [
										'Remarks:\n',
										{ text: '(Compulsory)', bold: true },
									], colSpan: 2, rowSpan: 2, alignment: 'left'
								},{},
								{
									text: purreqhd.remarks,
									colSpan: 9, rowSpan: 2, alignment: 'left'
								},{},{},{},{},{},{},{},{}
							],
							[ {},{},{},{},{},{},{},{},{},{},{} ],
							@if($purreqhd->recstatus == 'CANCELLED')
							[
								{
									text: [
										'Cancel Remarks:\n',
										{ text: 'Cancel By: '+purreqhd.cancelby, bold: true,color:'darkred' },
									], colSpan: 2, rowSpan: 2, alignment: 'left',color:'darkred'
								},{},
								{
									text: purreqhd.cancelled_remark,
									colSpan: 9, rowSpan: 2, alignment: 'left',color:'darkred'
								},{},{},{},{},{},{},{},{}
							],
							[ {},{},{},{},{},{},{},{},{},{},{} ],
							@endif
						]
					}
				},
				{
					text: 'Note:-', fontSize: 8, bold: true
				},
				{
					text: '1)	Asset of RM3000 and above (per unit/set) must be verified by Finance Executive (Asset Management)\n2)	Amount below RM1,000 (1 quotation)\n3)	Amount RM1,000 - RM100,000 (Min 3 quotations)\n4)	RM100,000 - RM200,000 (Min 4 quotations/sebutharga kecil)\n5)	RM200,000 - RM500,000.00 (Min 5 quotations/ quotation process)\n6)	Amount above RM 500,000 (tendering process)', fontSize: 8
				},
				{
					text: '(2) APPLICATION (Please use additional sheet(s) if required)',
					style: 'subheader'
				},
				{
					style: 'tableExample',
					table: {
						widths: [41,41,41,41,41,40,40,41,41,41,41],
						// headerRows: 5,
						// keepWithHeaderRows: 5,
						body: [
							[
								{ text: 'No.' },
								{ text: 'Item Description', colSpan: 5 },{},{},{},{},
								{ text: 'Qty', alignment: 'right' },
								{ text: 'Price\n(Per Unit)', alignment: 'right' },
								{ text: 'Tax Amt', alignment: 'right' },
								{ text: 'Price (RM)', alignment: 'right', colSpan: 2 },{}
							],
							@foreach($purreqdt as $index=>$obj)
							[
								{ text: '{{++$index}}' },
								{ text: `{!!str_replace('`', '', $obj->description)!!}\n{!!str_replace('`', '', $obj->remarks)!!}`, colSpan: 5 },{},{},{},{},
								{ text: '{{$obj->qtyrequest}}', alignment: 'right' },
								{ text: '{{number_format($obj->unitprice,2)}}', alignment: 'right' },
								{ text: '{{number_format($obj->tot_gst,2)}}', alignment: 'right' },
								{ text: '{{number_format($obj->totamount,2)}}', alignment: 'right', colSpan: 2 },{}
							],
							@endforeach
							// [
							// 	{
							// 		text: [
							// 			'1)	New Asset [	] / Replacement [	] / Additional [	]: ',
							// 			{ text: 'Please tick (√)', fontSize: 7 },
							// 			'\n2)	Budget : Yes [	] / No [	]',
							// 			'\n3)	Justification: \n____________________________________________________\n____________________________________________________',
							// 			'\n\n4)	Charge to patient per use: _____________________',
							// 		], colSpan: 6
							// 	},{},{},{},{},{},{},{},{},
							// 	{ text: ' ', colSpan: 2 },{}
							// ],
							[
								{ text: 'Total', style: 'totalbold', colSpan: 8, alignment: 'right' },{},{},{},{},{},{},{},
								{ text: '{{number_format($total_tax,2)}}', alignment: 'right' },
								{ text: '{{number_format($purreqhd->totamount,2)}}', alignment: 'right', colSpan: 2 },{}
							],
							[
								{ text: 'Requested By:', style: 'totalbold', colSpan: 3 , border: [true, false, true, false]},{},{},
								{ text: 'Supported By:', style: 'totalbold', colSpan: 3 , border: [true, false, true, false]},{},{},
								{ text: 'Verified By:', style: 'totalbold', colSpan: 5 , border: [true, false, true, false]},{},{},{},{}
							],
							[
								{ text: '', colSpan: 3 , border: [true, false, true, false]},{},{},
								{ text: `{!!$purreqhd->support_remark!!}`, colSpan: 3 , border: [true, false, true, false]},{},{},
								{ text: `{!!$purreqhd->verified_remark!!}`, colSpan: 5 , border: [true, false, true, false]},{},{},{},{}
							],
							[
								{ text: '{{$purreqhd->requestby_name}}', style: 'totalbold', colSpan: 3 , border: [true, false, true, false]},{},{},
								{ text: '{{$purreqhd->supportby_name}}', style: 'totalbold', colSpan: 3 , border: [true, false, true, false]},{},{},
								{ text: '{{$purreqhd->verifiedby_name}}', style: 'totalbold', colSpan: 5 , border: [true, false, true, false]},{},{},{},{}
							],
							[
								{ text: '{{$purreqhd->requestby_dsg}}', style: 'totalbold', italics: true,margin: [0,-5,0,0], colSpan: 3 , border: [true, false, true, false]},{},{},
								{ text: '{{$purreqhd->supportby_dsg}}', style: 'totalbold', italics: true,margin: [0,-5,0,0], colSpan: 3 , border: [true, false, true, false]},{},{},
								{ text: '{{$purreqhd->verifiedby_dsg}}', style: 'totalbold', italics: true,margin: [0,-5,0,0], colSpan: 5 , border: [true, false, true, false]},{},{},{},{}
							],

							[
								{ text: 'Joint Recommended By:', style: 'totalbold', colSpan: 3 , border: [true, true, true, false]},{},{},
								{ text: 'Joint Recommended By:', style: 'totalbold', colSpan: 3 , border: [true, true, true, false]},{},{},
								{ text: 'Approved By:', style: 'totalbold', colSpan: 5 , border: [true, true, true, false]},{},{},{},{}
							],
							[
								{ text: `{!!$purreqhd->recommended1_remark!!}`, colSpan: 3 , border: [true, false, true, false]},{},{},
								{ text: `{!!$purreqhd->recommended2_remark!!}`, colSpan: 3 , border: [true, false, true, false]},{},{},
								{ text: `{!!$purreqhd->approved_remark!!}`, colSpan: 5 , border: [true, false, true, false]},{},{},{},{}
							],
							[
								{ text: '{{$purreqhd->recommended1by_name}}', style: 'totalbold', colSpan: 3 , border: [true, false, true, false]},{},{},
								{ text: '{{$purreqhd->recommended2by_name}}', style: 'totalbold', colSpan: 3 , border: [true, false, true, false]},{},{},
								{ text: '{{$purreqhd->approvedby_name}}', style: 'totalbold', colSpan: 5 , border: [true, false, true, false]},{},{},{},{}
							],
							[
								{ text: '{{$purreqhd->recommended1by_dsg}}', style: 'totalbold', italics: true,margin: [0,-5,0,0], colSpan: 3 , border: [true, false, true, true]},{},{},
								{ text: '{{$purreqhd->recommended2by_dsg}}', style: 'totalbold', italics: true,margin: [0,-5,0,0], colSpan: 3 , border: [true, false, true, true]},{},{},
								{ text: '{{$purreqhd->approvedby_dsg}}', style: 'totalbold', italics: true,margin: [0,-5,0,0], colSpan: 5 , border: [true, false, true, true]},{},{},{},{}
							],
							
						]
					}
				},
				// {
				// 	text: 'This is a computer-generated document. No signature is required.', alignment: 'center', fontSize: 8,
				// },
			],
			styles: {
				header: {
					fontSize: 16,
					bold: true,
					margin: [0, 2, 0, 10]
				},
				subheader: {
					fontSize: 10,
					bold: true,
					margin: [0, 10, 0, 5]
				},
				tableExample: {
					fontSize: 9,
					margin: [0, 5, 0, 15]
				},
				tableHeader: {
					bold: true,
					fontSize: 13,
					color: 'black',
					margin: [0, 0, 0, 0]
				},
				totalbold: {
					bold: true,
					fontSize: 9,
				}
			},
			images: {
				letterhead: {
					url: "{{asset('/img/letterheadukm.png')}}",
					headers: {
						myheader: '123',
						myotherheader: 'abc',
					}
				}
			}
		};

		// pdfMake.createPdf(docDefinition).getBase64(function(data) {
		// 	var base64data = "data:base64"+data;
		// 	console.log($('object#pdfPreview').attr('data',base64data));
		// 	// document.getElementById('pdfPreview').data = base64data;
			
		// });
		pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
			$('#pdfiframe').attr('src',dataURL);
		});

		pdfMake.createPdf(docDefinition).getBase64(function(dataURL) {
			base64_pr = dataURL;

			var obj = {
				base64:dataURL,
				_token:$('#_token').val(),
				merge_key:merge_key,
				lineno_:1
			};

			$.post( '../attachment_upload/form?page=merge_pdf',$.param(obj) , function( data ) {
			}).done(function(data) {
			});
		});
	});

	$(document).ready(function () {
		$('div.canclick').click(function(){
			$('div.canclick').removeClass('teal inverted');
			$(this).addClass('teal inverted');
			var goto = $(this).data('goto');

			if($(goto).offset() != undefined){
			$('html, body').animate({
				scrollTop: $(goto).offset().top
				}, 500, function(){

				});
			}
		});

		$('#merge_btn').click(function(){
			let attach_array = [];
			$('input:checkbox:checked').each(function(){
				attach_array.push($(this).data('src'));
			});

			if(attach_array.length > 0 ){
				var obj = {
					page:'merge_pdf_with_attachment',
					merge_key:merge_key,
					attach_array:attach_array
				};

				$('#pdfiframe_merge').attr('src',"../attachment_upload/table?"+$.param(obj));
				$('#btn_merge,#pdfiframe_merge').show();
				$('#btn_merge').click();
			}else{
				alert('Select at least 1 PDF Attachment to merge with main PDF');
			}
		});

		populate_attachmentfile();

		$('#ref_dropdown.ui.dropdown')
		  .dropdown({
		  	onChange: function(value, text, $selectedItem) {
		      window.open(value);
		    }
		  });
	});

	function makeid(length) {
	    let result = '';
	    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	    const charactersLength = characters.length;
	    let counter = 0;
	    while (counter < length) {
	      result += characters.charAt(Math.floor(Math.random() * charactersLength));
	      counter += 1;
	    }
	    return result;
	}

	function populate_attachmentfile(){
		attachmentfiles.forEach(function(e,i){
			$('#pdfiframe_'+e.idno).attr('src',"../uploads/"+e.src);
		});
	}
	

	// pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
	// 	console.log(dataURL);
	// 	document.getElementById('pdfPreview').data = dataURL;
	// });

	

	// jsreport.serverUrl = 'http://localhost:5488'
    // async function preview() {        
    //     const report = await jsreport.render({
	// 	  template: {
	// 	    name: 'mc'    
	// 	  },
	// 	  data: mydata
	// 	});
	// 	document.getElementById('pdfPreview').data = await report.toObjectURL()

    // }

    // preview().catch(console.error)
</script>

<body style="margin: 0px;">

<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
<div class="ui segments" style="width: 18vw;height: 95vh;float: left; margin: 10px; position: fixed;">
  <div class="ui secondary segment">
    <h3>
    	<b>Navigation</b>
    	<button id="merge_btn" class="ui small primary button" style="font-size: 12px;padding: 6px 10px;float: right;">Merge</button>
		</h3>
		@if(!empty($print_connection->purordhd) || !empty($print_connection->delordhd))
		<div class="ui dropdown" id="ref_dropdown">
		  <div class="text">Document Reference</div>
		  <i class="dropdown icon"></i>
		  <div class="menu">
		  	@if(!empty($print_connection->purordhd))
		    <div class="item" data-value="../purchaseOrder/showpdf?recno={{$print_connection->purordhd->recno}}">Purchase Order</div>
		  	@endif

		  	@if(!empty($print_connection->delordhd))
		    <div class="item" data-value="../deliveryOrder/showpdf?recno={{$print_connection->delordhd->recno}}">Delivery Order</div>
		  	@endif
		  </div>
		</div>
		@endif
  </div>
  <div class="ui segment teal inverted canclick" style="cursor: pointer;" data-goto='#pdfiframe'>
    <p>Purchase Request </p>
  </div>
  @foreach($attachment_files as $file)
  <div class="ui segment canclick" style="cursor: pointer;" data-goto='#pdfiframe_{{$file->idno}}'>
    <p>{{$file->resulttext}} <input type="checkbox" data-src="{{$file->attachmentfile}}" name="{{$file->idno}}" style="float: right;margin-right: 5px;"></p>
  </div>
  @endforeach
  <div id="btn_merge" class="ui segment canclick" style="cursor: pointer;display: none;" data-goto='#pdfiframe_merge'>
    <p>Merged File</p>
  </div>
</div>
<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 79vw;height: 100vh;float: right;"></iframe>
@foreach($attachment_files as $file)
<iframe id="pdfiframe_{{$file->idno}}" width="100%" height="100%" src="" frameborder="0" style="width: 79vw;height: 100vh;float: right;"></iframe>
@endforeach
<iframe id="pdfiframe_merge" width="100%" height="100%" src="" frameborder="0" style="width: 79vw;height: 100vh;float: right;display: none;"></iframe>

<!-- <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe> -->

</body>
</html>