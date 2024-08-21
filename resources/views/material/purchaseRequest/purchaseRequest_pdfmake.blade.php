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
			'{{$key}}' : '{{$val}}',
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
		@foreach($supplier as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var company = {
		@foreach($company as $key => $val) 
			'{{$key}}' : '{{$val}}',
		@endforeach 
	};

	var reqdept = {
		@foreach($reqdept as $key => $val) 
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
			      { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
			    ]
			},
			pageSize: 'A4',
		  	content: [
				{
					style: 'tableExample',
					table: {
						widths: ['*','*','*','*','*','*','*','*','*','*','*'],
						// headerRows: 5,
						// keepWithHeaderRows: 5,
						body: [
							[
								{image: 'letterhead',width:175, height:65, style: 'tableHeader', colSpan: 5, alignment: 'center'},{},{},{},{},

								{text: 'Purchase Request', style: 'tableHeader', colSpan: 6, alignment: 'center'},{},{},{},{},{}
							],

							[
								{
									text: 'Address To:\n{{$supplier->SuppCode}}\n{{$supplier->Name}}\n{{$supplier->Addr1}}\n{{$supplier->Addr2}}\n{{$supplier->Addr3}}\n{{$supplier->Addr4}}', 
									colSpan: 5, 
									rowSpan: 4,
									alignment: 'left'},{},{},{},{},
								{
								 	text: 'Purchase No.',
								 	colSpan: 2, alignment: 'left'},{},
								{
									text: '{{$purreqhd->reqdept}}'+'{{str_pad($purreqhd->purreqno, 9, '0', STR_PAD_LEFT)}}',
								 	colSpan: 4, alignment: 'left'},{},{},{}
							],
							
							[
								{},{},{},{},{},
								{
								 	text: 'Purchase Date.',
								 	colSpan: 2, alignment: 'left'},{},
								{
									text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$purreqhd->purreqdt)->format('d-m-Y')}}',
								 	colSpan: 4, alignment: 'left'},{},{},{}
							],

							[
								{},{},{},{},{},
								{
								 	text: 'Contact Person.',
								 	colSpan: 2, alignment: 'left'},{},
								{
									text: '{{$supplier->ContPers}}',
								 	colSpan: 4, alignment: 'left'},{},{},{}
							],

							[{},{},{},{},{},
							{
							 	text: 'Contact No.',
							 	colSpan: 2, alignment: 'left'},{},
							{
								text: '{{$supplier->TelNo}}',
							 	colSpan: 4, alignment: 'left'},{},{},{}],

							[{text:'No.'},{text:'Description',colSpan: 4},{},{},{},{text:'UOM'},{text:'Quantity'},{text:'Unit Price'},{text:'Tax Amt'},{text:'Discount Amount'},{text:'Nett Amount'}],

							@foreach ($purreqdt as $index=>$obj)
							[
								{text:'{{++$index}}'},
								{text:`{{$obj->description}}\n{{$obj->remarks}}`,colSpan: 4},{},{},{},
								{text:'{{$obj->uomcode}}'},
								{text:'{{$obj->qtyrequest}}', alignment: 'right'},
								{text:'{{number_format($obj->unitprice,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->tot_gst,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->amtdisc,2)}}', alignment: 'right'},
								{text:'{{number_format($obj->amount,2)}}', alignment: 'right'},
							],
							@endforeach

							[
								{text:'TOTAL', style: 'totalbold', colSpan: 5},{},{},{},{},{},{},{},
								{text:'{{number_format($total_tax,2)}}', alignment: 'right'},
								{text:'{{number_format($total_discamt,2)}}', alignment: 'right'},
								{text:'{{number_format($purreqhd->totamount,2)}}', alignment: 'right'}
							],

							[
								{text:'RINGGIT MALAYSIA: {{$totamt_eng}}', style: 'totalbold', italics: true, colSpan: 11}
							],
							
							[
								{text:

									`Please Deliver goods/services/works with original purchase order, delivery order and invoice to:\n\nAddress To:\n{{$reqdept->description}}\n{{$reqdept->addr1}}\n{{$reqdept->addr2}}\n{{$reqdept->addr3}}\n{{$reqdept->addr4}}\n
									Contact Person: {{$reqdept->contactper}}\n
									Tel No.: {{$reqdept->tel}}\n
									Email: {{$reqdept->email}}\n
									`
									,colSpan: 6,rowSpan:4},{},{},{},{},{},
								{text:'Delivered By: \n\n\n\n\n\n', style: 'totalbold',colSpan: 3,rowSpan:3},{},{},
								{text:'Approval: \n\n\n\n\n\n', style: 'totalbold',colSpan: 2,rowSpan:3},{}
							],
							
							[
								{},{},{},{},{},{},
								{},{},{},
								{},{}
							],
							
							[
								{},{},{},{},{},{},
								{},{},{},
								{},{}
							],
							
							[
								{},{},{},{},{},{},
								{text:'Sign: \n\n\n\n\n\n   Position:\n\n Date:\n\n', style: 'totalbold',colSpan: 5},{},{},
								{},{}
							]
						]
					}
				},
			],
			styles: {
				header: {
					fontSize: 18,
					bold: true,
					margin: [0, 0, 0, 10]
				},
				subheader: {
					fontSize: 16,
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
					color: 'black'
				},
				totalbold: {
					bold: true,
					fontSize: 10,
				}
			},
			images: {
				letterhead: {
					url: "{{asset('/img/MSLetterHead.jpg')}}",
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

			var obj = {
				page:'merge_pdf_with_attachment',
				merge_key:merge_key,
				attach_array:attach_array
			};

			$('#pdfiframe_merge').attr('src',"../attachment_upload/table?"+$.param(obj));
			$('#btn_merge,#pdfiframe_merge').show();
			$('#btn_merge').click();
		});

		populate_attachmentfile();
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
    <h3><b>Navigation</b>
    	<button id="merge_btn" class="ui small primary button" style="font-size: 12px;padding: 6px 10px;float: right;">Merge</button>
	</h3>
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