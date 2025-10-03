<!DOCTYPE html>
<html>
<head>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
</head>
<body>
  <button onclick="generatePDF()">Generate PDF with QR Code</button>

  <script>
    function generatePDF() {
      const docDefinition = {
        content: [
          { text: 'QR Code Example', style: 'header' },
          {
            qr: 'https://example.com',   // the text or URL for the QR code
            fit: 100,                    // size of QR code (pixels)
            alignment: 'center',         // left | center | right
            foreground: 'black'          // optional: QR color
          },
          { text: 'Scan the QR code above.', margin: [0, 20, 0, 0] }
        ],
        styles: {
          header: { fontSize: 18, bold: true, margin: [0, 0, 0, 10] }
        }
      };

      pdfMake.createPdf(docDefinition).open();
    }
  </script>
</body>
</html>
