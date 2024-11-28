<!DOCTYPE html>
<html>
<head>
<title>Repack</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<style>
    svg#barcode{
        display: none;
    }
</style>
<svg id="barcode"></svg>
<body style="margin: 0px;">
    <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>
</body>
<script>

    var mydata = {
        'itemcode' : "{{$itemcode}}",
        'pages' : "{{$pages}}",
    }

    JsBarcode("#barcode", mydata.itemcode, {
      format: "CODE39",
      width:1,
      height:30,
      displayValue: false,
      margin:0
    });

    $(document).ready(function () {
        var docDefinition = {
            pageSize: {
                width: 65 * 2.8346456693,
                height: 20 * 2.8346456693,
            },
            pageMargins: [5, 5, 5, 5],
            content: make_content(),
            styles: {
            },
        };

        function make_content(){
            var content = [];
            var pages = parseInt(mydata.pages);

            for (var i = pages - 1; i >= 0; i--) {

                content.push({
                  svg: $('svg#barcode').get(0).outerHTML,
                  width: 160,
                  margin:[0,0,0,0],alignment:'center'
                });
                content.push({
                    text:mydata.itemcode,alignment:'center'
                });
                if(i != 0){
                    content.push({text:'',pageBreak: 'after'});
                }
            }

            return content;
        }

        pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
            $('#pdfiframe').attr('src',dataURL);
        });
    });
</script>

</html>