<!DOCTYPE html>
<html>
    <head>
        <title>Bladder Irrigation</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        $(document).ready(function (){
            var docDefinition = {
                footer: function (currentPage, pageCount){
                    return [
                        // { text: 'This is computer-generated document. No signature is required.', italics: true, alignment: 'center', fontSize: 9 },
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center', fontSize: 9 }
                    ]
                },
                pageSize: 'A4',
                // pageMargins: [10, 20, 20, 30],
                content: [
                    {
                        image: 'letterhead', width: 500, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: 'BLADDER IRRIGATION\n\n',
                        style: 'header',
                        alignment: 'center'
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [50,1,'*',80,1,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'PATIENT NAME', colSpan:2 },{},
                                    { text: ':\u200B\t{{$pat_mast->Name}}', colSpan:4},{},{},{},
                                ],
                                [
                                    { text: 'MRN', colSpan:2 },{},
                                    { text: ':\u200B\t{{str_pad($pat_mast->MRN, 7, "0", STR_PAD_LEFT)}}' },
                                    { text: 'ROOM NO', colSpan:2 },{},
                                    { text: ':\u200B\t{{$pat_mast->ward}} / {{$pat_mast->bednum}}' }
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [60,60,35,35,35,35,'*',45], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'DATE', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: 'TIME', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: 'INPUT', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: 'OUTPUT', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '+VE', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '-VE', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: 'REMARK', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: 'STAFF', style: 'tableHeader', fillColor: '#dddddd' },
                                ],
                                @php($tot_i1 = 0)
                                @php($tot_o1 = 0)
                                @foreach ($bladder as $obj)
                                    @if($obj->shift == '1')
                                    [
                                        { text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->entereddate)->format('d-m-Y')}}' },
                                        { text: '{{\Carbon\Carbon::createFromFormat('H:i:s',$obj->enteredtime)->format('H:i')}}' },
                                        { text: '{{number_format($obj->input,2)}}', alignment: 'right' },
                                        { text: '{{number_format($obj->output,2)}}', alignment: 'right' },
                                        { text: '{{number_format($obj->positive,2)}}', alignment: 'right' },
                                        { text: '{{number_format($obj->negative,2)}}', alignment: 'right' },
                                        { text: `{!!$obj->remarks!!}` },
                                        { text: '{{$obj->adduser}}' },
                                    ],
                                    @php($tot_i1 += $obj->input)
                                    @php($tot_o1 += $obj->output)
                                    @endif
                                @endforeach
                                [
                                    { text: 'TOTAL', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '8AM-2PM', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '{{number_format($tot_i1,2)}}', style: 'tableHeader', alignment: 'right', fillColor: '#dddddd' },
                                    { text: '{{number_format($tot_o1,2)}}', style: 'tableHeader', alignment: 'right', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                ],
                                @php($tot_i2 = 0)
                                @php($tot_o2 = 0)
                                @foreach ($bladder as $obj)
                                    @if($obj->shift == '2')
                                    [
                                        { text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->entereddate)->format('d-m-Y')}}' },
                                        { text: '{{\Carbon\Carbon::createFromFormat('H:i:s',$obj->enteredtime)->format('H:i')}}' },
                                        { text: '{{number_format($obj->input,2)}}', alignment: 'right' },
                                        { text: '{{number_format($obj->output,2)}}', alignment: 'right' },
                                        { text: '{{number_format($obj->positive,2)}}', alignment: 'right' },
                                        { text: '{{number_format($obj->negative,2)}}', alignment: 'right' },
                                        { text: `{!!$obj->remarks!!}` },
                                        { text: '{{$obj->adduser}}' },
                                    ],
                                    @php($tot_i2 += $obj->input)
                                    @php($tot_o2 += $obj->output)
                                    @endif
                                @endforeach
                                [
                                    { text: 'TOTAL', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '3PM-9PM', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '{{number_format($tot_i2,2)}}', style: 'tableHeader', alignment: 'right', fillColor: '#dddddd' },
                                    { text: '{{number_format($tot_o2,2)}}', style: 'tableHeader', alignment: 'right', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                ],
                                @php($tot_i3 = 0)
                                @php($tot_o3 = 0)
                                @foreach ($bladder as $obj)
                                    @if($obj->shift == '3')
                                    [
                                        { text: '{{\Carbon\Carbon::createFromFormat('Y-m-d',$obj->entereddate)->format('d-m-Y')}}' },
                                        { text: '{{\Carbon\Carbon::createFromFormat('H:i:s',$obj->enteredtime)->format('H:i')}}' },
                                        { text: '{{number_format($obj->input,2)}}', alignment: 'right' },
                                        { text: '{{number_format($obj->output,2)}}', alignment: 'right' },
                                        { text: '{{number_format($obj->positive,2)}}', alignment: 'right' },
                                        { text: '{{number_format($obj->negative,2)}}', alignment: 'right' },
                                        { text: `{!!$obj->remarks!!}` },
                                        { text: '{{$obj->adduser}}' },
                                    ],
                                    @php($tot_i3 += $obj->input)
                                    @php($tot_o3 += $obj->output)
                                    @endif
                                @endforeach
                                [
                                    { text: 'TOTAL', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '10PM-7AM', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '{{number_format($tot_i3,2)}}', style: 'tableHeader', alignment: 'right', fillColor: '#dddddd' },
                                    { text: '{{number_format($tot_o3,2)}}', style: 'tableHeader', alignment: 'right', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                ],
                    
                                [
                                    { text: 'GRAND TOTAL', style: 'tableHeader', fillColor: '#dddddd'},
                                    { text: '7AM/7PM', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '{{number_format(($tot_i1)+($tot_i2)+($tot_i3),2)}}', style: 'tableHeader', alignment: 'right', fillColor: '#dddddd' },
                                    { text: '{{number_format(($tot_o1)+($tot_o2)+($tot_o3),2)}}', style: 'tableHeader', alignment: 'right', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                ],
                                [
                                    { text: '', border: [false, true, false, false] },
                                    { text: '', border: [false, true, false, false] },
                                    { text: '', border: [false, true, false, false] },
                                    { text: '', border: [false, true, false, false] },
                                    { text: '', border: [false, true, false, false] },//ltrb
                                    { text: 'TOTAL INPUT', style: 'tableHeader', colSpan: 2, fillColor: '#dddddd'},
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '{{number_format(($tot_i1)+($tot_i2)+($tot_i3),2)}}', style: 'tableHeader', alignment: 'right' },
                                ],
                                [
                                    { text: '', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] },//ltrb
                                    { text: 'TOTAL OUTPUT', style: 'tableHeader',colSpan: 2, fillColor: '#dddddd'},
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '{{number_format(($tot_o1)+($tot_o2)+($tot_o3),2)}}', style: 'tableHeader', alignment: 'right' },
                                ],[
                                    { text: '', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] },
                                    { text: '', border: [false, false, false, false] },//ltrb
                                    { text: 'BALANCE', style: 'tableHeader', colSpan: 2, fillColor: '#dddddd'},
                                    { text: '', style: 'tableHeader', fillColor: '#dddddd' },
                                    { text: '{{number_format((($tot_i1)+($tot_i2)+($tot_i3))-number_format(($tot_o1)+($tot_o2)+($tot_o3)),2)}}', style: 'tableHeader', alignment: 'right' },
                                ],
                            ],
                        },
                    },
                    
                ],
                styles: {
                    header: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 10, 0, 0]
                    },
                    subheader: {
                        fontSize: 16,
                        bold: true,
                        margin: [0, 10, 0, 5]
                    },
                    tableExample: {
                        fontSize: 8,
                        margin: [0, 5, 0, 10]
                    },
                    tableDetail: {
                        fontSize: 7.5,
                        margin: [0, 0, 0, 8]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
                        margin: [0, 0, 0, 0],
                        color: 'black'
                    },
                    totalbold: {
                        bold: true,
                        fontSize: 10,
                    },
                    comp_header: {
                        bold: true,
                        fontSize: 8,
                    }
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
            
            // pdfMake.createPdf(docDefinition).getBase64(function (data){
            //     var base64data = "data:base64"+data;
            //     console.log($('object#pdfPreview').attr('data',base64data));
            //     document.getElementById('pdfPreview').data = base64data;
            // });
            
            pdfMake.createPdf(docDefinition).getDataUrl(function (dataURL){
                $('#pdfiframe').attr('src',dataURL);
            });
        });
        
        function make_header(){
            
        }
        
        // pdfMake.createPdf(docDefinition).getDataUrl(function (dataURL){
        //     console.log(dataURL);
        //     document.getElementById('pdfPreview').data = dataURL;
        // });
        
        // jsreport.serverUrl = 'http://localhost:5488'
        // async function preview(){
        //     const report = await jsreport.render({
        //         template: {
        //             name: 'mc'
        //         },
        //         data: mydata
        //     });
        //     document.getElementById('pdfPreview').data = await report.toObjectURL()
        // }
        
        // preview().catch(console.error)
    </script>
    
    <body style="margin: 0px;">
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>
    </body>
</html>