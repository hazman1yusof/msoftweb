<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Hello world</title>
</head>
<body>
   
    <script type="text/javascript" src="jspdf.debug.js"></script>
    <script type="text/javascript">
        var pdf = new jsPDF();
        pdf.setFont("helvetica");
        pdf.setFontType("bold");
        pdf.setFontSize(15);
        pdf.text(20, 30, 'BEACON INTERNATIONAL TRAINING CENTER SDN BHD (621976-D)');

        pdf.setFontType("normal");
        pdf.setFontSize(12);
        pdf.text(60, 40, 'No 1, JLN 215, SECTION 51 OFF JLN TEMPLER');
        pdf.text(70, 45, '46050 PETALING JAYA, SELANGOR');
        pdf.text(68, 50, 'Tel:  03-76207979     Fax:  03-76207979');

        pdf.line(20, 55, 190, 55); 

        pdf.setFontType("bold");
        pdf.setFontSize(13)
        pdf.text(75, 65, 'PAYMENT VOUCHER');

        pdf.line(20, 70, 190, 70);

        pdf.setFontType("normal");
        pdf.setFontSize(10);
        pdf.text(20, 80, 'Pay To           :');
        pdf.text(20, 90, 'Cheque To     :');
        pdf.text(20, 100, 'Bank a/c No   :');

        pdf.text(140, 80, 'Voucher No  :');
        pdf.text(140, 90, 'Date             :');

        pdf.line(20, 105, 190, 105);



        pdf.output('dataurlnewwindow'); 


      	//doc.output('datauri');    //opens the data uri in current window
        //pdf.save('hello_world.pdf');
    </script>
</body>
</html>