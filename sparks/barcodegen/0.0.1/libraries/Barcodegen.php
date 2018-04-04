<?php

//require_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'third_party' . DIRECTORY_SEPARATOR . 'datamatrix.php');
//require_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'third_party' . DIRECTORY_SEPARATOR . 'pdf417.php');
//require_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'third_party' . DIRECTORY_SEPARATOR . 'qrcode.php');

class barcodegen {

  public static function barcode($value, $code = 'C39', $destination_path = '') {
    if ($code == 'C39') {
      require_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'
        . DIRECTORY_SEPARATOR . 'third_party'
        . DIRECTORY_SEPARATOR . 'dns1dbarcode.php');

      $d1 = new DNS1DBarcode();
      $d1->save_path = "";

      return $d1->getBarcodeHTML($value, $code);
    }

    return;
  }

  public function barcode_batch($values, $code = 'C39') {
    return;
  }

  public function printHello() {
    /*
      $d2=new DNS2DBarcode();
      $d2->save_path="";
      //echo $d2->getBarcodePNGPath("dinesh", 'pdf417',2, 2, 'black');
      //echo $d2->getBarcodePNGPath("dinesh", 'datamatrix',2, 2, 'black');
      //echo $d2->getBarcodePNGPath("dinesh", 'qrcode',2, 2, 'black');

      $d1=new DNS1DBarcode();
      $d1->save_path="";
      echo $d1->getBarcodePNGPath("1234567", 'C39');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'C39+');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'C39E');
      echo "<br />";

      echo $d1->getBarcodePNGPath("1234567", 'C39E+');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'C93');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'S25');
      echo "<br />";

      echo $d1->getBarcodePNGPath("1234567", 'S25+');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'I25');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'I25+');
      echo "<br />";

      echo $d1->getBarcodePNGPath("1234567", 'C128');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'C128A');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'C128B');
      echo "<br />";

      echo $d1->getBarcodePNGPath("1234", 'C128C');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'EAN2');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'EAN5');
      echo "<br />";

      echo $d1->getBarcodePNGPath("1234567", 'EAN8');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'EAN13');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'UPCA');
      echo "<br />";

      echo $d1->getBarcodePNGPath("1234567", 'UPCE');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'MSI');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'MSI+');
      echo "<br />";

      echo $d1->getBarcodePNGPath("1234567", 'POSTNET');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'PLANET');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'RMS4CC');
      echo "<br />";

      echo $d1->getBarcodePNGPath("1234567", 'KIX');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'IMB');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'CODABAR');
      echo "<br />";

      echo $d1->getBarcodePNGPath("1234567", 'CODE11');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'PHARMA');
      echo "<br />";
      echo $d1->getBarcodePNGPath("1234567", 'PHARMA2T');
      echo "<br />";
     */
  }

}
