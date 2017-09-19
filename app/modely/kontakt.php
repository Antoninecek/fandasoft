<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 19.09.2017
 * Time: 0:06
 */

namespace app\modely;


class Kontakt {

    private $text;
    private $form;

    public function __construct() {
        $this->setText();
        $this->setForm();
    }

    private function setText() {
        $this->text = "<h1>KONTAKT</h1>
<p>Neco te trapi? Mas napad na zlepseni? Napis mi!</p>";
    }

    private function setForm() {
        $this->form = "
        <form id='kontaktform' method='post' action='kontakt/odeslano'>
    <input name='predmet' type=\"text\" class=\"form-control\" placeholder='PREDMET' required>
    <textarea name=\"telo\" class=\"form-control\" placeholder='ZPRAVA' required></textarea>
    <input type='submit' value='ODESLAT' class='form-control'>
</form>";
    }

    public function rendruj(){
        return $this->text . ' ' . $this->form;
    }
}