<!DOCTYPE html>
<html>
    <head>
        <title>INTAKE OUTPUT CHART</title>
    </head>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/moment.js') }}"></script>
    <script src="{{ asset('plugins/numeral.min.js') }}"></script>
    
    </object>
    <script>

        var oraltype=[
                '{{$intakeoutput->oraltype1}}',
                '{{$intakeoutput->oraltype2}}',
                '{{$intakeoutput->oraltype3}}',
                '{{$intakeoutput->oraltype4}}',
                '{{$intakeoutput->oraltype5}}',
                '{{$intakeoutput->oraltype6}}',
                '{{$intakeoutput->oraltype7}}',
                '{{$intakeoutput->oraltype8}}',
                '{{$intakeoutput->oraltype9}}',
                '{{$intakeoutput->oraltype10}}',
                '{{$intakeoutput->oraltype11}}',
                '{{$intakeoutput->oraltype12}}',
                '{{$intakeoutput->oraltype13}}',
                '{{$intakeoutput->oraltype14}}',
                '{{$intakeoutput->oraltype15}}',
                '{{$intakeoutput->oraltype16}}',
                '{{$intakeoutput->oraltype17}}',
                '{{$intakeoutput->oraltype18}}',
                '{{$intakeoutput->oraltype19}}',
                '{{$intakeoutput->oraltype20}}',
                '{{$intakeoutput->oraltype21}}',
                '{{$intakeoutput->oraltype22}}',
                '{{$intakeoutput->oraltype23}}',
                '{{$intakeoutput->oraltype24}}',
            ];
        var oralamt=[
                '{{$intakeoutput->oralamt1}}',
                '{{$intakeoutput->oralamt2}}',
                '{{$intakeoutput->oralamt3}}',
                '{{$intakeoutput->oralamt4}}',
                '{{$intakeoutput->oralamt5}}',
                '{{$intakeoutput->oralamt6}}',
                '{{$intakeoutput->oralamt7}}',
                '{{$intakeoutput->oralamt8}}',
                '{{$intakeoutput->oralamt9}}',
                '{{$intakeoutput->oralamt10}}',
                '{{$intakeoutput->oralamt11}}',
                '{{$intakeoutput->oralamt12}}',
                '{{$intakeoutput->oralamt13}}',
                '{{$intakeoutput->oralamt14}}',
                '{{$intakeoutput->oralamt15}}',
                '{{$intakeoutput->oralamt16}}',
                '{{$intakeoutput->oralamt17}}',
                '{{$intakeoutput->oralamt18}}',
                '{{$intakeoutput->oralamt19}}',
                '{{$intakeoutput->oralamt20}}',
                '{{$intakeoutput->oralamt21}}',
                '{{$intakeoutput->oralamt22}}',
                '{{$intakeoutput->oralamt23}}',
                '{{$intakeoutput->oralamt24}}',
            ];
        var intratype=[
                '{{$intakeoutput->intratype1}}',
                '{{$intakeoutput->intratype2}}',
                '{{$intakeoutput->intratype3}}',
                '{{$intakeoutput->intratype4}}',
                '{{$intakeoutput->intratype5}}',
                '{{$intakeoutput->intratype6}}',
                '{{$intakeoutput->intratype7}}',
                '{{$intakeoutput->intratype8}}',
                '{{$intakeoutput->intratype9}}',
                '{{$intakeoutput->intratype10}}',
                '{{$intakeoutput->intratype11}}',
                '{{$intakeoutput->intratype12}}',
                '{{$intakeoutput->intratype13}}',
                '{{$intakeoutput->intratype14}}',
                '{{$intakeoutput->intratype15}}',
                '{{$intakeoutput->intratype16}}',
                '{{$intakeoutput->intratype17}}',
                '{{$intakeoutput->intratype18}}',
                '{{$intakeoutput->intratype19}}',
                '{{$intakeoutput->intratype20}}',
                '{{$intakeoutput->intratype21}}',
                '{{$intakeoutput->intratype22}}',
                '{{$intakeoutput->intratype23}}',
                '{{$intakeoutput->intratype24}}',
            ];
        var intraamt=[
                '{{$intakeoutput->intraamt1}}',
                '{{$intakeoutput->intraamt2}}',
                '{{$intakeoutput->intraamt3}}',
                '{{$intakeoutput->intraamt4}}',
                '{{$intakeoutput->intraamt5}}',
                '{{$intakeoutput->intraamt6}}',
                '{{$intakeoutput->intraamt7}}',
                '{{$intakeoutput->intraamt8}}',
                '{{$intakeoutput->intraamt9}}',
                '{{$intakeoutput->intraamt10}}',
                '{{$intakeoutput->intraamt11}}',
                '{{$intakeoutput->intraamt12}}',
                '{{$intakeoutput->intraamt13}}',
                '{{$intakeoutput->intraamt14}}',
                '{{$intakeoutput->intraamt15}}',
                '{{$intakeoutput->intraamt16}}',
                '{{$intakeoutput->intraamt17}}',
                '{{$intakeoutput->intraamt18}}',
                '{{$intakeoutput->intraamt19}}',
                '{{$intakeoutput->intraamt20}}',
                '{{$intakeoutput->intraamt21}}',
                '{{$intakeoutput->intraamt22}}',
                '{{$intakeoutput->intraamt23}}',
                '{{$intakeoutput->intraamt24}}',
            ];
        var othertype=[
                '{{$intakeoutput->othertype1}}',
                '{{$intakeoutput->othertype2}}',
                '{{$intakeoutput->othertype3}}',
                '{{$intakeoutput->othertype4}}',
                '{{$intakeoutput->othertype5}}',
                '{{$intakeoutput->othertype6}}',
                '{{$intakeoutput->othertype7}}',
                '{{$intakeoutput->othertype8}}',
                '{{$intakeoutput->othertype9}}',
                '{{$intakeoutput->othertype10}}',
                '{{$intakeoutput->othertype11}}',
                '{{$intakeoutput->othertype12}}',
                '{{$intakeoutput->othertype13}}',
                '{{$intakeoutput->othertype14}}',
                '{{$intakeoutput->othertype15}}',
                '{{$intakeoutput->othertype16}}',
                '{{$intakeoutput->othertype17}}',
                '{{$intakeoutput->othertype18}}',
                '{{$intakeoutput->othertype19}}',
                '{{$intakeoutput->othertype20}}',
                '{{$intakeoutput->othertype21}}',
                '{{$intakeoutput->othertype22}}',
                '{{$intakeoutput->othertype23}}',
                '{{$intakeoutput->othertype24}}',
            ];
        var otheramt=[
                '{{$intakeoutput->otheramt1}}',
                '{{$intakeoutput->otheramt2}}',
                '{{$intakeoutput->otheramt3}}',
                '{{$intakeoutput->otheramt4}}',
                '{{$intakeoutput->otheramt5}}',
                '{{$intakeoutput->otheramt6}}',
                '{{$intakeoutput->otheramt7}}',
                '{{$intakeoutput->otheramt8}}',
                '{{$intakeoutput->otheramt9}}',
                '{{$intakeoutput->otheramt10}}',
                '{{$intakeoutput->otheramt11}}',
                '{{$intakeoutput->otheramt12}}',
                '{{$intakeoutput->otheramt13}}',
                '{{$intakeoutput->otheramt14}}',
                '{{$intakeoutput->otheramt15}}',
                '{{$intakeoutput->otheramt16}}',
                '{{$intakeoutput->otheramt17}}',
                '{{$intakeoutput->otheramt18}}',
                '{{$intakeoutput->otheramt19}}',
                '{{$intakeoutput->otheramt20}}',
                '{{$intakeoutput->otheramt21}}',
                '{{$intakeoutput->otheramt22}}',
                '{{$intakeoutput->otheramt23}}',
                '{{$intakeoutput->otheramt24}}',
            ];
        var urineamt=[
                '{{$intakeoutput->urineamt1}}',
                '{{$intakeoutput->urineamt2}}',
                '{{$intakeoutput->urineamt3}}',
                '{{$intakeoutput->urineamt4}}',
                '{{$intakeoutput->urineamt5}}',
                '{{$intakeoutput->urineamt6}}',
                '{{$intakeoutput->urineamt7}}',
                '{{$intakeoutput->urineamt8}}',
                '{{$intakeoutput->urineamt9}}',
                '{{$intakeoutput->urineamt10}}',
                '{{$intakeoutput->urineamt11}}',
                '{{$intakeoutput->urineamt12}}',
                '{{$intakeoutput->urineamt13}}',
                '{{$intakeoutput->urineamt14}}',
                '{{$intakeoutput->urineamt15}}',
                '{{$intakeoutput->urineamt16}}',
                '{{$intakeoutput->urineamt17}}',
                '{{$intakeoutput->urineamt18}}',
                '{{$intakeoutput->urineamt19}}',
                '{{$intakeoutput->urineamt20}}',
                '{{$intakeoutput->urineamt21}}',
                '{{$intakeoutput->urineamt22}}',
                '{{$intakeoutput->urineamt23}}',
                '{{$intakeoutput->urineamt24}}',
            ];
        var vomitamt=[
                '{{$intakeoutput->vomitamt1}}',
                '{{$intakeoutput->vomitamt2}}',
                '{{$intakeoutput->vomitamt3}}',
                '{{$intakeoutput->vomitamt4}}',
                '{{$intakeoutput->vomitamt5}}',
                '{{$intakeoutput->vomitamt6}}',
                '{{$intakeoutput->vomitamt7}}',
                '{{$intakeoutput->vomitamt8}}',
                '{{$intakeoutput->vomitamt9}}',
                '{{$intakeoutput->vomitamt10}}',
                '{{$intakeoutput->vomitamt11}}',
                '{{$intakeoutput->vomitamt12}}',
                '{{$intakeoutput->vomitamt13}}',
                '{{$intakeoutput->vomitamt14}}',
                '{{$intakeoutput->vomitamt15}}',
                '{{$intakeoutput->vomitamt16}}',
                '{{$intakeoutput->vomitamt17}}',
                '{{$intakeoutput->vomitamt18}}',
                '{{$intakeoutput->vomitamt19}}',
                '{{$intakeoutput->vomitamt20}}',
                '{{$intakeoutput->vomitamt21}}',
                '{{$intakeoutput->vomitamt22}}',
                '{{$intakeoutput->vomitamt23}}',
                '{{$intakeoutput->vomitamt24}}',
            ];
        var aspamt=[
                '{{$intakeoutput->aspamt1}}',
                '{{$intakeoutput->aspamt2}}',
                '{{$intakeoutput->aspamt3}}',
                '{{$intakeoutput->aspamt4}}',
                '{{$intakeoutput->aspamt5}}',
                '{{$intakeoutput->aspamt6}}',
                '{{$intakeoutput->aspamt7}}',
                '{{$intakeoutput->aspamt8}}',
                '{{$intakeoutput->aspamt9}}',
                '{{$intakeoutput->aspamt10}}',
                '{{$intakeoutput->aspamt11}}',
                '{{$intakeoutput->aspamt12}}',
                '{{$intakeoutput->aspamt13}}',
                '{{$intakeoutput->aspamt14}}',
                '{{$intakeoutput->aspamt15}}',
                '{{$intakeoutput->aspamt16}}',
                '{{$intakeoutput->aspamt17}}',
                '{{$intakeoutput->aspamt18}}',
                '{{$intakeoutput->aspamt19}}',
                '{{$intakeoutput->aspamt20}}',
                '{{$intakeoutput->aspamt21}}',
                '{{$intakeoutput->aspamt22}}',
                '{{$intakeoutput->aspamt23}}',
                '{{$intakeoutput->aspamt24}}',
            ];
        var otherout=[
                '{{$intakeoutput->otherout1}}',
                '{{$intakeoutput->otherout2}}',
                '{{$intakeoutput->otherout3}}',
                '{{$intakeoutput->otherout4}}',
                '{{$intakeoutput->otherout5}}',
                '{{$intakeoutput->otherout6}}',
                '{{$intakeoutput->otherout7}}',
                '{{$intakeoutput->otherout8}}',
                '{{$intakeoutput->otherout9}}',
                '{{$intakeoutput->otherout10}}',
                '{{$intakeoutput->otherout11}}',
                '{{$intakeoutput->otherout12}}',
                '{{$intakeoutput->otherout13}}',
                '{{$intakeoutput->otherout14}}',
                '{{$intakeoutput->otherout15}}',
                '{{$intakeoutput->otherout16}}',
                '{{$intakeoutput->otherout17}}',
                '{{$intakeoutput->otherout18}}',
                '{{$intakeoutput->otherout19}}',
                '{{$intakeoutput->otherout20}}',
                '{{$intakeoutput->otherout21}}',
                '{{$intakeoutput->otherout22}}',
                '{{$intakeoutput->otherout23}}',
                '{{$intakeoutput->otherout24}}',
            ];

        $(document).ready(function () {
            var docDefinition = {
                footer: function(currentPage, pageCount) {
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                pageMargins: [20, 20, 20, 50],
                content: [
                    {text:'ABC HOSPITAL',alignment: 'center', fontSize: 13, margin: [0, 0, 0, 0],bold:true},
                    {text:'B-101 IRIS APARTMENT',alignment: 'right', fontSize: 9, margin: [0, 3, 0, 0]},
                    {text:'PERSIARAN SAUJANA 4',alignment: 'right', fontSize: 9, margin: [0, 3, 0, 0]},
                    {text:'SAUJANA UTAMA',alignment: 'right', fontSize: 9, margin: [0, 3, 0, 0]},
                    {
                        table: {
                            widths: [25,'*'],//panjang standard dia 515
                            body: [
                                [
                                    {text: 'Name', fontSize: 9,bold: true,alignment: 'left'}, 
                                    {text: ': {{$pat_mast->Name}}', fontSize: 9,alignment: 'left'},
                                ],
                                [
                                    {text: 'MRN', fontSize: 9,bold: true,alignment: 'left'}, 
                                    {text: ': {{$pat_mast->MRN}}', fontSize: 9,alignment: 'left'},
                                ],
                                [
                                    {text: 'Date', fontSize: 9,bold: true,alignment: 'left'}, 
                                    {text: ': {{$intakeoutput->recorddate}}', fontSize: 9,alignment: 'left'},
                                ]
                            ]
                        },layout: 'noBorders',
                    },
                    {text:'INTAKE OUTPUT CHART',alignment: 'center', fontSize: 13, margin: [0, 5, 0, 0],bold:true},
                    {
                        table: {
                            dontBreakRows: true,
                            widths: [30,38,38,38,38,38,38,38,38,38,38,38],//panjang standard dia 515
                            body: make_body()
                        }
                    },
                ],
                styles: {
                    body_ttl: {
                        margin: [0, 0, 0, 0],bold: true,fontSize: 9
                    },
                    body_row: {
                        margin: [0, 0, 0, 0],fontSize: 7
                    },
                },
            };
            
            // pdfMake.createPdf(docDefinition).getBase64(function(data) {
            //     var base64data = "data:base64"+data;
            //     console.log($('object#pdfPreview').attr('data',base64data));
            //     // document.getElementById('pdfPreview').data = base64data;
            
            // });
            
            pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
                $('#pdfiframe').attr('src',dataURL);
            });
        });

        function make_body(){
            var retval = [
                [
                    {text:'DATE/TIME', style: 'body_ttl', alignment:'center',rowSpan: 3},
                    {text:'IN (ml)', style: 'body_ttl', alignment:'center',colSpan: 6},{},{},{},{},{},
                    {text:'OUT (ml)', style: 'body_ttl', alignment:'center',colSpan: 5},{},{},{},{}
                ],
                [
                    {},
                    {text:'ORAL', style: 'body_ttl', alignment:'center',colSpan: 2},{},
                    {text:'INTRA-VENA', style: 'body_ttl', alignment:'center',colSpan: 2},{},
                    {text:'OTHERS', style: 'body_ttl', alignment:'center',colSpan: 2},{},
                    {text:'TIME', style: 'body_ttl', alignment:'right',rowSpan: 2},
                    {text:'URINE', style: 'body_ttl', alignment:'right',rowSpan: 2},
                    {text:'VOMIT', style: 'body_ttl', alignment:'right',rowSpan: 2},
                    {text:'ASPIRATE', style: 'body_ttl', alignment:'right',rowSpan: 2},
                    {text:'OTHERS', style: 'body_ttl', alignment:'right',rowSpan: 2}
                ],
                [
                    {},
                    {text:'TYPE', alignment:'left', style: 'body_ttl'},
                    {text:'AMOUNT', alignment:'right', style: 'body_ttl'},
                    {text:'TYPE', alignment:'left', style: 'body_ttl'},
                    {text:'AMOUNT', alignment:'right', style: 'body_ttl'},
                    {text:'TYPE', alignment:'left', style: 'body_ttl'},
                    {text:'AMOUNT', alignment:'right', style: 'body_ttl'},
                    {},{},{},{},{}
                ]
            ];

            let time = moment('07:00', 'HH:mm');
            let tot_oralamt = 0;
            let tot_intraamt = 0;
            let tot_otheramt = 0;
            let tot_urineamt = 0;
            let tot_vomitamt = 0;
            let tot_aspamt = 0;
            let tot_otherout = 0;

            for (let i = 0; i < 8; i++) {
                retval.push([
                    {text:time.format('HH:mm'), style: 'body_row'},
                    {text:oraltype[i], style: 'body_row'},
                    {text:numeral(oralamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:intratype[i], style: 'body_row'},
                    {text:numeral(intraamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:othertype[i], style: 'body_row'},
                    {text:numeral(otheramt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:time.format('HH:mm'), style: 'body_row'},
                    {text:numeral(urineamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:numeral(vomitamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:numeral(aspamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:numeral(otherout[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                ]);
                time.add(1, 'hours');
                tot_oralamt+=numeral(oralamt[i]);
                tot_intraamt+=numeral(intraamt[i]);
                tot_otheramt+=numeral(otheramt[i]);
                tot_urineamt+=numeral(urineamt[i]);
                tot_vomitamt+=numeral(vomitamt[i]);
                tot_aspamt+=numeral(aspamt[i]);
                tot_otherout+=numeral(otherout[i]);
            }

            retval.push([
                {text:'', style: 'body_row'},
                {text:'Subtotal',bold:true, style: 'body_row'},
                {text:numeral(tot_oralamt).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:'Subtotal',bold:true, style: 'body_row'},
                {text:numeral(tot_intraamt).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:'Subtotal',bold:true, style: 'body_row'},
                {text:numeral(tot_otheramt).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:'Subtotal',bold:true, style: 'body_row'},
                {text:numeral(tot_urineamt).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:numeral(tot_vomitamt).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:numeral(tot_aspamt).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:numeral(tot_otherout).format('0,0.00'), style: 'body_row',alignment:'right'},
            ]);


            let time2 = moment('15:00', 'HH:mm');
            let tot_oralamt2 = 0;
            let tot_intraamt2 = 0;
            let tot_otheramt2 = 0;
            let tot_urineamt2 = 0;
            let tot_vomitamt2 = 0;
            let tot_aspamt2 = 0;
            let tot_otherout2 = 0;

            for (let i = 8; i < 16; i++) {
                retval.push([
                    {text:time2.format('HH:mm'), style: 'body_row'},
                    {text:oraltype[i], style: 'body_row'},
                    {text:numeral(oralamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:intratype[i], style: 'body_row'},
                    {text:numeral(intraamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:othertype[i], style: 'body_row'},
                    {text:numeral(otheramt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:time2.format('HH:mm'), style: 'body_row'},
                    {text:numeral(urineamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:numeral(vomitamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:numeral(aspamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:numeral(otherout[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                ]);
                time2.add(1, 'hours');
                tot_oralamt2+=numeral(oralamt[i]);
                tot_intraamt2+=numeral(intraamt[i]);
                tot_otheramt2+=numeral(otheramt[i]);
                tot_urineamt2+=numeral(urineamt[i]);
                tot_vomitamt2+=numeral(vomitamt[i]);
                tot_aspamt2+=numeral(aspamt[i]);
                tot_otherout2+=numeral(otherout[i]);
            }

            retval.push([
                {text:'', style: 'body_row'},
                {text:'Subtotal',bold:true, style: 'body_row'},
                {text:numeral(tot_oralamt2).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:'Subtotal',bold:true, style: 'body_row'},
                {text:numeral(tot_intraamt2).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:'Subtotal',bold:true, style: 'body_row'},
                {text:numeral(tot_otheramt2).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:'Subtotal',bold:true, style: 'body_row'},
                {text:numeral(tot_urineamt2).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:numeral(tot_vomitamt2).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:numeral(tot_aspamt2).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:numeral(tot_otherout2).format('0,0.00'), style: 'body_row',alignment:'right'},
            ]);


            let time3 = moment('23:00', 'HH:mm');
            let tot_oralamt3 = 0;
            let tot_intraamt3 = 0;
            let tot_otheramt3 = 0;
            let tot_urineamt3 = 0;
            let tot_vomitamt3 = 0;
            let tot_aspamt3 = 0;
            let tot_otherout3 = 0;

            for (let i = 16; i < 24; i++) {
                retval.push([
                    {text:time3.format('HH:mm'), style: 'body_row'},
                    {text:oraltype[i], style: 'body_row'},
                    {text:numeral(oralamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:intratype[i], style: 'body_row'},
                    {text:numeral(intraamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:othertype[i], style: 'body_row'},
                    {text:numeral(otheramt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:time3.format('HH:mm'), style: 'body_row'},
                    {text:numeral(urineamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:numeral(vomitamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:numeral(aspamt[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                    {text:numeral(otherout[i]).format('0,0.00'), style: 'body_row',alignment:'right'},
                ]);
                time3.add(1, 'hours');
                tot_oralamt3+=numeral(oralamt[i]);
                tot_intraamt3+=numeral(intraamt[i]);
                tot_otheramt3+=numeral(otheramt[i]);
                tot_urineamt3+=numeral(urineamt[i]);
                tot_vomitamt3+=numeral(vomitamt[i]);
                tot_aspamt3+=numeral(aspamt[i]);
                tot_otherout3+=numeral(otherout[i]);
            }

            retval.push([
                {text:'', style: 'body_row'},
                {text:'Subtotal',bold:true, style: 'body_row'},
                {text:numeral(tot_oralamt3).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:'Subtotal',bold:true, style: 'body_row'},
                {text:numeral(tot_intraamt3).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:'Subtotal',bold:true, style: 'body_row'},
                {text:numeral(tot_otheramt3).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:'Subtotal',bold:true, style: 'body_row'},
                {text:numeral(tot_urineamt3).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:numeral(tot_vomitamt3).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:numeral(tot_aspamt3).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:numeral(tot_otherout3).format('0,0.00'), style: 'body_row',alignment:'right'},
            ]);

            let grd_oralamt = tot_oralamt + tot_oralamt2 + tot_oralamt3;
            let grd_intraamt = tot_intraamt + tot_intraamt2 + tot_intraamt3;
            let grd_otheramt = tot_otheramt + tot_otheramt2 + tot_otheramt3;
            let grd_urineamt = tot_urineamt + tot_urineamt2 + tot_urineamt3;
            let grd_vomitamt = tot_vomitamt + tot_vomitamt2 + tot_vomitamt3;
            let grd_aspamt = tot_aspamt + tot_aspamt2 + tot_aspamt3;
            let grd_otherout = tot_otherout + tot_otherout2 + tot_otherout3;

            retval.push([
                {text:'', style: 'body_row'},
                {text:'Grand Total',bold:true, style: 'body_row'},
                {text:numeral(grd_oralamt).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:'Grand Total',bold:true, style: 'body_row'},
                {text:numeral(grd_intraamt).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:'Grand Total',bold:true, style: 'body_row'},
                {text:numeral(grd_otheramt).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:'Grand Total',bold:true, style: 'body_row'},
                {text:numeral(grd_urineamt).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:numeral(grd_vomitamt).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:numeral(grd_aspamt).format('0,0.00'), style: 'body_row',alignment:'right'},
                {text:numeral(grd_otherout).format('0,0.00'), style: 'body_row',alignment:'right'},
            ]);

            return retval;
        }
        
    </script>
    
    <body style="margin: 0px;">
        <iframe id="pdfiframe" width="100%" height="100%" src="" frameborder="0" style="width: 99vw;height: 99vh;"></iframe>
    </body>
</html>