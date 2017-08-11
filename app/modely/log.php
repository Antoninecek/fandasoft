<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 17.07.2017
 * Time: 11:49
 */

namespace app\modely;

/**
 * Class Log
 * @package app\modely
 */
class Log {
    private $text, $typ;

    /**
     * Log constructor.
     * @param $text
     * @param $typ
     * <br>
     * <li>1 - uzivatele</li>
     */
    public function __construct($text, $typ) {
        $this->text = $text;
        $this->typ = $typ;
    }

    /**
     * @param Log
     * @return bool
     */
    public function zapisLog() {
        $file = null;
        $text = null;
        switch ($this->getTyp()) {
            case 1:
                $file = 'logy/uzivatele.txt';
                $text = date('d.m.Y H:i:s') . " " . $this->getText(). "\r\n";
                break;
            default:
                return false;
        }

        $to = "frantisek.jukl@fandasoft.cz";
        $subject = "#FANDASOFT - Log";
        $headers = "From: log@fandasoft.cz" . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $emailText = $text;

        mail($to, $subject, $emailText, $headers);

        return file_put_contents($file, $text, FILE_APPEND) === true ? true : false;
    }

    /**
     * @return mixed
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text) {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getTyp() {
        return $this->typ;
    }

    /**
     * @param mixed $typ
     */
    public function setTyp($typ) {
        $this->typ = $typ;
    }

}