<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 19.09.2017
 * Time: 0:24
 */

namespace app\kontrolery;


use app\modely\Upozorneni;
use libs\Kontroler;
use libs\Pohled;

class Kontakt extends Kontroler {

    public function __construct() {
        $this->setSablonu('app/sablony/vychozi');
    }

    public function index(){
        $this->sablona->set('titulek', 'kontakt');
        $content = new Pohled('app/pohledy/uvodni');
        $kontakt = new \app\modely\Kontakt();
        $content->set('kontakt', $kontakt->rendruj());
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }

    public function odeslano($parametry = null){
        if(!empty($parametry['predmet']) && !empty($parametry['telo'])){
            $to = "frantisek.jukl@fandasoft.cz";
            $subject = "#FANDASOFT - ". $parametry['predmet'];
            $headers = "From: kontakt@fandasoft.cz" . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $emailText = $parametry['telo'] . "<br>Odeslano uzivatelem z aplikace #Fandasoft.";

            mail($to, $subject, $emailText, $headers);
        }
        $this->sablona->set('titulek', 'odeslano');
        $content = new Pohled('app/pohledy/uvodni');
        $this->sablona->set('upozorneni', new Upozorneni('success', 'Ja ti dekuji!'));
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }

}