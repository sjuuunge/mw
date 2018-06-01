<?php

for($i = 0; $i < 25; $i++)
{
    $barcode = array(rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9));

    $check = $barcode[0] + $barcode[2] + $barcode[4] + $barcode[6] + $barcode[8] + $barcode[10];
    $check *= 3;
    $check += $barcode[1] + $barcode[3] + $barcode[5] + $barcode[7] + $barcode[9];
    $mod = $check % 10;

    $check_digit = $mod == 0 ? 0 : 10 - $mod;

    $barcode = implode("", $barcode) . $check_digit;

    $url = "https://barcode.tec-it.com/barcode.ashx?data=" . $barcode . "&code=UPCA&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=500&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&qunit=Mm&quiet=0";
    $img = "barcodes/" . $barcode . ".gif";
    file_put_contents($img, file_get_contents($url));
}


?>