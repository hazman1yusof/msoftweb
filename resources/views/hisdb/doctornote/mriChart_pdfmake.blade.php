<!DOCTYPE html>
<html>
    <head>
        <title>Checklist MRI Form</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        
        $(document).ready(function () {
            var docDefinition = {
                footer: function(currentPage, pageCount) {
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                // pageOrientation: 'landscape',
                content: [
                    {
                        image: 'letterhead', width: 175, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
                    {
                        text: '\nCHECKLIST MRI FORM\n',
                        style: 'header',
                        alignment: 'center',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            headerRows: 1,
                            widths: [80, '*',80,94,'*'], //panjang standard dia 515
                            body: [
                                [
                                    {text: 'Name ' },
                                    {text: ': {{$mri->Name}}'},{},
                                    {text: 'MRN ' },
                                    {text: ': {{str_pad($mri->mrn, 7, "0", STR_PAD_LEFT)}}'},
                                    
                                ],
                                [
                                    {text: 'NRIC/Passport No. ' },
                                    {text: ': {{$mri->Newic}}'},{},
                                    {text: 'Weight ' },
                                    {text: ': {{$mri->weight}} kg'},
                                ],
                                [
                                    {text: 'Ward/Clinic ' },
                                    {text: `: `},{},
                                    {text: 'No. Phone ' },
                                    {text: ': {{$mri->telhp}}'},
                                ],
                                [
                                    {text: 'Date ' },
                                    {text: ': {{\Carbon\Carbon::createFromFormat('Y-m-d',$mri->mri_date)->format('d-m-Y')}}'},{},{},{},
                                ],
    
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample',
                        table: {
                            // headerRows: 1,
                            widths: [15,'*',20,20], //panjang standard dia 515
                            body: [
                                [
                                    {text: 'Please indicate in appropriate column, whether or not the patient has the items indicated.\nSila tandakan pada kotak yang berkenaan jika pesakit mempunyai item tersebut.', style: 'tableHeader', colSpan: 2}, {}, 
                                    {text: 'YES', style: 'tableHeader', alignment:'center'},
                                    {text: 'NO', style: 'tableHeader', alignment:'center'}
                                ],
                                [
                                    {text: '1.'}, 
                                    {text: 'Cardiac pacemaker (Penyelaras denyutan jantung)'},
                                    @if($mri->pacemaker == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->pacemaker == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '2.'}, 
                                    {text: 'Prosthetics valve, if yes, please specify.\nInjap jantung palsu, jika ada nyatakan.'}, 
                                    @if($mri->pros_valve == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->pros_valve == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '3.'}, 
                                    {text: 'Known intraocular foreign body or history of eye injury.\n Intraocular bendasing atau sejarah cedera pada mata.'}, 
                                    @if($mri->intraocular == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->intraocular == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '4.'}, 
                                    {text: 'Cochlear implants (ENT.)\n Implant koklea (ENT).'}, 
                                    @if($mri->cochlear == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->cochlear == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '5.'}, 
                                    {text: 'Neurotransmitter (brain/spinal cord pacemaker).\n Neurotransmitter (otak/perentak saraf tunjang).'}, 
                                    @if($mri->neurotransm == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->neurotransm == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '6.'}, 
                                    {text: 'Bone growth stimulators.\n Perangsang tumbesaran tulang.'}, 
                                    @if($mri->bonegrowth == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->bonegrowth == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '7.'}, 
                                    {text: 'Implantable drug infusion pumps.\n Implant pam infuse ubat.'}, 
                                    @if($mri->druginfuse == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->druginfuse == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '8.'}, 
                                    {text: 'Cerebral surgical clips/wire.\n Klip serebral.'}, 
                                    @if($mri->surg_clips == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->surg_clips == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '9.'}, 
                                    {text: 'Joint/limb prosthesis of metallic ferromagnetic materials.\n Anggota badan palsu dari bahan feromagnetic.'}, 
                                    @if($mri->limb_prosth == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->limb_prosth == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '10.'}, 
                                    {text: 'Shrapnel or bullet fragment (any of the body).\n  Serpihan atau peluru.'}, 
                                    @if($mri->shrapnel == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->shrapnel == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '11.'}, 
                                    {text: 'Any operation in the last 3 month? If yes please specify.\n  Pembedahan dalam masa 3 bulan, jika ada nyatakan.'}, 
                                    @if($mri->oper_3mth == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->oper_3mth == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '12.'}, 
                                    {text: 'Any previous MRI examination?\n  Pemeriksaan MRI sebelum ini?'}, 
                                    @if($mri->prev_mri == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->prev_mri == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '13.'}, 
                                    {text: 'Have you ever experienced claustrophobia?\n Anda mempunyai klaustrofobia?'}, 
                                    @if($mri->claustrophobia == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->claustrophobia == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '14.'}, 
                                    {text: 'Dental implant (held in place by magnet).\n Implant dental, mempunyai magnet.'}, 
                                    @if($mri->dental_imp == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->dental_imp == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '15.'}, 
                                    {text: 'Any implanted ferromagnetic materials (susuk or etc).\n Mempunyai bahan-bahan ferromagnetic seperti susuk.'}, 
                                    @if($mri->frmgnetic_imp == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->frmgnetic_imp == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '16.'}, 
                                    {text: 'Pregnancy (1st trimester).\n Mengandung trimester pertama.'}, 
                                    @if($mri->pregnancy == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->pregnancy == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '17.'}, 
                                    {text: 'Allergic to drug or contrast media?\n Mempunyai alahan terhadap ubat atau media kontras.'}, 
                                    @if($mri->allergy_drug == '1')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                    @if($mri->allergy_drug == '0')
                                        { text: '√', alignment:'center'},
                                    @else
                                        { text: '', alignment:'center'},
                                    @endif
                                ],
                                [
                                    {text: '18.'}, 
                                    {text: 'Blood urea: {{$mri->bloodurea}}', colSpan: 3},{},{},
                                ],
                                [
                                    {text: '19.'}, 
                                    {text: 'Serum creatinine: {{$mri->serum_creat}}', colSpan: 3},{},{},
                                ],
                            ],
                    
                        },
                    },
                    {
                        style: 'tableExample',
                        table: {
                            widths: ['*','*','*','*'], //panjang standard dia 515
                            body: [
                                [
                                    { text: 'Name & Sign of Doctor:\n\n{{$mri->doc_name}}', colSpan:2},{},
                                    { text: 'Name & Sign of patient/parents/guardian:\n\n{{$mri->pat_name}}', colSpan:2},{},
                                ],
                                [
                                    { text: 'RADIOLOGY USE ONLY\n\n'},
                                    { text: 'Doctor/Radiologist\n\n'},
                                    { text: 'Radiographer\n\n'},
                                    { text: 'Staff Nurse\n\n'},
                                ],
                                [
                                    { text: 'Relatives accompanying must comply with items listed above. Prohibited items; watches, magnetic cards (credit/ATM cards) and any ferromagnetic and metallic materials.\n\nPenjaga yang menemani pesakit hendaklah mematuhi peraturan di atas. Barang larangan seperti jam tangan, kad magnetic (kad kredit/ATM) termasuk apa-apa bahan ferromagnetic dan bahan-bahan besi', colSpan:4},{},{},{}
                                ],
                            ]
                        },
                    },
                ],
                styles: {
                    header: {
                        fontSize: 14,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    },
                    header1: {
                        fontSize: 14,
                        bold: true,
                        margin: [60, 0, 0, 10]
                    },
                    subheader: {
                        fontSize: 16,
                        bold: true,
                        margin: [0, 10, 0, 5]
                    },
                    tableExample: {
                        fontSize: 8,
                        margin: [0, 5, 0, 15]
                    },
                    tableHeader: {
                        bold: true,
                        fontSize: 9,
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
                        url: "{{asset('/img/letterheadukm.png')}}",
                        headers: {
                            myheader: '123',
                            myotherheader: 'abc',
                        }
                    }
                }
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
        
        // pdfMake.createPdf(docDefinition).getDataUrl(function(dataURL) {
        //     console.log(dataURL);
        //     document.getElementById('pdfPreview').data = dataURL;
        // });
        
        // jsreport.serverUrl = 'http://localhost:5488'
        // async function preview() {
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