<!DOCTYPE html>
<html>
<head>
<title>Bill Cycle Detail</title>

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
    var patmast_episode = {
        @foreach($patmast_episode as $key => $val)
            '{{$key}}' : '{{$val}}',
        @endforeach
    };

    var chargetrx=[
        @foreach($chargetrx as $key => $chargetrx1)
        {
            @foreach($chargetrx1 as $key2 => $val)
                '{{$key2}}' : `{!!$val!!}`,
            @endforeach
        },
        @endforeach
    ];

    var chgclass=[
        @foreach($chgclass as $key => $chgclass1)
        {
            @foreach($chgclass1 as $key2 => $val)
                '{{$key2}}' : `{!!$val!!}`,
            @endforeach
        },
        @endforeach
    ];

    var invgroup=[
        @foreach($invgroup as $key => $invgroup1)
        {
            @foreach($invgroup1 as $key2 => $val)
                '{{$key2}}' : `{!!$val!!}`,
            @endforeach
        },
        @endforeach
    ];

    var username = '{{$username}}';
    var footer = `{!!$footer!!}`;

    $(document).ready(function () {
        var docDefinition = {
            header: function(currentPage, pageCount, pageSize) {
                var retval=[];
                var header_tbl = {
                        style: 'header_tbl',
                        table: {
                            headerRows: 1,
                            widths: [50,'*',65,'*'],//panjang standard dia 515
                            body: [
                                [
                                    {text: 'Debtor',bold: true}, 
                                    {text: ': '+patmast_episode.debtorcode},
                                    {text: 'Page',bold: true}, 
                                    {text: ': '+currentPage+' / '+pageCount},
                                ],
                                [
                                    {text: 'Name',bold: true}, 
                                    {text: ': '+patmast_episode.debtorname},
                                    {text: 'User',bold: true}, 
                                    {text: ': '+username},
                                ],
                                [
                                    {text: '',bold: true}, 
                                    {text: ': '+patmast_episode.address1},
                                    {text: 'Fin Class',bold: true}, 
                                    {text: ': '+patmast_episode.pay_type},
                                ],
                                [
                                    {text: '',bold: true}, 
                                    {text: ': '+patmast_episode.address2},
                                    {text: ''}, 
                                    {text: ''},
                                ],
                                [
                                    {text: '',bold: true}, 
                                    {text: ': '+patmast_episode.address3},
                                    {text: ''}, 
                                    {text: ''},
                                ],
                                [
                                    {text: 'Contact',bold: true}, 
                                    {text: ': '+patmast_episode.contact},
                                    {text: 'Gl No.',bold: true}, 
                                    {text: ': '+patmast_episode.refno},
                                ],
                                [{},{},{},{}],
                                [
                                    {text: 'MRN',bold: true}, 
                                    {text: ': '+pad('000000',patmast_episode.mrn,true)},
                                    {text: 'Episode No.',bold: true}, 
                                    {text: ': '+pad('0000',patmast_episode.episno,true)},
                                ],
                                [
                                    {text: 'Name',bold: true}, 
                                    {text: ': '+patmast_episode.name},
                                    {text: 'Register Date',bold: true}, 
                                    {text: ': '+dateFormatter(patmast_episode.reg_date)+' '+patmast_episode.reg_time},
                                ],
                                [
                                    {text: 'I/C',bold: true}, 
                                    {text: ': '+patmast_episode.newic},
                                    {text: 'I/D',bold: true}, 
                                    {text: ': '},
                                ],
                                [
                                    {text: 'Doctor',bold: true}, 
                                    {text: ': '+patmast_episode.doc_name,colSpan: 2},
                                    {}, 
                                    {},
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    }

                var image = {image: 'letterhead', width: 200, height: 40, style: 'tableHeader', colSpan: 5, alignment: 'center',margin:[0,10,0,0]};
                retval.push(image);

                retval.push(header_tbl);
                return retval

            },
            footer: function(currentPage, pageCount) {
                return [
                  { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                ]
            },
            pageSize: 'A4',
            pageMargins: [30, 205, 20, 50],
            content: [
                {
                    style: 'tableExample',
                    table: {
                        headerRows: 1,
                        dontBreakRows: true,
                        widths: [80,180,60,40,60,60],//panjang standard dia 515
                        body: make_body()
                    }
                },
                {
                    table: {
                        headerRows: 1,
                        dontBreakRows: true,
                        widths: ['*'],//panjang standard dia 515
                        body: [
                            [
                                {text: footer,fontSize: 9,margin: [0, 20, 0, 20]}

                            ]
                        ]
                    },layout: 'noBorders',
                },
                {
                    text: 'THIS IS COMPUTER GENERATED DOCUMENT. NO SIGNATURE IS REQUIRED.',fontSize: 9
                }
            ],
            styles: {
                tableExample: {
                    fontSize: 9,
                    margin: [0, 5, 0, 0]
                },
                header_tbl: {
                    fontSize: 9,
                    margin: [30, 5, 40, 20]
                },
                body_ttl: {
                    margin: [0, 2, 0, 2]
                },
                body_row: {
                    margin: [10, 0, 0, 0]
                },
                body_row_2: {
                    margin: [0, 5, 0, 5],
                    bold:true
                }
            },
            images: {
                letterhead: {
                    url: '{{asset('/img/letterheadukm.png')}}',
                    headers: {
                        myheader: '123',
                        myotherheader: 'abc',
                    }
                }
            }
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
                {text:'Price Code',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
                {text:'Description',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
                {text:'Trans Date',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
                {text:'Qty',bold: true, style: 'body_ttl',alignment: 'left',border: [false, true, false, true]},
                {text:'Amount (RM)',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]},
                {text:'GST (RM)',bold: true, style: 'body_ttl',alignment: 'right',border: [false, true, false, true]}
            ]
        ];

        let total_all=0;
        let total_depo=0;
        let total_sum=0;
        chgclass.forEach(function(e_cc,i_c){
            var total_sub = 0;
            let arrsub_h =  [
                    {text:e_cc.chgc_desc,colSpan:3, bold:true, margin:[0,10,0,0], border: [false, false, false, false]},
                    {},
                    {},
                    {text:'', margin:[0,8,0,0], border: [false, false, false, false]},
                    {text:'',alignment: 'right', margin:[0,10,0,0], border: [false, false, false, false]},
                    {text:'', margin:[0,8,0,0], border: [false, false, false, false]},
                ];
            retval.push(arrsub_h);

            invgroup.forEach(function(e_inv,i_inv){
                if(e_cc.chgclass == e_inv.chgclass){
                    let total_inv = 0;
                    chargetrx.forEach(function(e_trx,i_trx){
                        if(e_inv.pdescription == e_trx.pdescription){
                            let arr1 = [
                                {text:e_trx.chgcode, style: 'body_row', border: [false, false, false, false], margin:[10,0,0,0]},
                                {text:e_trx.description, style: 'body_row', border: [false, false, false, false]},
                                {text:dateFormatter(e_trx.trxdate), style: 'body_row', border: [false, false, false, false]},
                                {text:e_trx.quantity, style: 'body_row', border: [false, false, false, false]},
                                {text:myparseFloat(e_trx.amount),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
                                {text:myparseFloat(e_trx.taxamount),alignment: 'right', style: 'body_row', border: [false, false, false, false]},
                            ];
                            retval.push(arr1);
                            total_inv = parseFloat_(total_inv) + parseFloat_(e_trx.amount);
                        }
                    });
                    let arrtot =  [
                            {text:'',style: 'body_row_2', border: [false, false, false, false]},
                            {text:e_inv.pdescription, style: 'body_row_2', border: [false, false, false, false]},
                            {text:'',style: 'body_row_2', border: [false, false, false, false]},
                            {text:'', style: 'body_row_2',border: [false, false, false, false]},
                            {text:myparseFloat(total_inv),alignment: 'right', style: 'body_row_2', border: [false, false, false, false]},
                            {text:'',style: 'body_row_2', border: [false, false, false, false]},
                        ];
                    retval.push(arrtot);
                    total_sub = parseFloat_(total_sub) + parseFloat_(total_inv);
                }
                
            });
            let arrsub =  [
                    {text:'', margin:[0,8,0,0], border: [false, false, false, false]},
                    {text:'', margin:[0,8,0,0], border: [false, false, false, false]},
                    {text:'Sub-Total', margin:[0,8,0,0], border: [false, false, false, false]},
                    {text:'', margin:[0,8,0,0], border: [false, false, false, false]},
                    {text:myparseFloat(total_sub),alignment: 'right', margin:[0,8,0,0], border: [false, false, false, false]},
                    {text:'', margin:[0,8,0,0], border: [false, false, false, false]},
                ];
            retval.push(arrsub);
            total_sum = parseFloat_(total_sum) + parseFloat_(total_sub);
        });
        total_all = parseFloat_(total_sum)-parseFloat_(total_depo);

        let arr_sum =  [
                {text:'TOTAL BILL AMOUNT', margin:[0,8,0,0], colSpan:4, border: [false, false, false, false]},
                {},
                {},
                {},
                {text:myparseFloat(total_sum),alignment: 'right', margin:[0,8,0,0], border: [false, false, false, false]},
                {text:'',alignment: 'right', margin:[0,8,0,0], border: [false, false, false, false]},
            ];
        retval.push(arr_sum);
        let arr_depo =  [
                {text:'DEPOSIT/PAYMENT PAID', colSpan:4, border: [false, false, false, false]},
                {},
                {},
                {},
                {text:myparseFloat(total_depo),alignment: 'right', border: [false, false, false, false]},
                {text:'',alignment: 'right', border: [false, false, false, false]},
            ];
        retval.push(arr_depo);
        let arr_all =  [
                {text:'TOTAL AMOUNT TO BE PAID/(REFUND)', colSpan:4, border: [false, false, false, false]},
                {},
                {},
                {},
                {text:myparseFloat(total_all),alignment: 'right', border: [false, false, false, false]},
                {text:'',alignment: 'right', border: [false, false, false, false]},
            ];
        retval.push(arr_all);

        return retval;
    }

    function dateFormatter(val){
        if(val == '') return '';
        if(val == null) return '';
        return moment(val).format("DD-MM-YYYY");
    } 

    function myparseFloat(val){
        if(val == '') return '0.00';
        if(val == null) return '0.00';
        return numeral(val).format('0,0.00');
    } 

    function parseFloat_(val){
        if(val == '') return 0;
        if(val == null) return 0;
        return parseFloat(val);
    } 

    function myparseFloatVV(unitcost,dspqty){
        if(dspqty == null) return '0.00';
        if(dspqty.trim() == '') return '0.00';
        return numeral(unitcost*dspqty).format('0,0.00');
    } 

    function pad(pad, str, padLeft) {
        if (typeof str === 'undefined') 
            return pad;
        if (str == '') 
            return '';
        if (str == undefined) 
            return '';
        if (str == null) 
            return '';
        if (padLeft) {
            return (pad + str).slice(-pad.length);
        } else {
            return (str + pad).substring(0, pad.length);
        }
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