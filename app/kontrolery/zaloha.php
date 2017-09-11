<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 02.08.2017
 * Time: 8:56
 */

namespace app\kontrolery;

use app\modely\Upozorneni;
use PDO;
use PDOException;
use libs\Kontroler;
use libs\Pohled;
use app\modely\Spravcezaloh;

class zaloha extends Kontroler {

    /**
     * @var Spravcezaloh
     */
    private $szaloh = null;

    public function __construct() {
        $this->sablona = new Pohled('app/sablony/vychozi');
        $this->szaloh = $this->vytvorSpravce('zaloh');
    }

    public function index() {

        $this->sablona->set('titulek', 'Zaloha');
        $content = new Pohled('app/pohledy/upload');
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }


    public function vlozSap() {
        $content = new Pohled('app/pohledy/upload');
        try {
            if ($this->ulozSoubor()) {
                $ar = $this->souborDoDb();
                if ($ar > 100) {
                    $this->sablona->set('upozorneni', new Upozorneni('success', 'celkovy pocet zaznamu je ' . $ar));
                } else {
                    $this->sablona->set('upozorneni', new Upozorneni('danger', 'neco se podelalo, zkontroluj znovu, ze vkladas spravny soubor, se spravnym layoutem'));
                }
            }
        } catch (\ErrorException $e) {
            $this->sablona->set('upozorneni', new Upozorneni('danger', 'neco se podelalo, zkontroluj znovu, ze vkladas spravny soubor, se spravnym layoutem'));
        }
        $this->sablona->set('titulek', 'Zaloha');
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }

    private function ulozSoubor() {
        if (ENVIRONMENT == 'PROD') {
            $target_dir = "up/";
        } else {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . ROOT_DIR . "up/";
        }
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

        if ($_FILES["fileToUpload"]["name"] != "sap.csv") {
            $uploadOk = 0;
        }
// Check if file already exists
        if (file_exists($target_file) && $_FILES["fileToUpload"]["name"] == "sap.csv") {
            //echo "Sorry, file already exists.";
            @unlink($target_file);
        }

// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return false;
            //echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

                return true;
                //echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {

                return false;
                //echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    private function souborDoDb() {
        // nacist do db z csv
        $databasehost = DB_HOST;
        $databasename = DB_NAME;
        $databasetable = "sap";
        $databaseusername = DB_USER;
        $databasepassword = DB_PASS;
        $fieldseparator = ";";
        $lineseparator = "\n";
        if (ENVIRONMENT == 'PROD') {
            $csvfile = "up/sap.csv";
        } else {
            $csvfile = $_SERVER['DOCUMENT_ROOT'] . ROOT_DIR . "up/sap.csv";
        }


        if (!file_exists($csvfile)) {
            die("File not found.");
        }

        try {
            $pdo = new PDO("mysql:host=$databasehost;dbname=$databasename", $databaseusername, $databasepassword, array(
                    PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES cp1250"
                )
            );
        } catch (PDOException $e) {
            die("database connection failed: " . $e->getMessage());
        }

//        print_r($this->szaloh->zalohujTabulku('sap'));
        $pdo->exec('delete from sap');

        $file = fopen($csvfile, "r");
        $rows = 0;
        while (($getData = fgetcsv($file, 10000, ";")) !== FALSE) {
//            var_dump($getData);
            $data1 = str_replace('"', ' ', $getData[1]);
            $data3 = str_replace('"', ' ', $getData[3]);

//            $pdo->exec("insert into sap (zbozi, model, ean, popis, kusy, kategorie) values (/'" . $getData[0] . "/',/'" . $getData[1] . "/',/'" . $getData[2] . "/',/'" . $getData[3] . "/',/'" . $getData[4] . "/',/'" . $getData[5] ."/')");
            $pdo->exec("insert into sap (zbozi, model, ean, popis, kusy, kategorie) values (\"" . $getData[0] . "\",\"" . $data1 . "\",\"" . $getData[2] . "\",\"" . $data3 . "\",\"" . $getData[4] . "\",\"" . $getData[5] . "\")");
            $rows++;
        }

//        $affectedRows = $pdo->exec("
//      LOAD DATA LOCAL INFILE " . $pdo->quote($csvfile) . " INTO TABLE `$databasetable`
//      FIELDS TERMINATED BY " . $pdo->quote($fieldseparator) . "
//      LINES TERMINATED BY " . $pdo->quote($lineseparator));

        return $rows;
    }

}