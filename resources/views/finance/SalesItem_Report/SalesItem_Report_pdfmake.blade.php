<!DOCTYPE html>
<html>
<head>
<title>Sales By Item</title>

</head>

<!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="mydata.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<script>
    
    var dbacthdr=[
        @foreach($dbacthdr as $key => $dtobj)
            {
            @foreach($dtobj as $key2 => $val)
                '{{$key2}}' : `{!!str_replace('`', '', $val)!!}`,
            @endforeach
            },
        
        @endforeach 
    ];

    var invno_array=[
        @foreach($invno_array as $key => $val) 
            '{{$val}}',
        @endforeach 
    ];

    var header = {
        @foreach($header as $key => $val) 
            '{{$key}}' : '{{$val}}',
        @endforeach 
    };

    $(document).ready(function () {
        var docDefinition = {
            header: function(currentPage, pageCount, pageSize) {
                var retval=[];
                var header_tbl = {
                        style: 'header_tbl',
                        table: {
                            headerRows: 1,
                            widths: ['*','*','*','*'],//panjang standard dia 515
                            body: [
                                [
                                    {text: 'Date From',bold: true}, 
                                    {text: ': '+header.datefrom},
                                    {text: 'Date To',bold: true}, 
                                    {text: ': '+header.dateto},
                                ],[
                                    {text: 'Print By',bold: true}, 
                                    {text: ': '+header.printby},
                                    {text: 'Page',bold: true}, 
                                    {text: ': '+currentPage+' / '+pageCount},
                                ]
                            ]
                        },
                        layout: 'noBorders',
                    }

                var title = {text: header.compname+'\nSales By Item',fontSize:10,alignment: 'center',bold: true, margin: [0, 20, 0, 0]};
                retval.push(title);

                retval.push(header_tbl);
                return retval

            },
            footer: function(currentPage, pageCount) {
                return [
                  { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                ]
            },
            pageSize: 'A4',
            pageMargins: [20, 110, 20, 50],
            content: [
                {
                    style: 'tableExample',
                    table: {
                        headerRows: 1,
                        dontBreakRows: true,
                        widths: [50,50,180,'*','*','*','*','*'],//panjang standard dia 515
                        body: make_body()
                    }
                },
            ],
            styles: {
                tableExample: {
                    fontSize: 9,
                    margin: [0, 15, 0, 0]
                },
                header_tbl: {
                    fontSize: 9,
                    margin: [30, 20, 40, 20]
                },
                body_ttl: {
                    margin: [-2, 2, -2, 2]
                },
                body_row: {
                    margin: [-2, 3, -2, 3]
                },
                body_hdr: {
                    bold: true,
                    margin: [0, 0, 0, 2]
                },
            },
        };

        // pdfMake.createPdf(docDefinition).getBase64(function(data) {
        //  var base64data = "data:base64"+data;
        //  console.log($('object#pdfPreview').attr('data',base64data));
        //  // document.getElementById('pdfPreview').data = base64data;
            
        // });
        pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
            $('#pdfiframe').attr('src',dataURL);
        });
    });

    function make_body(){
        var retval = [
            [
                {text:'Date',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
                {text:'Charge Code',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
                {text:'Description',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
                {text:'Quantity',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
                {text:'Tot Amount',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
                {text:'Tot Cost',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
                {text:'Tax',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
                {text:'Total',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
            ]
        ];

        var totalAmount = 0;
        invno_array.forEach(function(e_inv,i_inv){
            var amt = 0;
            var cpr = 0;
            var tax = 0;
            var tot = 0;
            dbacthdr.forEach(function(e,i){
                if(e_inv == e.invno){
                    if(amt == 0){
                        let arr_hdr = [
                            {text:e.debtorcode+' '+e.dm_desc+' '+
                                ('0000000' + e.invno).slice(-7), style: 'body_hdr',colSpan: 8, border: [false, false, false, false]},
                            {},
                            {},
                            {},
                            {},
                            {},
                            {},
                            {},
                        ];
                        retval.push(arr_hdr);
                    }

                    let arr1 = [
                        {text:dateFormatter(e.trxdate), style: 'body_row', border: [false, false, false, false]},
                        {text:e.chgcode, style: 'body_row', border: [false, false, false, false]},
                        {text:e.cm_desc, style: 'body_row', border: [false, false, false, false]},
                        {text:myparseFloat(e.quantity),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
                        {text:myparseFloat(e.amount),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
                        {text:myparseFloat(e.costprice),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
                        {text:myparseFloat(e.taxamount),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
                        {text:myparseFloat(parseFloat(e.amount) + parseFloat(e.taxamount)),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
                    ];
                    retval.push(arr1);
                    amt = amt + parseFloat(e.amount);
                    cpr = cpr + parseFloat(e.costprice);
                    tax = tax + parseFloat(e.taxamount);
                    tot = tot + parseFloat(e.amount) + parseFloat(e.taxamount);
                }

            });

            let arr_ftr = [
                {text:'', style: 'body_hdr', border: [false, false, false, false]},
                {text:'', style: 'body_hdr', border: [false, false, false, false]},
                {text:'', style: 'body_hdr', border: [false, false, false, false]},
                {text:'TOTAL',alignment: 'right', style: 'body_hdr', border: [false, false, false, false]},
                {text:myparseFloat(amt),alignment: 'right', style: 'body_hdr', border: [false, false, false, false]},
                {text:myparseFloat(cpr),alignment: 'right', style: 'body_hdr', border: [false, false, false, false]},
                {text:myparseFloat(tax),alignment: 'right', style: 'body_hdr', border: [false, false, false, false]},
                {text:myparseFloat(tot),alignment: 'right', style: 'body_hdr', border: [false, false, false, false]},
            ];
            retval.push(arr_ftr);
            totalAmount = totalAmount + parseFloat(tot);
        });

        let arr_grt = [
            {text:'', style: 'body_hdr', border: [false, true, false, true]},
            {text:'', style: 'body_hdr', border: [false, true, false, true]},
            {text:'', style: 'body_hdr', border: [false, true, false, true]},
            {text:'GRAND TOTAL',colSpan: 4,alignment: 'right', style: 'body_hdr', border: [false, true, false, true]},
            {},
            {},
            {},
            {text:myparseFloat(totalAmount),alignment: 'right', style: 'body_hdr', border: [false, true, false, true]},
        ];
        retval.push(arr_grt);

        return retval;
    }

    function dateFormatter(val){
        if(val == null) return '';
        if(val.trim() == '') return '';
        return moment(val).format("DD-MM-YYYY");
    } 

    function myparseFloat(val){
        if(val == null) return '0.00';
        // if(val.trim() == '') return '0.00';
        return numeral(val).format('0,0.00');
    } 

    function myparseFloatVV(unitcost,dspqty){
        if(dspqty == null) return '0.00';
        // if(dspqty.trim() == '') return '0.00';
        return numeral(unitcost*dspqty).format('0,0.00');
    } 
    

    // pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
    //  console.log(dataURL);
    //  document.getElementById('pdfPreview').data = dataURL;
    // });

    

    // jsreport.serverUrl = 'http://localhost:5488'
    // async function preview() {        
    //     const report = await jsreport.render({
    //    template: {
    //      name: 'mc'    
    //    },
    //    data: mydata
    //  });
    //  document.getElementById('pdfPreview').data = await report.toObjectURL()

    // }

    // preview().catch(console.error)
</script>

<body style="margin: 0px;">

<iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>

</body>
</html>