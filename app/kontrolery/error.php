<?php

namespace app\kontrolery;

use libs\Kontroler;
use libs\Pohled;

class Error extends Kontroler {

    public function __construct() {
        $this->setSablonu('app/sablony/vychozi');
    }

    public function debugError(\Exception $exception) {
        $this->sablona->set('titulek', '500 - server error');
        $obsah = new Pohled('app/pohledy/debugger');
        $obsah->set('myException', $exception);
        $this->sablona->set('content', $obsah->rendruj());
        echo $this->sablona->rendruj();
        exit();
    }

    public function error500($e){
        // TODO LOGUJ CHYBU
        $this->sablona->set('titulek', '500 error');
        $obsah = new Pohled('app/pohledy/500');
        $this->sablona->set('content', $obsah->rendruj());
        echo $this->sablona->rendruj();
        exit();
    }

    public function error404() {
        $this->sablona->set('titulek', '404 - not found');
        $obsah = new Pohled('app/pohledy/404');
        $this->sablona->set('content', $obsah->rendruj());
        echo $this->sablona->rendruj();
        exit();
    }

}