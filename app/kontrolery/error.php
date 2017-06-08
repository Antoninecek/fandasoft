<?php

namespace app\kontrolery;

use libs\Kontroler;
use libs\Pohled;

class Error extends Kontroler {

    public function __construct() {
        $this->setSablonu('app/sablony/vychozi');
    }

    public function notFound() {
        $this->sablona->set('titulek', '404 - not found');
        $obsah = new Pohled('app/pohledy/404');
        $this->sablona->set('content', $obsah->rendruj());
        echo $this->sablona->rendruj();
    }

    public function database(){
        $this->sablona->set('titulek', 'db error');
        $obsah = new Pohled('app/pohledy/databaseerror');
        $this->sablona->set('content', $obsah->rendruj());
        echo $this->sablona->rendruj();
    }
}