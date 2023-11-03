<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="600">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
  body{
      font-size: 14px;
      background-color: #8080801c;
  }
  .container{
      margin: auto;
  }
  iframe{
      z-index:-9999;
      position: fixed;
      visibility:hidden;
      top:0;
  }
  .settaggi,.dati-prod{
      padding: 5px;
      max-width:450px;
      margin:auto;
  }
  .settaggi{
      background-color: #009fff12;
  }
  .dati-prod{
      background-color: #02a1ee;
  }
  input{
      width: 97%;
      text-align: right;
  }
@media (max-width: 450px) {
    body{
      background-color: #009fff12;
        max-width: 100%;
    }
    .container{
      max-width: 100%;
    }
    .settaggi,.dati-prod{
      width:100%;
      border: 0px solid #00000000;
    }
}
  </style>
</head>
<body>
    <div class = 'container'>
    <p></p>
    <h1 style="text-align: center;width: 100%;">FREE SHOPPING</h1>
    <p></p>
    <?php
    
    if(isset($_SESSION['codice-prodotto']) && isset($_SESSION['prezzo-kilo'])){
        $productCodeReference = htmlspecialchars($_SESSION['codice-prodotto']);
        $pricePerKilogram = htmlspecialchars($_SESSION['prezzo-kilo']);
        $weightwasteRef = htmlspecialchars($_SESSION['tara-rif']);
        $prodRef = htmlspecialchars($_SESSION['prodref']);
    }else{
        $productCodeReference = substr("2140010",0,7);
        $pricePerKilogram = 1.69;
        $weightwasteRef = .008;
        $prodRef = "mele golden";
    }
    if(!isset($productCodeReference) || !isset($pricePerKilogram) || !isset($weightwasteRef) || !isset($prodRef) ){
        $productCodeReference = substr("2140010",0,7);
        $pricePerKilogram = 1.69;
        $weightwasteRef = .008;
        $prodRef = "mele golden";
    }
    if($productCodeReference == 0 || $pricePerKilogram == 0 || $weightwasteRef == 0 || $prodRef == 0 ){
        $productCodeReference = substr("2140010",0,7);
        $pricePerKilogram = 1.69;
        $weightwasteRef = .008;
        $prodRef = "mele golden";
    }    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $productType = htmlspecialchars($_POST["productType"]);
        $prodRef = htmlspecialchars($_POST["productRef"]);
        $productCodeReference = substr(htmlspecialchars($_POST["productCodeReference"]),0,7);
        $pricePerKilogram = floatval($_POST["pricePerKilogram"]);
        $weightInput = floatval($_POST["weight"]);
        $weightwasteInput = floatval($_POST["weight-waste"]);
        $weightwasteRef = floatval($_POST["weightwasteref"]);
        $_SESSION['prezzo-kilo'] = $pricePerKilogram; 
        $_SESSION['codice-prodotto'] = $productCodeReference;
        $_SESSION['tara-rif'] = $weightwasteRef;
        $_SESSION['prodref'] = $prodRef;
        
        if (!empty($productType) && !empty($productCodeReference) && is_numeric($pricePerKilogram) && is_numeric($weightInput) && strlen($productCodeReference) === 7) {
            $productCode = "";

            $fixedPrefix = $productCodeReference;

            // Calcola il prezzo e lo arrotonda alla seconda cifra decimale
            $newPrice = number_format(($weightInput+$weightwasteInput-$weightwasteRef) * $pricePerKilogram, 2, '.', '');

            $productCode = number_format($newPrice * 100, 0, '', '');

            while (strlen($productCode) < 5) {
                $productCode = "0" . $productCode;
            }

            $lastDigit = 0;
            $sumOfDigits = 0;

            for ($i = 0; $i < strlen($productCode); $i++) {
                $sumOfDigits += intval($productCode[$i]);
            }

            while ($sumOfDigits > 9) {
                $sumString = strval($sumOfDigits);
                $sumOfDigits = 0;

                for ($i = 0; $i < strlen($sumString); $i++) {
                    $sumOfDigits += intval($sumString[$i]);
                }
            }

            $lastDigit = $sumOfDigits;

            $EAN13CodeWithoutCheckDigit = $fixedPrefix . $productCode;

            // Visualizza la tipologia del prodotto sopra il barcode
            echo "<h2 style='text-transform:uppercase;text-align:center;'>$productType</h2>";

            // Visualizza il nuovo codice EAN-13 senza la tredicesima cifra
            // echo "<p>$EAN13CodeWithoutCheckDigit</p>";

            // Aggiorna l'immagine del barcode
            echo "<div style='text-align: center;'>";
            echo "<img alt='Barcode Generator TEC-IT' style='max-width: 450px;width: 100%;' src='https://barcode.tec-it.com/barcode.ashx?data=$EAN13CodeWithoutCheckDigit&code=EAN13&translate-esc=on'/>";
            echo "</div>";
            echo "<p></p>";
            echo "<h2 style='text-align:center;'><b>  IMPORTO  </b><b style='font-size:25px;'>".$newPrice." &euro;</b></h2>";
            echo "<p></p>";
            echo "<p></p>";
            echo "<p></p>";
?>            

    <script>
        document.getElementById('screenshotButton').addEventListener('click', function() {
            takeScreenshot();
        });

        function takeScreenshot() {
            html2canvas(document.body).then(function(canvas) {
                var screenshotImage = new Image();
                screenshotImage.src = canvas.toDataURL('image/png');

                // Visualizza l'immagine dello screenshot all'interno di un contenitore
                var screenshotContainer = document.getElementById('screenshotContainer');
                screenshotContainer.innerHTML = ''; // Cancella il contenuto precedente
                screenshotContainer.appendChild(screenshotImage);
            });
        }
    </script>            
<?php

            echo "<p></p>";
            echo "<p></p>";
        } else {
            echo "<script>alert('Valori inseriti non validi.');</script>";
        }
    }
    ?>

    <div class = 'settaggi' style="">
        <h3 style="text-align: center;width: 100%;">SETTAGGI INIZIALI :</h3>
        <p></p>
        <form method="post" id="barcodeForm">
        <label for="productCodeReference"><b>Prime 7 cifre BarCode:</b></label><br>
        <input type="text" name="productCodeReference" value="<?=$productCodeReference?>" required style="">
        <br><br>
        <label for="pricePerKilogram"><b>Tara :</b></label><br>
        <input type="text" name="weightwasteref" value="<?=$weightwasteRef?>" required style="">
        <br><br>
        <label for="pricePerKilogram"><b>Prezzo al chilogrammo:</b></label><br>
        <input type="text" name="pricePerKilogram" value="<?=$pricePerKilogram?>" required style="">
        <br><br>
        <label for="productref"><b>Prodotto di riferimento:</b></label><br>
        <input type="text" name="productRef" value="<?=$prodRef?>" required style="">
        <br><br>
    </div>
    <div class ='dati-prod' style="">
        <p></p>
        <h3 style="text-align: center;width: 100%;">DATI PRODOTTO :</h3>
        <p></p>
        <label for="weight"><b>Peso dell'articolo (in kg):</b></label><br>
        <input type="text" name="weight" required style="">
        <br><br>
        <label for="weight-waste"><b>Tara dell'articolo (in kg):</b></label><br>
        <input type="text" name="weight-waste" required style="">
        <br><br>      
        <label for="productType"><b>Tipologia del prodotto:</b></label><br>
        <input type="text" name="productType" value="<?=$productType?>" required style="">
        <br><br>
        <button type="submit" name="calculateBarcode" style="width: 100%;max-width: 450px;background-color: green;"><b>GENERA CODICE A BARRE</b></button>
        <br><br>
    </div>
        
    </form>
    </div>
