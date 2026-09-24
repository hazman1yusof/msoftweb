<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <!-- Include the QRCode.js library via cdnjs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" defer></script>
</head>
<style>
    img{
        margin: auto;
    }
</style>
<body>
    <!-- Element where the QR code will be rendered -->
    <div id="qrcode"></div>

    <script>
        // Wait for the script to load completely
        window.addEventListener('DOMContentLoaded', (event) => {
            
            // 1. Basic Usage: Generates a default QR code immediately
            // new QRCode(document.getElementById("qrcode"), "https://example.com");

            // 2. Advanced Usage: Customized configuration
            var qrcode = new QRCode(document.getElementById("qrcode"), {
                text: "{{$qrurl}}",
                width: 256,
                height: 256,
                colorDark : "#000000",   // Foreground color
                colorLight : "#ffffff",  // Background color
                correctLevel : QRCode.CorrectLevel.H // Error correction level (L, M, Q, H)
            });

            // 3. Dynamic Updates (Optional)
            // You can change the QR code value later programmatically:
            // qrcode.clear(); // Clears the existing QR code
            // qrcode.makeCode("https://new-url.com"); // Generates a new one
        });
    </script>

</body>
</html>