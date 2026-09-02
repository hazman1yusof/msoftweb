<!DOCTYPE html>
<html>
    <head>
        <title>Endoscopy Notes (Stomach)</title>
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
        
        $(document).ready(function (){
            var docDefinition = {
                footer: function (currentPage, pageCount){
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                // pageOrientation: 'landscape',
                content: [
                    {
                        image: 'letterhead', width: 500, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nENDOSCOPY NOTES\n',
                        style: 'header',
                        alignment: 'center',
                        decoration: 'underline',
                    },
                    // { canvas: [ { type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 0.5 } ] },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: ['*','*'], // panjang standard dia 515
                            body: [
                                [
                                    {
                                        text: [
                                            { text: `NAME : \u200B\t{!!$endoscopystomach->Name!!}` },
                                            { text: '\u200B\tSEX : \u200B\t{{$endoscopystomach->Sex}}' },
                                        ],
                                    },
                                    { text: 'AGE : \u200B\t{{$age}}' },
                                ],
                                [
                                    { text: 'REFERRED BY : \u200B\t{{$endoscopystomach->referredBy}}', colSpan: 2 },
                                ],
                                [
                                    { text: 'ENDOSCOPIST : \u200B\t{{$endoscopystomach->endoscopist}}', colSpan: 2 },
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: [240,240],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    {
                                        text: [
                                            { text: '\nPREVIOUS SCOPY', decoration: 'underline', bold: true },
                                            { text: `\n\n{!!$endoscopystomach->previousScopy!!}\n\n` },
                                        ],
                                    },
                                    {
                                        text: [
                                            { text: '\nCOMPLAINTS', decoration: 'underline', bold: true },
                                            { text: `\n\n{!!$endoscopystomach->complaints!!}\n\n` },
                                        ],
                                    },
                                ],
                                [
                                    {
                                        text: [
                                            { text: '\nOESOPHAGUS', decoration: 'underline', bold: true },
                                            { text: `\n\n{!!$endoscopystomach->oesophagus!!}\n\n` },
                                        ],
                                    },
                                    {
                                        text: [
                                            { text: '\nSTOMACH', decoration: 'underline', bold: true },
                                            { text: `\n\n{!!$endoscopystomach->stomach!!}\n\n` },
                                        ],
                                    },
                                ],
                                [
                                    {
                                        text: [
                                            { text: '\nDUODENUM', decoration: 'underline', bold: true },
                                            { text: `\n\n{!!$endoscopystomach->duodenum!!}\n\n` },
                                        ], colSpan: 2
                                    },
                                ],
                                [
                                    {
                                        text: [
                                            { text: '\nREMARKS/BIOPSY', decoration: 'underline', bold: true },
                                            { text: `\n\n{!!$endoscopystomach->remarks!!}\n\n` },
                                        ],
                                    },
                                    {
                                        text: [
                                            { text: '\nTREATMENT', decoration: 'underline', bold: true },
                                            { text: `\n\n{!!$endoscopystomach->treatment!!}\n\n` },
                                        ],
                                    },
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                // return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
                    },
                ],
                styles: {
                    header: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    },
                    subheader: {
                        fontSize: 12,
                        bold: true,
                        margin: [0, 10, 0, 5]
                    },
                    tableExample: {
                        fontSize: 9,
                        margin: [0, 5, 0, 15]
                    },
                    tableExample1: {
                        fontSize: 9,
                        margin: [110, 5, 0, 10]
                    },
                    tableExample2: {
                        fontSize: 9,
                        margin: [60, 5, 0, 10]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
                        color: 'black'
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
            
            pdfMake.createPdf(docDefinition).getBase64(function (dataURL){
                base64_pr = dataURL;
                
                var obj = {
                    base64: dataURL,
                    _token: $('#_token').val(),
                    merge_key: merge_key,
                    lineno_: 1
                };
                
                $.post('../attachment_upload/form?page=merge_pdf', $.param(obj), function (data){
                }).done(function (data){
                });
            });
        });
        
        $(document).ready(function (){
            $('div.canclick').click(function (){
                $('div.canclick').removeClass('teal inverted');
                $(this).addClass('teal inverted');
                var goto = $(this).data('goto');
                
                if($(goto).offset() != undefined){
                    $('html, body').animate({
                        scrollTop: $(goto).offset().top
                    }, 500, function (){
                        
                    });
                }
            });
            
            $('#merge_btn').click(function (){
                let attach_array = [];
                $('input:checkbox:checked').each(function (){
                    attach_array.push($(this).data('src'));
                });
                
                if(attach_array.length > 0){
                    var obj = {
                        page: 'merge_pdf_with_attachment',
                        merge_key: merge_key,
                        attach_array: attach_array
                    };
                    
                    $('#pdfiframe_merge').attr('src',"../attachment_upload/table?"+$.param(obj));
                    $('#btn_merge,#pdfiframe_merge').show();
                    $('#btn_merge').click();
                }else{
                    alert('Select at least 1 PDF Attachment to merge with main PDF');
                }
            });
            
            $('#ref_dropdown.ui.dropdown')
            .dropdown({
                onChange: function (value, text, $selectedItem){
                    window.open(value);
                }
            });
        });
        
        function makeid(length){
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            const charactersLength = characters.length;
            let counter = 0;
            while(counter < length){
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
                counter += 1;
            }
            return result;
        }
        
        function populate_attachmentfile(){
            $('#pdfiframe_1').attr('src',attachmentfiles);
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
        <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
        <div class="ui segments" style="width: 18vw; height: 95vh; float: left; margin: 10px; position: fixed;">
            <div class="ui secondary segment">
                <h3>
                    <b>Navigation</b>
                    <!-- <button id="merge_btn" class="ui small primary button" style="font-size: 12px; padding: 6px 10px; float: right;">Merge</button> -->
                </h3>
            </div>
            <div class="ui segment teal inverted canclick" style="cursor: pointer;" data-goto='#pdfiframe'>
                <p>Endoscopy Notes (Stomach)</p>
            </div>
            @if($attachment_files != '')
            <div class="ui segment canclick" style="cursor: pointer;" data-goto='#pdfiframe_1'>
                <!-- <p>IMAGE<input type="checkbox" data-src="{{$attachment_files}}" name="1" style="float: right; margin-right: 5px;"></p> -->
                <p>IMAGE</p>
            </div>
            @endif
            <div id="btn_merge" class="ui segment canclick" style="cursor: pointer; display: none;" data-goto='#pdfiframe_merge'>
                <p>Merged File</p>
            </div>
        </div>
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 79vw; height: 100vh; float: right;"></iframe>
        
        @if($attachment_files != '')
        <iframe id="pdfiframe_1" width="100%" height="100%" src="{{$attachment_files}}" frameborder="0" style="width: 79vw; height: 100vh; float: right;"></iframe>
        @endif
        <iframe id="pdfiframe_merge" width="100%" height="100%" src="" frameborder="0" style="width: 79vw; height: 100vh; float: right; display: none;"></iframe>
        
        <!-- <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw; height: 99vh;"></iframe> -->
    </body>
</html>