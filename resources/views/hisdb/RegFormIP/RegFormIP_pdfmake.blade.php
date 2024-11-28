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
        var NRP = ' ';
        var Name = ' ';
        var Age = ' ';
        var DOB = ' ';
        var Sex = ' ';
        var Status = ' ';
        var NRIC = ' ';
        var PassportNo = ' ';
        var CurrAddress = ' ';
        var District = ' ';
        var PostCode = ' ';
        var State = ' ';
        var Race = ' ';
        var Religion = ' ';
        var NextofKinName = ' ';
        var NextofKinTelNo = ' ';
        var Relationship = ' ';
        var Occupation = ' ';
        var EmployersAddress = ' ';
        var TelNo = ' ';
        var HouseNo = ' ';
        var OfficeNo = ' ';
        var HpNo = ' ';
        var FaxNo = ' ';
        var LegalProceeding = ' ';
        var RegTime = ' ';
        var ReferredFrom = ' ';
        var MothersName = ' ';
        var MothersAddress = ' ';
        var MothersNRICPassport = ' ';
        var MothersEmployerName = ' ';
        var MothersEmployerAddress = ' ';
        var MothersEmployerTelNo = ' ';
        var FathersName = ' ';
        var FathersAddress = ' ';
        var FathersNRICPassport = ' ';
        var FathersEmployerName = ' ';
        var FathersEmployerAddress = ' '; 
        var FathersEmployerTelNo = ' ';
        var InsuranceName = ' ';
        var PolicyType = ' ';
        var Entitlement = ' ';
        var AdmDate = ' ';
        var FirstTranDate = ' ';
        var SecTranDate = ' ';
        var ThirdTranDate = ' ';
        var DischargeDate = ' ';
        var Note1 = ' ';
        var AdmWard = ' ';
        var FirstTranWard = ' ';
        var SecTranWard = ' ';
        var ThirdTranWard = ' ';
        var DischargeWard = ' ';
        var Note2 = ' ';
        var AdmDicipline = ' ';
        var FirstTranDicipline = ' ';
        var SecTranDicipline = ' ';
        var ThirdTranDicipline = ' ';
        var DischargeDicipline = ' ';
        var Note3 = ' ';
        var DischargeTime = ' ';
        var SpecialistName = ' ';
        var Deposit = ' ';
        var FirstTopUp = ' ';
        var SecTopUp = ' ';
        var ThirdTopUp = ' ';
        var MainDiag = ' ';
        var MainDiagCodeNo = ' ';
        var Cause = ' ';
        var CauseCodeNo = ' ';
        var OtherDiag = ' ';
        var OtherDiagCodeNo = ' ';
        var ExternalInjuries = ' ';
        var ExternalInjuriesCodeNo = ' ';
        var OtherFactor = ' ';
        var OtherFactorCodeNo = ' ';
        var Operation = ' ';
        var OperationCodeNo = ' ';
        var MedOfficerName = ' ';
        var Signature = ' ';
        var SpecialistNote = ' ';
        
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
                    {
                        image: 'letterhead', width: 175, style: 'tableHeader', colSpan: 5, alignment: 'center'
                    },
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