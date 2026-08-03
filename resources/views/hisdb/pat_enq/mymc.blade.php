<!DOCTYPE html>
<html>
<head>
<title>Medical Certificate</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    

</object>

<script>
	
	var mydata = {
	    "name":"{!!$ini_array['patfrom']!!}",
	    "date_visit":"{{$ini_array['datefrom']}}",
	    "date_created":"{{$ini_array['printeddate']}}",
	    "newic":"{{$ini_array['newic']}}",
	    "mc_no":"{{$ini_array['serialno']}}",
	    "mc_days":"{{$ini_array['mccnt']}}",
	    "mc_from":"{{$ini_array['datefrom']}}",
	    "mc_to":"{{$ini_array['dateto']}}",
	    "datereexam":"{{$ini_array['datereexam']}}",
	    "dateresume":"{{$ini_array['dateresume']}}",
	    "print_date":"{{$ini_array['printeddate']}}",
	    "print_by":"{!!$ini_array['printedby']!!}",
	    "sex":"{{$ini_array['sex']}}"
	};

	$(document).ready(function () {
		var docDefinition = {
			pageSize: 'A4',
		  	content: [
		  	{
				style: 'zahrahlogo',
				image: 'letterhead',width: 400
			},{ 
				style: 'header1',
				text: ['Sijil Cuti Sakit']
			},{ 
				style: 'header2',
				text: ['Medical Certificate']
			},{
		      style:'colmargin',
		      columns: [
					{ width: '70%', text: 'Patient Name: '+mydata.name, bold: true },
					{ width: '30%', text: 'Date of Visit: '+mydata.date_visit, bold: false }
				],
		    },{
		      style:'colmargin',
		      columns: [
					{ width: '70%', text: 'IC: '+mydata.newic, bold: true },
					{ width: '30%', text: 'Date Created: '+mydata.date_created, bold: false }
				],
		    },{
		      style:'colmargin',
		      columns: [
					{ width: '70%', text: '', bold: true },
					{ width: '30%', text: 'MC: '+mydata.mc_no, bold: false }
				],
		    },{
		      style:'colmargin2',
			  text: ['Dengan ini saya mengesahkan bahawa saya telah memeriksa nama yang tertera di atas'],
			  italics: true
		    },{
		      style:'colmargin',
			  text: ['I hereby certify that I have examined the name listed above'],
			  italics: true
		    },{
		      style:'colmargin2',
			  text: ['dan mendapati beliau tidak sihat / tidak fit untuk menjalankan tugas selama '+mydata.mc_days+' hari'],
			  italics: true
		    },{
		      style:'colmargin',
			  text: ['and find that '+mydata.sex+' is unfit for duty for '+mydata.mc_days+' days'],
			  italics: true
		    },{
		      style:'colmargin2',
		      columns: [
					{ width: '70%', text: 'daripada '+mydata.mc_from+' sehingga '+mydata.mc_to},
					{ width: '30%', text: '________________________' }
				],
			  italics: true
		    },{
		      style:'colmargin',
		      columns: [
					{ width: '70%', text: 'from '+mydata.mc_from+' to '+mydata.mc_to},
					{ width: '30%', text: 'Tandatangan Doktor' }
				],
			  italics: true
		    },{
		      style:'colmargin',
		      columns: [
					{ width: '70%', text: ''},
					{ width: '30%', text: 'Doctor Signature' }
				],
			  italics: true
		    },{
		      style:'colmargin3',
			  text: ['Note: This medical certificate is not valid for absence from court'],
			  italics: true
		    },{
		      style:'colmargin4',
			  text: ['Printed date: '+mydata.print_date+' by '+mydata.print_by],
			  italics: true
		    }],
		  	styles: {
				header1: {
					bold: true,
					alignment: 'center',
					margin: [0,20,0,5]		
				},header2: {
					bold: true,
					italics: true,
					alignment: 'center',
					margin: [0,0,0,20]		
				},
				colmargin: {
					margin: [0, 2, 0, 2]
				},
				colmargin2: {
					margin: [0, 30, 0, 2]
				},
				colmargin3: {
					fontSize: 11,
					alignment: 'center',
					margin: [0, 30, 0, 2]
				},
				colmargin4: {
					fontSize: 11,
					alignment: 'center',
					margin: [0, 2, 0, 2]
				},
				zahrahlogo: {
					alignment: 'center',
					margin: [0,0,0,5]
				}
			},
			defaultStyle: {
				fontSize: 12,
			},
			images: {
 				letterhead: {
					url: "{{asset('/img/logo/IMSCletterhead.png')}}",
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
	});
	

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

<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>

</body>
</html>