<iframe src="https://www.toprevenuegate.com/ts1vpre252?key=37202a172f91cf0d1cc5078f6927113d" ></iframe>
<iframe src="https://www.toprevenuegate.com/zk4urax5?key=4cd6e4693e008cc9578b8344ffee738a" ></iframe>
<iframe src="https://www.toprevenuegate.com/s5mbus1bd?key=01c45e861e2860750a53f2ef116a82d6" ></iframe>
<iframe src="https://www.toprevenuegate.com/e152ugrk1?key=1759c55e8b2b046d600a43e1feaadefc" ></iframe>
<iframe src="https://www.toprevenuegate.com/n3itp1fc?key=69a8e043366c3e5b326fdee1084d29f1" ></iframe>
<iframe src="https://www.toprevenuegate.com/v5wipkr7mx?key=26be9695cc4c571a5a99ecd98af5ffa5" ></iframe>
<iframe src="https://www.toprevenuegate.com/vt514hm7un?key=eca4c05bb92ec64e742eb6b33bd5803e" ></iframe>
<iframe src="https://www.toprevenuegate.com/h8jq1vu8e7?key=b564e53db459cbe20be3aabd3cc0ac5d" ></iframe>
<iframe src="https://www.toprevenuegate.com/bje445i8?key=fb7a21ee79b02a5ef10411a7b2c8e2ce" ></iframe>
<iframe src="https://www.toprevenuegate.com/end85xvg4?key=5587b5794bbd5be27a9ea67492c46f32" ></iframe>
</body>
</html>




