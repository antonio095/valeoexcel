<pre>
<?php

include "MSXLS.php"; //MSCFB.php is 'required once' inside MSXLS.php

// if (isset($_POST['submit'])) {

$file = $_FILES['fileToUpload']['tmp_name'];

$excel = new MSXLS($file);

if ($excel->error) {
    die($excel->err_msg);
}

// }

//Terminate script execution, show error message.

$arrayFor501 = [];
$store505 = "";
$arrayOf505 = [];
$arrayOf551 = [];
$rows = []; // stores the data from the excel in rows
$numeroDeFactura = "";
$fechaDeFactura = "";
$valorTotalMercancia = "";
$arrayFor551 = [];
$arrayOfFacturas = [];

$excel->switch_to_row();

while ($row = $excel->read_next_row()) {

    $rows[] = $row;
}

$claveDeDocumento = $rows[1][1];
$numeroDePedimentos = $rows[1][0];

$numeroDePedimentos = substr($numeroDePedimentos, -7);

array_push($arrayFor501, "501", "|", "2", "|", $claveDeDocumento, "|", $numeroDePedimentos);
array_push($arrayFor501, "|", "006059", "|", "0", "|", "0", "|", "0", "|", "0", "|", "0", "|", "0", "|", "0", "|");
array_push($arrayFor501, "7", "|", "7", "|", "7", "|", "7", "|", "|", "|", "|", "|", "|", "|", "|", "|" . PHP_EOL);

$myfile = fopen("excelTest.txt", "w") or die("Unable to open file!");

fwrite($myfile, implode("", $arrayFor501));

$sizeOfRows = sizeof($rows);

for ($i = 4; $i <= $sizeOfRows - 1; $i++) {

    $numeroDeFactura = $rows[$i][0];
    $fechaDeFactura = $rows[$i][1];
    $fechaDeFactura = str_replace("-", "", $fechaDeFactura);
    $valorTotalMercancia = $rows[$i][6];

    $store505 = "505" . "|" . $numeroDeFactura . "|" . $fechaDeFactura . "|DAP|USD|" . $valorTotalMercancia . "|" . $valorTotalMercancia . "|VALEO||USA||||||||||||||||||||||||||||||||" . PHP_EOL; // insert to $arrayFor551 items
    array_push($arrayOf505, $store505);
    array_push($arrayOfFacturas, $numeroDeFactura);

    error_reporting(0);

    if (sizeof($rows[$i]) > 30) {

        array_pop($arrayOf505);
        $fractionOf551 = $rows[$i][6];
        $description = $rows[$i][5];
        $numeroDeParte = $rows[$i][21];
        $valorMercancia = $rows[$i][12];

        $cantidadComercial = $rows[$i][10];
        $umc = $rows[$i][9];
        $cantidadTarifa = $rows[$i][11];
        $valorAgregado = $rows[$i][14];
        $numeroDeFactura = $rows[$i][19];

        array_push($arrayFor551, "551", "|", $fractionOf551 . "|", $description, "|" . $numeroDeParte . "|", $valorMercancia, "|", $cantidadComercial, "|", $umc, "|"); // insert to $arrayFor551 items
        array_push($arrayFor551, $cantidadTarifa . "|", $valorAgregado, "|", "0", "|", "0", "|", "|", "|", "USA", "|", "USA"); // insert to $arrayFor551 items
        array_push($arrayFor551, "|", "|", "|", "|", "|", "0", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", "|", $numeroDeFactura);

        $imploded551 = implode("", $arrayFor551);

        array_push($arrayOf551, $imploded551);

        $arrayFor551 = [];
    }
}

$tempFacturas = [];

for ($j = 0; $j <= sizeof($arrayOfFacturas) - 1; $j++) {

    if (preg_match('#[0-9]#', $arrayOfFacturas[$j])) {

        array_push($tempFacturas, $arrayOfFacturas[$j]);
        // break;
    } else {
        break;
    }

}

$arrayOfTemps = [];
$arrayPermanentFor551 = [];

array_shift($arrayOf551);

for ($k = 0; $k <= sizeof($tempFacturas) - 1; $k++) {

    for ($x = 0; $x <= sizeof($arrayOf551) - 1; $x++) {
        if (strpos($arrayOf551[$x], $tempFacturas[$k])) {
            // echo $arrayOf551[$x] . "</br>";
            array_push($arrayOfTemps, $arrayOf551[$x]);
        }
    }

    array_push($arrayPermanentFor551, $arrayOfTemps);
    $arrayOfTemps = [];

}

for ($k = 0; $k <= sizeof($arrayPermanentFor551) - 1; $k++) {

    $sizeOfInsideArray = sizeof($arrayPermanentFor551[$k]);

    for ($x = 0; $x <= $sizeOfInsideArray - 1; $x++) {
        $temps = explode("|", $arrayPermanentFor551[$k][$x]);
        array_pop($temps);
        $temps = implode("|", $temps);
        $arrayPermanentFor551[$k][$x] = $temps;
    }
}

// print_r($tempFacturas);

for ($w = 0; $w <= sizeof($tempFacturas) - 1; $w++) {

    $sizeOfInsideArray = sizeof($arrayPermanentFor551[$w]);
    $stringFor505 = $arrayOf505[$w];
    // echo  $stringFor505 . "</br>";
    fwrite($myfile, $stringFor505);

    for ($m = 0; $m <= $sizeOfInsideArray - 1; $m++) {
        $stringFor551 = $arrayPermanentFor551[$w][$m] . PHP_EOL;
        fwrite($myfile, $stringFor551);
    }

}

// print_r($arrayOf505);

// fwrite($myfile, implode("", $arrayFor501));

// print_r($arrayPermanentFor551);

fclose($myfile); // close the txt;

$file = "excelTest.txt";

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}

// readfile('excelTest.txt');
// print_r($rows);

?>
</pre>

<html>
<title></title>
<head></head>
<body>
    <div>
        <p>El TXT FUE CREADO</p>
        <!-- <button hr = "index.php">Crear Nuevo Archivo</button> -->
        <button onclick="window.location.href = 'index.php';">Crear Nuevo Archivo</button>
    </div>
</body>
</html>