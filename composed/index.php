<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require '../vendor/autoload.php';
// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;
$manager = new ImageManager(array('driver' => 'imagick'));

$count = isset($_GET['count']) ? $_GET['count'] : 1;
$used = [];

$barcode = isset($_GET['barcode']) ? generate_barcode($_GET['barcode']) : FALSE;


if(!$barcode)
{
    for($i = 0; $i < $count; $i++)
    {
        // Prep canvas with border
        $image = $manager->canvas(520, 600, '#FFF');
        $image->rectangle(0, 0, 520, 600, function ($draw) {
            $draw->background('rgba(255, 255, 255, 0)');
            $draw->border(3, '#000');
        });

        // 1. Generate a barcode
        $barcode = generate_barcode();
        $image->insert($barcode, 'top-left', 148, 26);


        // 2. Generate a colored line
        $image->line(10, 540, 510, 540, function ($draw) {
            $draw->color('#' . $_GET['hex']);
            $draw->width(15);
        });

        // 3. Combine into 52 * 60mm image and add random label
        $code = random_code($used);
        $used[] = $code;

        $image->text($code, 260, 500, function($typography) {
            $typography->file('../fonts/OCRA-Mac.ttf');
            $typography->size(77);
            $typography->color('#000');
            $typography->align('center');
        });

        $image->save('./' . $_GET['hex'] . '/' . $_GET['hex'] . '-' . $code . '.jpg', 100);
    }
}
else
{
    // Prep canvas with border
    $image = $manager->canvas(520, 600, '#FFF');
    $image->rectangle(0, 0, 520, 600, function ($draw) {
        $draw->background('rgba(255, 255, 255, 0)');
        $draw->border(3, '#000');
    });
    // A barcode was supplied, so we only render that one
    $image->insert($barcode, 'top-left', 148, 26);

    // 2. Generate a colored line
    $image->line(10, 540, 510, 540, function ($draw) {
        $draw->color('#' . $_GET['hex']);
        $draw->width(15);
    });

    // 3. Combine into 52 * 60mm image and add random label
    $code = random_code($used);
    $used[] = $code;

    $image->text($code, 260, 500, function($typography) {
        $typography->file('../fonts/OCRA-Mac.ttf');
        $typography->size(77);
        $typography->color('#000');
        $typography->align('center');
    });

    $image->save('./' . $_GET['hex'] . '/THE_ONE_' . $_GET['hex'] . '-' . $code . '.jpg', 100);
}


function generate_barcode($in=FALSE)
{
    $barcode = array(rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), rand(0, 9), 0, 0, 0);

    $check = $barcode[0] + $barcode[2] + $barcode[4] + $barcode[6] + $barcode[8] + $barcode[10];
    $check *= 3;
    $check += $barcode[1] + $barcode[3] + $barcode[5] + $barcode[7] + $barcode[9];
    $mod = $check % 10;

    $check_digit = $mod == 0 ? 0 : 10 - $mod;

    $barcode = $in ? $in : implode("", $barcode) . $check_digit;

    $url = "https://barcode.tec-it.com/barcode.ashx?data=" . $barcode . "&code=UPCA&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=120&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&qunit=Mm&quiet=0";
    return file_get_contents($url);
}

function random_code($not_in)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 3; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return !in_array($randomString, $not_in) ? $randomString : random_code($not_in);
}


?>