<!DOCTYPE html>
<html>
<head>
<title>Print Patient Label</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>

</object>

<script>
	
	var mydata = {
	    'comp_name' : "{!!$ini_array['comp_name']!!}",
	    'name' : "{!!$ini_array['name']!!}",
        'mrn' : "{!!$ini_array['mrn']!!}",
        'sex' : "{!!$ini_array['sex']!!}",
        'age' : "{!!$ini_array['age']!!}",
        'date' : "{!!$ini_array['date']!!}",
        'newic' : "{!!$ini_array['newic']!!}",
        'dob' : "{!!$ini_array['dob']!!}",
        'race' : "{!!$ini_array['race']!!}",
        'bedno' : "{!!$ini_array['bedno']!!}",
        'ward' : "{!!$ini_array['ward']!!}",
        'doc' : "{!!$ini_array['doc']!!}",
        'pages' : "{!!$ini_array['pages']!!}",
	};

	$(document).ready(function () {
		var docDefinition = {
			pageSize: {
				width: 60 * 2.8346456693,
			    height: 40 * 2.8346456693,
			},
			pageMargins: [5, 5, 5, 5],
			content:make_content(),
		  	styles: {
				normal: {
					margin: [0, 2, 0, 2],
					alignment:'left',
					fontSize:7
				},
				normal_tbl: {
					margin: [0, 0, 0, 0],
					alignment:'left',
					fontSize:7
				},
				table:{
					margin: [0, 0, 0, 0],
				}
			},
			defaultStyle: {
				fontSize: 12,
			},
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
	
	function make_content(){
		var content = [];
		var pages = parseInt(mydata.pages);

		for (var i = pages - 1; i >= 0; i--) {
			content.push({text:mydata.comp_name.toUpperCase(), bold:true, fontSize:10,alignment:'center' });
			content.push({text:mydata.name.toUpperCase(), style: 'normal', bold:true ,alignment:'center' });

			content.push({
				style: 'table',
	            table: {
	                widths: [15,55,15,55],
	                body:[
	                	[
							{text: 'MRN',bold: true, style: 'normal_tbl'}, 
							{text: ': '+mydata.mrn.toUpperCase(), style: 'normal_tbl'},
							{text: 'SEX',bold: true, style: 'normal_tbl'}, 
							{text: ': '+mydata.sex.toUpperCase()+' / '+mydata.age+' y/o', style: 'normal_tbl'},
						],[
							{text: 'DATE',bold: true, style: 'normal_tbl'}, 
							{text: ': '+mydata.date, style: 'normal_tbl'},
							{text: 'I/C',bold: true, style: 'normal_tbl'}, 
							{text: ': '+mydata.newic, style: 'normal_tbl'},
						],[
							{text: 'DOB',bold: true, style: 'normal_tbl'}, 
							{text: ': '+mydata.dob, style: 'normal_tbl'},
							{text: 'RACE',bold: true, style: 'normal_tbl'}, 
							{text: ': '+mydata.race.toUpperCase(), style: 'normal_tbl'},
						],[
							{text: 'BEDNO',bold: true, style: 'normal_tbl'}, 
							{text: ': '+mydata.bedno, style: 'normal_tbl'},
							{text: 'WARD',bold: true, style: 'normal_tbl'}, 
							{text: ': '+mydata.ward.toUpperCase(), style: 'normal_tbl'},
						]
	                ]
	        },layout: 'noBorders'});

			content.push({text:'DOCTOR:'+mydata.doc, style: 'normal' ,alignment:'center'});

			if(i != 0){
				content.push({text:'',pageBreak: 'after'});
			}


			// var retval = [
			// 	{text:e.itemcode, style: 'bold', },
			// 	{text:e.uomcode, style: 'normal', },

			// ]
		}

		return content;
	}

</script>

<body style="margin: 0px;">

<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>

</body>
</html>