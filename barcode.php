<?php

$barcode = $_GET['barcode'];

if(!$barcode)
{
    echo "No barcode supplied.";
    exit;
}


$url = "https://barcode.tec-it.com/barcode.ashx?data=" . $barcode . "&code=UPCA&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=500&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&qunit=Mm&quiet=0";
echo('<img src="' . $url . '" />');

?>