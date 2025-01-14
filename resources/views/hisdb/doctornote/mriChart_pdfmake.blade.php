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
                            widths: [80,'*',60,94,'*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Name ' },
                                    { text: ': {{$mri->Name}}' },{},
                                    { text: 'MRN ' },
                                    { text: ': {{str_pad($mri->mrn, 7, "0", STR_PAD_LEFT)}}' },
                                ],
                                [
                                    { text: 'NRIC/Passport No. ' },
                                    { text: ': {{$mri->Newic}}' },{},
                                    { text: 'Weight ' },
                                    { text: ': {{$mri->mri_weight}} kg' },
                                ],
                                [
                                    { text: 'Ward/Clinic ' },
                                    { text: ': {{$mri->EpWard}}' },{},
                                    { text: 'No. Phone ' },
                                    @if(!empty($mri->telh))
                                        { text: ': {{$mri->telh}}/{{$mri->telhp}}' },
                                    @else
                                        { text: ': {{$mri->telhp}}' },
                                    @endif
                                ],
                                [
                                    { text: 'Date ' },
                                    { text: ': {{\Carbon\Carbon::createFromFormat('Y-m-d',$mri->entereddate)->format('d-m-Y')}}' },{},{},{},
                                ],
                            ]
                        },
                        layout: 'noBorders',
                    },
                    {
                        style: 'tableExample2',
                        table: {
                            // headerRows: 1,
                            widths: [15,'*',20,20], // panjang standard dia 515
                            body: [
                                [
                                    {
                                        text: [
                                            'Please indicate in appropriate column, whether or not the patient has the items indicated.\n',
                                            { text: 'Sila tandakan pada kotak yang berkenaan jika pesakit mempunyai item tersebut.', italics: true }
                                        ], colSpan: 2, style: 'tableHeader',
                                    },{},
                                    { text: 'YES', style: 'tableHeader', alignment: 'center' },
                                    { text: 'NO', style: 'tableHeader', alignment: 'center' }
                                ],
                                [
                                    { text: '1.' },
                                    {
                                        text: [
                                            'Cardiac pacemaker.\n',
                                            { text: 'Penyelaras denyutan jantung', italics: true }
                                        ],
                                    },
                                    @if($mri->cardiacpacemaker == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->cardiacpacemaker == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '2.' },
                                    {
                                        text: [
                                            'Prosthetics valve, if yes, please specify.\n',
                                            { text: 'Injap jantung palsu, jika ada nyatakan:\n', italics: true },
                                            `- {!!$mri->prosvalve_rmk!!}`
                                        ],
                                    },
                                    @if($mri->pros_valve == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->pros_valve == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '3.' },
                                    {
                                        text: [
                                            'Known intraocular foreign body or history of eye injury.\n',
                                            { text: 'Intraocular bendasing atau sejarah cedera pada mata.\n', italics: true },
                                        ],
                                    },
                                    @if($mri->intraocular == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->intraocular == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '4.' },
                                    {
                                        text: [
                                            'Cochlear implants (ENT.).\n',
                                            { text: 'Implant koklea (ENT).\n', italics: true },
                                        ],
                                    },
                                    @if($mri->cochlear_imp == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->cochlear_imp == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '5.' },
                                    {
                                        text: [
                                            'Neurotransmitter (brain/spinal cord pacemaker).\n',
                                            { text: 'Neurotransmitter (otak/perentak saraf tunjang).\n', italics: true },
                                        ],
                                    },
                                    @if($mri->neurotransm == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->neurotransm == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '6.' },
                                    {
                                        text: [
                                            'Bone growth stimulators.\n',
                                            { text: 'Perangsang tumbesaran tulang.\n', italics: true },
                                        ],
                                    },
                                    @if($mri->bonegrowth == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->bonegrowth == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '7.' },
                                    {
                                        text: [
                                            'Implantable drug infusion pumps.\n',
                                            { text: 'Implant pam infuse ubat.\n', italics: true },
                                        ],
                                    },
                                    @if($mri->druginfuse == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->druginfuse == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '8.' },
                                    {
                                        text: [
                                            'Cerebral surgical clips/wire.\n',
                                            { text: 'Klip serebral.\n', italics: true },
                                        ],
                                    },
                                    @if($mri->surg_clips == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->surg_clips == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '9.' },
                                    {
                                        text: [
                                            'Joint/limb prosthesis of metallic ferromagnetic materials.\n',
                                            { text: 'Anggota badan palsu dari bahan feromagnetic.\n', italics: true },
                                        ],
                                    },
                                    @if($mri->jointlimb_pros == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->jointlimb_pros == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '10.' },
                                    {
                                        text: [
                                            'Shrapnel or bullet fragment (any of the body).\n',
                                            { text: 'Serpihan atau peluru.\n', italics: true },
                                        ],
                                    },
                                    @if($mri->shrapnel == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->shrapnel == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '11.' },
                                    {
                                        text: [
                                            'Any operation in the last 3 month? If yes please specify.\n',
                                            { text: 'Pembedahan dalam masa 3 bulan, jika ada nyatakan:\n', italics: true },
                                            `- {!!$mri->oper3mth_remark!!}`
                                        ],
                                    },
                                    @if($mri->oper_3mth == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->oper_3mth == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '12.' },
                                    {
                                        text: [
                                            'Any previous MRI examination?\n',
                                            { text: 'Pemeriksaan MRI sebelum ini?\n', italics: true },
                                        ],
                                    },
                                    @if($mri->prev_mri == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->prev_mri == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '13.' },
                                    {
                                        text: [
                                            'Have you ever experienced claustrophobia?\n',
                                            { text: 'Anda mempunyai klaustrofobia?\n', italics: true },
                                        ],
                                    },
                                    @if($mri->claustrophobia == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->claustrophobia == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '14.' },
                                    {
                                        text: [
                                            'Dental implant (held in place by magnet).\n',
                                            { text: 'Implant dental, mempunyai magnet.\n', italics: true },
                                        ],
                                    },
                                    @if($mri->dental_imp == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->dental_imp == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '15.' },
                                    {
                                        text: [
                                            'Any implanted ferromagnetic materials (susuk or etc).\n',
                                            { text: 'Mempunyai bahan-bahan ferromagnetic seperti susuk.\n', italics: true },
                                        ],
                                    },
                                    @if($mri->frmgnetic_imp == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->frmgnetic_imp == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '16.' },
                                    {
                                        text: [
                                            'Pregnancy (1st trimester).\n',
                                            { text: 'Mengandung trimester pertama.\n', italics: true },
                                        ],
                                    },
                                    @if($mri->pregnancy == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->pregnancy == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '17.' },
                                    {
                                        text: [
                                            'Allergic to drug or contrast media?\n',
                                            { text: 'Mempunyai alahan terhadap ubat atau media kontras.\n', italics: true },
                                        ],
                                    },
                                    @if($mri->allergy_drug == '1')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                    @if($mri->allergy_drug == '0')
                                        { text: '√', alignment: 'center' },
                                    @else
                                        { text: '', alignment: 'center' },
                                    @endif
                                ],
                                [
                                    { text: '18.' },
                                    { text: 'Blood urea: {{$mri->bloodurea}}', colSpan: 3 },{},{},
                                ],
                                [
                                    { text: '19.' },
                                    { text: 'Serum creatinine: {{$mri->serum_creatinine}}', colSpan: 3 },{},{},
                                ],
                            ],
                        },
                    },
                    {
                        style: 'tableExample2',
                        table: {
                            widths: ['*','*','*','*'], // panjang standard dia 515
                            body: [
                                [
                                    { text: 'Name & Sign of Doctor: {{strtoupper($mri->mri_doctorname)}}', colSpan: 2, border: [true, false, true, true] },{},
                                    { text: 'Name & Sign of patient/parents/guardian: {{strtoupper($mri->Name)}}', colSpan: 2, border: [true, false, true, true] },{},
                                ],
                                [
                                    { text: 'RADIOLOGY USE ONLY:', bold: true },
                                    { text: 'Doctor/Radiologist:\n{{strtoupper($mri->radiologist)}}' },
                                    { text: 'Radiographer:\n{{strtoupper($mri->radiographer)}}' },
                                    { text: 'Entered By:\n{{strtoupper($mri->mri_lastuser)}}' },
                                ],
                                [
                                    {
                                        text: [
                                            'Relatives accompanying must comply with items listed above. Prohibited items; watches, magnetic cards (credit/ATM cards) and any ferromagnetic and metallic materials.\n\n',
                                            { text: 'Penjaga yang menemani pesakit hendaklah mematuhi peraturan di atas. Barang larangan seperti jam tangan, kad magnetic (kad kredit/ATM) termasuk apa-apa bahan ferromagnetic dan bahan-bahan besi', italics: true }
                                        ], colSpan: 4,
                                    },{},{},{}
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
                    tableExample2: {
                        fontSize: 8,
                        margin: [0, 0, 0, 0]
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
            
            // pdfMake.createPdf(docDefinition).getBase64(function (data){
            //     var base64data = "data:base64"+data;
            //     console.log($('object#pdfPreview').attr('data',base64data));
            //     // document.getElementById('pdfPreview').data = base64data;
            // });
            
            pdfMake.createPdf(docDefinition).getDataUrl(function (dataURL){
                $('#pdfiframe').attr('src',dataURL);
            });
        });
        
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