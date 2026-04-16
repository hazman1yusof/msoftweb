<!DOCTYPE html>
<html>
    <head>
        <title>IN PATIENT REGISTRATION FORM</title>
    </head>
    
    <!-- <script src="https://unpkg.com/@jsreport/browser-client/dist/jsreport.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="mydata.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    </object>
    
    <script>
        var NRP = `{!!$ini_array['mrn']!!}`;
        var Name = `{!!$ini_array['name']!!}`;
        var Age = `{!!$ini_array['age']!!}`;
        var DOB = `{!!$ini_array['dob']!!}`;
        var Sex = `{!!$ini_array['sex']!!}`;
        var Status = ``;
        var NRIC = `{!!$ini_array['newic']!!}`;
        var PassportNo = ``;
        var CurrAddress = `{!!$pat_mast->Address1!!}
{!!$pat_mast->Address2!!}
{!!$pat_mast->Address3!!}`;
        var District = ``;
        var PostCode = `{!!$pat_mast->Postcode!!}`;
        var State = `{!!$pat_mast->StateCode!!}`;
        var Race = `{!!$ini_array['race']!!}`;
        var Religion = ``;
        var NextofKinName = ``;
        var NextofKinTelNo = ``;
        var Relationship = ``;
        var Occupation = ``;
        var EmployersAddress = ``;
        var TelNo = `{!!$pat_mast->telh!!}`;
        var HouseNo = `{!!$pat_mast->telh!!}`;
        var OfficeNo = `{!!$pat_mast->telo!!}`;
        var HpNo = `{!!$pat_mast->telhp!!}`;
        var FaxNo = ``;
        var LegalProceeding = ``;
        var RegTime = ``;
        var ReferredFrom = ``;
        var MothersName = ``;
        var MothersAddress = ``;
        var MothersNRICPassport = ``;
        var MothersEmployerName = ``;
        var MothersEmployerAddress = ``;
        var MothersEmployerTelNo = ``;
        var FathersName = ``;
        var FathersAddress = ``;
        var FathersNRICPassport = ``;
        var FathersEmployerName = ``;
        var FathersEmployerAddress = ``;
        var FathersEmployerTelNo = ``;
        var InsuranceName = ``;
        var PolicyType = ``;
        var Entitlement = ``;
        var AdmDate = ``;
        var FirstTranDate = ``;
        var SecTranDate = ``;
        var ThirdTranDate = ``;
        var DischargeDate = ``;
        var Note1 = ``;
        var AdmWard = ``;
        var FirstTranWard = ``;
        var SecTranWard = ``;
        var ThirdTranWard = ``;
        var DischargeWard = ``;
        var Note2 = ``;
        var AdmDicipline = ``;
        var FirstTranDicipline = ``;
        var SecTranDicipline = ``;
        var ThirdTranDicipline = ``;
        var DischargeDicipline = ``;
        var Note3 = ``;
        var DischargeTime = ``;
        var SpecialistName = ``;
        var Deposit = ``;
        var FirstTopUp = ``;
        var SecTopUp = ``;
        var ThirdTopUp = ``;
        var MainDiag = ``;
        var MainDiagCodeNo = ``;
        var Cause = ``;
        var CauseCodeNo = ``;
        var OtherDiag = ``;
        var OtherDiagCodeNo = ``;
        var ExternalInjuries = ``;
        var ExternalInjuriesCodeNo = ``;
        var OtherFactor = ``;
        var OtherFactorCodeNo = ``;
        var Operation = ``;
        var OperationCodeNo = ``;
        var MedOfficerName = ``;
        var Signature = ``;
        var SpecialistNote = ``;
        
        $(document).ready(function (){
            var docDefinition = {
                footer: function (currentPage, pageCount){
                    return [
                        { text: currentPage.toString() + ' of ' + pageCount, alignment: 'center' }
                    ]
                },
                pageSize: 'A4',
                pageMargins: [23, 23, 23, 23],
                // pageOrientation: 'landscape',
                content: [
                    // {
                    //     image: 'ukmsc_logo', width: 175, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    // },
                    [
                        {
                            text: [
                                'ABC SPECIALIST CENTRE',
                            ],
                            style: 'header',
                            alignment: 'center',
                        },
                    ],
                    [
                        {
                            text: [
                                '\nBORANG PENDAFTARAN PESAKIT DALAM\n',
                                { text: 'IN PATIENT REGISTRATION FORM', italics: true },
                            ],
                            style: 'header',
                            alignment: 'center',
                        },
                    ],
                    // [
                    //     {
                    //         text: [
                    //             'BUTIR-BUTIR PESAKIT / ',
                    //             { text: 'PARTICULAR OF PATIENT', italics: true },
                    //         ],
                    //         style: 'subheader1',
                    //         alignment: 'center',
                    //     },
                    // ],
                    {
                        style: 'tableExample',
                        table: {
                            widths: [41,41,41,41,41,40,40,41,41,41,41],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    {
                                        text: [
                                            'BUTIR-BUTIR PESAKIT / ',
                                            { text: 'PARTICULAR OF PATIENT', italics: true },
                                        ], colSpan: 11, style: 'subheader1', alignment: 'center'
                                    },{},{},{},{},{},{},{},{},{},{},
                                ],
                                [
                                    {
                                        text: [
                                            '1. No. Pesakit / ',
                                            { text: 'NRP\n', italics: true },
                                            { text: NRP },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    {
                                        text: [
                                            '2. Nama Penuh / ',
                                            { text: 'Name\n', italics: true },
                                            { text: Name },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                    {
                                        text: [
                                            '3. Umur / ',
                                            { text: 'Age\n', italics: true },
                                            { text: Age },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    {
                                        text: [
                                            '4. Tarikh Lahir / ',
                                            { text: 'D.O.B\n', italics: true },
                                            { text: DOB },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    {
                                        text: [
                                            '5. Jantina / ',
                                            { text: 'Sex\n', italics: true },
                                            { text: Sex },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                ],
                                [
                                    {
                                        text: [
                                            '6. Taraf Perkahwinan / ',
                                            { text: 'Status\n', italics: true },
                                            { text: Status },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '7. No. K/P / ',
                                            { text: 'NRIC\n', italics: true },
                                            { text: NRIC },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                    {
                                        text: [
                                            '8. No. Pasport / ',
                                            { text: 'Passport No.\n', italics: true },
                                            { text: PassportNo },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                ],
                                [
                                    {
                                        text: [
                                            '9. Alamat Terkini / ',
                                            { text: 'Current Address\n', italics: true },
                                            { text: CurrAddress },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '10. Daerah / ',
                                            { text: 'District\n', italics: true },
                                            { text: District },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    {
                                        text: [
                                            '11. Poskod / ',
                                            { text: 'Post Code\n', italics: true },
                                            { text: PostCode },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    {
                                        text: [
                                            '12. Negeri / ',
                                            { text: 'State\n', italics: true },
                                            { text: State },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                ],
                                [
                                    {
                                        text: [
                                            '13. Keturunan / ',
                                            { text: 'Race\n', italics: true },
                                            { text: Race },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '14. Agama / ',
                                            { text: 'Religion\n', italics: true },
                                            { text: Religion },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                    {
                                        text: [
                                            '15. Nama Saudara / Waris / ',
                                            { text: 'Name of Next of kin\n', italics: true },
                                            { text: NextofKinName },
                                            '\n\nNo. Telefon / ',
                                            { text: 'Tel No.: ', italics: true },
                                            { text: NextofKinTelNo },
                                        ], rowSpan: 2, colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                ],
                                [
                                    {
                                        text: [
                                            '16. Hubungan / ',
                                            { text: 'Relationship\n', italics: true },
                                            { text: Relationship },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '17. Pekerjaan / ',
                                            { text: 'Occupation\n', italics: true },
                                            { text: Occupation },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                    {},{},{},{},
                                ],
                                [
                                    {
                                        text: [
                                            '18. Alamat Majikan / ',
                                            { text: 'Employer`s Address\n', italics: true },
                                            { text: EmployersAddress },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '19. No. Telefon / ',
                                            { text: 'Tel No.: ', italics: true },
                                            { text: TelNo },
                                            '\nRumah / ',
                                            { text: 'House: ', italics: true },
                                            { text: HouseNo },
                                            '\nPejabat / ',
                                            { text: 'Office: ', italics: true },
                                            { text: OfficeNo },
                                            '\nBimbit / ',
                                            { text: 'Hp: ', italics: true },
                                            { text: HpNo },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                    {
                                        text: [
                                            '20. No. Faks / ',
                                            { text: 'Fax No.\n', italics: true },
                                            { text: FaxNo },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    {
                                        text: [
                                            '21. Kes Perundangan / ',
                                            { text: 'Legal Proceeding\n', italics: true },
                                            { text: LegalProceeding },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                ],
                                [
                                    {
                                        text: [
                                            '22. Waktu Daftar / ',
                                            { text: 'Registration\n', italics: true },
                                            { text: RegTime },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                    {
                                        text: [
                                            '23. Punca Rujukan / ',
                                            { text: 'Referred From\n', italics: true },
                                            { text: ReferredFrom },
                                        ], colSpan: 8, alignment: 'left'
                                    },{},{},{},{},{},{},{},
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
                    },
                    // [
                    //     {
                    //         text: [
                    //             'LENGKAPKAN BAHAGIAN INI SEKIRANYA PESAKIT BAWAH UMUR / ',
                    //             { text: '\nPLEASE FILL UP THIS SECTION IF PATIENT IS A MINOR', italics: true },
                    //         ],
                    //         style: 'subheader1',
                    //         alignment: 'center',
                    //     },
                    // ],
                    {
                        style: 'tableExample',
                        table: {
                            widths: [41,41,41,41,41,40,40,41,41,41,41],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    {
                                        text: [
                                            'LENGKAPKAN BAHAGIAN INI SEKIRANYA PESAKIT BAWAH UMUR / ',
                                            { text: '\nPLEASE FILL UP THIS SECTION IF PATIENT IS A MINOR', italics: true },
                                        ], colSpan: 11, style: 'subheader1', alignment: 'center'
                                    },{},{},{},{},{},{},{},{},{},{},
                                ],
                                [
                                    {
                                        text: [
                                            '1. Nama Ibu / ',
                                            { text: 'Name of Mother\n', italics: true },
                                            { text: MothersName },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '2. Alamat (Jika lain dari pesakit) / ',
                                            { text: 'Address (if different from patient`s address)\n', italics: true },
                                            { text: MothersAddress },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '3. No. KP / No. Passport / ',
                                            { text: 'NRIC / Passport No.\n', italics: true },
                                            { text: MothersNRICPassport },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                ],
                                [
                                    {
                                        text: [
                                            '4. Nama Majikan Ibu / ',
                                            { text: 'Employer`s Name\n', italics: true },
                                            { text: MothersEmployerName },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '5. Alamat Majikan / ',
                                            { text: 'Employer`s Address\n', italics: true },
                                            { text: MothersEmployerAddress },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '6. No. Telefon Majikan / ',
                                            { text: 'Employer`s Tel No.\n', italics: true },
                                            { text: MothersEmployerTelNo },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                ],
                                [
                                    {
                                        text: [
                                            '7. Nama Bapa / ',
                                            { text: 'Name of Father\n', italics: true },
                                            { text: FathersName },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '8. Alamat (Jika lain dari pesakit) / ',
                                            { text: 'Address (if different from patient`s address)\n', italics: true },
                                            { text: FathersAddress },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '9. No. KP / No. Passport / ',
                                            { text: 'NRIC / Passport No.\n', italics: true },
                                            { text: FathersNRICPassport },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                ],
                                [
                                    {
                                        text: [
                                            '10. Nama Majikan Bapa / ',
                                            { text: 'Employer`s Name\n', italics: true },
                                            { text: FathersEmployerName },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '11. Alamat Majikan / ',
                                            { text: 'Employer`s Address\n', italics: true },
                                            { text: FathersEmployerAddress },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '12. No. Telefon Majikan / ',
                                            { text: 'Employer`s Tel No.\n', italics: true },
                                            { text: FathersEmployerTelNo },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
                    },
                    // [
                    //     {
                    //         text: [
                    //             'LENGKAPKAN BAHAGIAN INI SEKIRANYA PEMBAYARAN DIBUAT MELALUI INSURANS / ',
                    //             { text: '\nPLEASE FILL UP THIS SECTION IF PAYMENT IS VIA INSURANCE', italics: true },
                    //         ],
                    //         style: 'subheader1',
                    //         alignment: 'center',
                    //     },
                    // ],
                    {
                        style: 'tableExample',
                        table: {
                            widths: [41,41,41,41,41,40,40,41,41,41,41],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    {
                                        text: [
                                            'LENGKAPKAN BAHAGIAN INI SEKIRANYA PEMBAYARAN DIBUAT MELALUI INSURANS / ',
                                            { text: '\nPLEASE FILL UP THIS SECTION IF PAYMENT IS VIA INSURANCE', italics: true },
                                        ], colSpan: 11, style: 'subheader1', alignment: 'center'
                                    },{},{},{},{},{},{},{},{},{},{},
                                ],
                                [
                                    {
                                        text: [
                                            '1. Syarikat Insurans / ',
                                            { text: 'Name of Insurance Provider\n', italics: true },
                                            { text: InsuranceName },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                    {
                                        text: [
                                            '2. Jenis Insurans / ',
                                            { text: 'Type of Policy\n', italics: true },
                                            { text: PolicyType },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                    {
                                        text: [
                                            '3. Tahap Insurans / ',
                                            { text: 'Entitlement\n', italics: true },
                                            { text: Entitlement },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
                    },
                    // [
                    //     {
                    //         text: [
                    //             'CATATAN KEMASUKAN HOSPITAL / ',
                    //             { text: '\nADMISSION NOTE', italics: true },
                    //         ],
                    //         style: 'subheader1',
                    //         alignment: 'center',
                    //     },
                    // ],
                    {
                        style: 'tableExample',
                        table: {
                            widths: [65,60,80,65,65,60,90],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    {
                                        text: [
                                            'CATATAN KEMASUKAN HOSPITAL / ',
                                            { text: '\nADMISSION NOTE', italics: true },
                                        ], colSpan: 7, style: 'subheader1', alignment: 'center'
                                    },{},{},{},{},{},{},
                                ],
                                [
                                    { text: ' ' },
                                    {
                                        text: [
                                            'Kemasukan / ',
                                            { text: '\nAdmission', italics: true },
                                        ], alignment: 'center'
                                    },
                                    {
                                        text: [
                                            'Pindah Pertama Kali / ',
                                            { text: '\n1st Transfer', italics: true },
                                        ], alignment: 'center'
                                    },
                                    {
                                        text: [
                                            'Pindah Kedua / ',
                                            { text: '\n2nd Transfer', italics: true },
                                        ], alignment: 'center'
                                    },
                                    {
                                        text: [
                                            'Pindah Ketiga / ',
                                            { text: '\n3rd Transfer', italics: true },
                                        ], alignment: 'center'
                                    },
                                    {
                                        text: [
                                            'Discaj / ',
                                            { text: '\nDischarge', italics: true },
                                        ], alignment: 'center'
                                    },
                                    {
                                        text: [
                                            'Catatan / ',
                                            { text: 'Note', italics: true },
                                        ], alignment: 'center'
                                    },
                                ],
                                [
                                    {
                                        text: [
                                            '1. Tarikh / ',
                                            { text: 'Date', italics: true },
                                        ], alignment: 'left'
                                    },
                                    { text: AdmDate, alignment: 'left' },
                                    { text: FirstTranDate, alignment: 'left' },
                                    { text: SecTranDate, alignment: 'left' },
                                    { text: ThirdTranDate, alignment: 'left' },
                                    { text: DischargeDate, alignment: 'left' },
                                    { text: Note1, alignment: 'left' },
                                ],
                                [
                                    {
                                        text: [
                                            '2. Wad / ',
                                            { text: 'Ward', italics: true },
                                        ], alignment: 'left'
                                    },
                                    { text: AdmWard, alignment: 'left' },
                                    { text: FirstTranWard, alignment: 'left' },
                                    { text: SecTranWard, alignment: 'left' },
                                    { text: ThirdTranWard, alignment: 'left' },
                                    { text: DischargeWard, alignment: 'left' },
                                    { text: Note2, alignment: 'left' },
                                ],
                                [
                                    {
                                        text: [
                                            '3. Disiplin / ',
                                            { text: 'Dicipline', italics: true },
                                        ], alignment: 'left'
                                    },
                                    { text: AdmDicipline, alignment: 'left' },
                                    { text: FirstTranDicipline, alignment: 'left' },
                                    { text: SecTranDicipline, alignment: 'left' },
                                    { text: ThirdTranDicipline, alignment: 'left' },
                                    { text: DischargeDicipline, alignment: 'left' },
                                    { text: Note3, alignment: 'left' },
                                ],
                                [
                                    {
                                        text: [
                                            '4. Waktu Discaj / ',
                                            { text: '\nTime of Discharge\n\n', italics: true },
                                            { text: DischargeTime },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{},
                                    {
                                        text: [
                                            '5. Nama Pakar / Pegawai Perubatan\n',
                                            { text: 'Name of Specialist / Medical Officer\n\n', italics: true },
                                            { text: SpecialistName },
                                        ], colSpan: 4, alignment: 'left'
                                    },{},{},{},
                                ],
                                [
                                    {
                                        text: [
                                            '6. Deposit\n\n',
                                            { text: Deposit },
                                        ], rowspan: 2, alignment: 'left', border: [true, true, true, false],
                                    },
                                    { text: 'Tambahan Pertama \n 1st Top - Up', colSpan: 2, alignment: 'left' },{},
                                    { text: 'Tambahan Kali Kedua \n 2nd Top - Up', colSpan: 2, alignment: 'left' },{},
                                    { text: 'Tambahan Kali Ketiga \n 3rd Top - Up', colSpan: 2, alignment: 'left' },{},
                                ],
                                [
                                    { text: '', border: [true, false, true, true] },
                                    { text: FirstTopUp, colSpan: 2, alignment: 'left' },{},
                                    { text: SecTopUp, colSpan: 2, alignment: 'left' },{},
                                    { text: ThirdTopUp, colSpan: 2, alignment: 'left' },{},
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
                    },
                    { text: '', alignment: 'left', fontSize: 9, pageBreak: 'after' },
                    // [
                    //     {
                    //         text: [
                    //             'DIAGNOSIS OLEH PAKAR/PEGAWAI PERUBATAN DI WAD BERKENAAN / ',
                    //             { text: '\nDIAGNOSIS OF SPECIALIST/MEDICAL OFFICER', italics: true },
                    //         ],
                    //         style: 'subheader1',
                    //         alignment: 'center',
                    //     },
                    // ],
                    {
                        style: 'tableExample',
                        table: {
                            widths: [120,210,190],
                            // headerRows: 5,
                            // keepWithHeaderRows: 5,
                            body: [
                                [
                                    {
                                        text: [
                                            'DIAGNOSIS OLEH PAKAR/PEGAWAI PERUBATAN DI WAD BERKENAAN / ',
                                            { text: '\nDIAGNOSIS OF SPECIALIST/MEDICAL OFFICER', italics: true },
                                        ], colSpan: 3, style: 'subheader1', alignment: 'center'
                                    },{},{},
                                ],
                                [
                                    { text: ' ' },
                                    { text: 'Diagnosis', alignment: 'center' },
                                    {
                                        text: [
                                            'No. Kod Urusan Jabatan Rekod / ',
                                            { text: '\nRecord Department Code No.', italics: true },
                                        ], alignment: 'center'
                                    },
                                ],
                                [
                                    {
                                        text: [
                                            '1. Diagnosis Utama / ',
                                            { text: '\nMain Diagnosis', italics: true },
                                        ], alignment: 'left'
                                    },
                                    { text: MainDiag, alignment: 'left' },
                                    { text: MainDiagCodeNo, alignment: 'left' },
                                ],
                                [
                                    {
                                        text: [
                                            '2. Sebab-sebab yang menyebabkan / ',
                                            { text: 'Cause', italics: true },
                                        ], alignment: 'left'
                                    },
                                    { text: Cause, alignment: 'left' },
                                    { text: CauseCodeNo, alignment: 'left' },
                                ],
                                [
                                    {
                                        text: [
                                            '3. Diagnosis Lain (Jika ada) / ',
                                            { text: '\nOther Diagnosis (If any)', italics: true },
                                        ], alignment: 'left'
                                    },
                                    { text: OtherDiag, alignment: 'left' },
                                    { text: OtherDiagCodeNo, alignment: 'left' },
                                ],
                                [
                                    {
                                        text: [
                                            '4. Sebab-sebab kecederaan luar dan keracunan',
                                            { text: '\nCause of External Injuries of Poisoning', italics: true },
                                        ], alignment: 'left'
                                    },
                                    { text: ExternalInjuries, alignment: 'left' },
                                    { text: ExternalInjuriesCodeNo, alignment: 'left' },
                                ],
                                [
                                    {
                                        text: [
                                            '5. Faktor-faktor lain yang mempengaruhi tahap kesihatan / ',
                                            { text: '\nOther factor', italics: true },
                                        ], alignment: 'left'
                                    },
                                    { text: OtherFactor, alignment: 'left' },
                                    { text: OtherFactorCodeNo, alignment: 'left' },
                                ],
                                [
                                    {
                                        text: [
                                            '6. Pembedahan (jika ada) / ',
                                            { text: '\nOperation (if any)', italics: true },
                                        ], alignment: 'left'
                                    },
                                    { text: Operation, alignment: 'left' },
                                    { text: OperationCodeNo, alignment: 'left' },
                                ],
                                [
                                    {
                                        text: [
                                            '7. Nama Pakar / Pegawai\n',
                                            { text: 'Name of specialist / medical officer', italics: true },
                                            { text: MedOfficerName, italics: true },
                                        ], colSpan: 2, alignment: 'left'
                                    },{},
                                    {
                                        text: [
                                            '8. Tandatangan / ',
                                            { text: 'Signature\n', italics: true },
                                            { text: Signature, italics: true },
                                        ], alignment: 'left'
                                    }
                                ],
                                [
                                    {
                                        text: [
                                            '9. Catatan / ',
                                            { text: 'Note', italics: true },
                                            { text: SpecialistNote, italics: true },
                                        ], colSpan: 3, alignment: 'left'
                                    },{},{}
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
                    },
                    // [
                    //     {
                    //         text: [
                    //             'PERSETUJUAN UNTUK RAWATAN DI PUSAT PAKAR UKM / ',
                    //             { text: '\nCONSENT TO UNDER TREATMENT IN UKM SPECIALIST CENTRE', italics: true },
                    //         ],
                    //         style: 'subheader1',
                    //         alignment: 'center',
                    //     },
                    // ],
                    {
                        style: 'tableConsent',
                        table: {
                            widths: [125,130,125,130], // panjang standard dia 515
                            body: [
                                [
                                    {
                                        text: [
                                            'PERSETUJUAN UNTUK RAWATAN DI PUSAT PAKAR UKM / ',
                                            { text: '\nCONSENT TO UNDER TREATMENT IN UKM SPECIALIST CENTRE', italics: true },
                                        ], colSpan: 4, style: 'subheader1', alignment: 'center'
                                    },{},{},{},
                                ],
                                [
                                    {
                                        text: [
                                            '1. Saya dengan ini bersetuju untuk diberi penjagaan di ',
                                            { text: 'PUSAT PAKAR UKM', bold: true },
                                            ', termasuk prosedur-prosedur diagnosis dan rawatan seperti yang telah dipertimbangkan sebagai sesuai dan perlu oleh doktor di bawah penyeliaannya. Sebarang fotograf yang diambil semasa rawatan dijalankan akan menjadi hakmilik ',
                                            { text: 'PUSAT PAKAR UKM.', bold: true },
                                            { text: '\nI do hereby give consent for myself/child/dependent to undergo treatment in UKM Specialist Centre. I further consent to submit myself to any additional/other procedures, diagnosis and treatment deemed necessary and required by the attending doctor. I agree that any photograph taken during my treatment in UKMSC is rightfully owned by UKMSC.', italics: true }
                                        ], colSpan: 4, border: [true, true, true, false],
                                    },{},{},{}
                                ],
                                [
                                    {
                                        text: [
                                            '2. Saya dengan ini memberi kuasa agar maklumat berkenaan dengan saya boleh dikeluarkan kepada doktor yang merujuk, majikan, dan mana-mana pihak yang berkenaan yang dianggap wajar oleh ',
                                            { text: 'PUSAT PAKAR UKM ', bold: true },
                                            'dan sekiranya saya perlu dipindahkan ke hospital yang lain saya juga bersetuju terhadap komunikasi maklumat yang berkaitan dari rekod pejabat saya dan rekod yang terdapat di ',
                                            { text: 'PUSAT PAKAR UKM ', bold: true },
                                            'kepada hospital di mana saya akan dirawat, yang diperlukan untuk tujuan pendaftaran dan kemasukan ke hospital itu.',
                                            { text: '\nI do hereby agree to allow UKMSC to furnish any information in relation to the treatment of myself/child/dependent to my employer / referring doctor / any other party / as deemed appropriate by UKMSC. In the event that / my child / dependent is transferred to any other hospital, I further agree for the purpose of registration and admission, to allow the communication and transfer of any necessary information relating to the treatment received by myself/child/dependent to the hospital where I/my child/dependent am to receive treatment.', italics: true }
                                        ], colSpan: 4, border: [true, false, true, false],
                                    },{},{},{}
                                ],
                                [
                                    {
                                        text: [
                                            '3. Saya akan bertanggungjawab untuk semua bayaran yang akan dikenakan ke atas saya oleh ',
                                            { text: 'PUSAT PAKAR UKM ', bold: true },
                                            'untuk rawatan saya dan sekiranya saya mempunyai insurans saya akan bertanggungjawab untuk semua bayaran yang tidak dibayar oleh polisi insurans saya. Sekiranya saya tidak berkesempatan untuk membayar apa-apa bayaran yang perlu dibayar kepada ',
                                            { text: 'PUSAT PAKAR UKM ', bold: true },
                                            'kerana kematian saya maka waris-waris saya akan dipertanggungjawabkan untuk menyelesaikan segala bayaran yang perlu dibayar atau yang tertunggak.',
                                            { text: '\nI do hereby undertake to pay UKMSC for all and any changes due and payable to UKMSC for the services provided by UKMSC during the course of treatment received by myself/child/depend. Where payment is made by insurance provider, I further undertake to pay UKMSC for all and any changes due and payable in excess of my insurance policy entitlement.\n\n', italics: true }
                                        ], colSpan: 4, border: [true, false, true, false],
                                    },{},{},{}
                                ],
                                [
                                    {
                                        text: [
                                            'Nama Pesakit/\n',
                                            { text: 'Patient Name: ', italics: true },
                                            { text: Name, decoration: 'underline' },
                                            '\n\u200B\t\u200B\t\u200B\t\u200B\t\u200B\t\u200B\t\u200B\t\u200B\tHuruf Besar / ',
                                            { text: 'Block Letter', italics: true },
                                        ], colSpan: 2, alignment: 'left', border: [true, false, false, false],
                                    },{},
                                    {
                                        text: [
                                            'Tandatangan Pesakit atau Penjaga/\n',
                                            { text: 'Signature of Patient or Guardian: ', italics: true },
                                            '_______________________________________\n',
                                        ], colSpan: 2, alignment: 'left', border: [false, false, true, false],
                                    },{},
                                ],
                                [
                                    {
                                        text: [
                                            'Pesakit Seorang Minor/\n',
                                            { text: 'Patient is a Minor: ', italics: true },
                                            '_________________________Tahun',
                                        ], colSpan: 2, alignment: 'left', border: [true, false, false, false],
                                    },{},
                                    {
                                        text: [
                                            'Saksi / ',
                                            { text: 'Witness: ', italics: true },
                                            '_______________________________________\n','Tarikh / ',
                                            { text: 'Date: ', italics: true },
                                            '_______________________________________\n',
                                        ], colSpan: 2, alignment: 'left', border: [false, false, true, false],
                                    },{},
                                ],
                                [
                                    {
                                        text: [
                                            // 'Saya, ',
                                            // { text: Name, decoration: 'underline' },
                                            // '  (Nama) No. K/P  ',
                                            // { text: NRIC, decoration: 'underline' },
                                            'Saya, ........................................................(Nama) No.K/P ........................................................, dengan ini mengakujanji bahawa saya kan bertanggungjawab untuk semua bayaran yang akan di kenakan ke atas pesakit oleh ',
                                            { text: 'PUSAT PAKAR UKM ', bold: true },
                                            'untuk rawatan beliau dan sekiranya pesakit mempunyai insurans saya kan bertanggungjawab untuk membayar segala bayaran yang perlu dibayar atau yang tertunggak yang tidak di bayar oleh polisi insurans pesakit.',
                                            // { text: '\nIn the event of the patient`s demise, I ', italics: true },
                                            // { text: Name, decoration: 'underline' },
                                            // { text: '  (Name) NRIC  ', italics: true },
                                            // { text: NRIC, decoration: 'underline' },
                                            { text: '\nIn the event of the patient`s demise, I ........................................................(Name) NRIC ........................................................, do hereby undertake to pay UKMSC for all and any changes due and payable in UKMSC for the services provided by UKMSC during the course of treatment received by the patient. Where payment is made by insurance provider, I further undertake to pay UKMSC for all and any changes due and payable in excess of the insurance policy entitlement.', italics: true },
                                        ], colSpan: 4, border: [true, false, true, false],
                                    },{},{},{}
                                ],
                                [
                                    {
                                        text: [
                                            { text: '\nSila Ambil Perhatian / ', bold: true, decoration: 'underline' },
                                            { text: 'Attention : ', bold: true, italics: true, decoration: 'underline' },
                                            '\nKami dikehendaki untuk menyimpan rekod-rekod perubatan dan x-ray asal di pejabat. Sekiranya tuan/puan meminta salinan rekod perubatan tuan/puan, ianya akan dibekalkan kepada tuan/puan dengan syarat tuan/puan menandatangani persetujuan permintaan salinan rekod perubatan tersebut dan juga selepas kami menerima bayaran yuran pemprosesan dari tuan/puan.',
                                            { text: '\nUKMSC is required to retain all original medical records and X-rays in our Record Department. Any request for copies of the aforementioned will only be allowed upon a written request an acknowledgement by the patient/legal guardian/legal representative. Any copies made will only be release upon full payment of the processing fee.', italics: true },
                                        ], colSpan: 4, border: [true, false, true, true],
                                    },{},{},{}
                                ],
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex){
                                return (rowIndex === 0) ? '#000000' : null;
                            }
                        }
                    },
                ],
                styles: {
                    header: {
                        fontSize: 12,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    },
                    subheader: {
                        fontSize: 16,
                        bold: true,
                        margin: [0, 10, 0, 5]
                    },
                    subheader1: {
                        fontSize: 10,
                        // margin: [0, 10, 0, 5],
                        // background: 'black',
                        color: 'white',
                    },
                    tableExample: {
                        fontSize: 8,
                        margin: [0, 0, 0, 0]
                    },
                    tableConsent: {
                        fontSize: 7,
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
                    ukmsc_logo:"data:image/png;base64,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"
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