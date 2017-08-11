<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 23.05.2017
 * Time: 17:14
 */

namespace app\kontrolery;


use libs\Kontroler;
use libs\Pohled;
use app\modely\Spravcepobocek;
use app\modely\Upozorneni;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class pobocka extends Kontroler {

    /**
     * @var Spravcepobocek
     */
    private $sp;

    public function __construct() {
        $this->setSablonu('app/sablony/vychozi');
        $this->setPripojeni();
        $this->sp = $this->vytvorSpravce("pobocek");
    }

    public function home(){
        $handler = new Home();
        $handler->index();
    }

    public function index() {

        $this->sablona->set('titulek', 'pobocka');
        $content = new Pohled('app/pohledy/pobocka');
        $content->set('pobockyList', $this->sp->vratVsechnyPobocky());
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }

    public function nastav() {
        $jmeno = empty($_POST['pobockyJmeno']) ? null : $_POST['pobockyJmeno'];
        $heslo = empty($_POST['pobockyHeslo']) ? null : hash("sha256", $_POST['pobockyHeslo']);
        if ($jmeno && $heslo) {

            $pobocka = $this->sp->vratPobocku($jmeno, $heslo);
            if ($pobocka) {
                $this->nastavCookie($pobocka);
                $scriptPresmerovani = "<script type='text/javascript'>$(document).ready(function(){setTimeout(function(){ location.href='home' }, 2000);})</script>";
                $this->sablona->set('upozorneni', new Upozorneni('success', 'pobocka nastavena - pro dokonceni pockej 2 vteriny' . $scriptPresmerovani));
            } else {
                $this->sablona->set('upozorneni', new Upozorneni('danger', 'spatne udaje o pobocce'));
            }
            unset($_POST);
            $this->index();
            exit();
        } else {
            $this->index();
            exit();
        }
    }

    public function zrus() {
        if (!empty($_SESSION[SESSION_POBOCKA])) {
            $this->zrusCookie();
            $this->sablona->set('upozorneni', new Upozorneni('info', 'zruseno'));
        }
        $this->index();
    }

    /**
     * @param app\modely\pobocka
     * @return bool
     */
    private function nastavCookie($pobocka) {
        $signer = new Sha256();

        $token = (new Builder())->setIssuer(WEB_URL)// Configures the issuer (iss claim)
        ->setAudience(WEB_URL)// Configures the audience (aud claim)
//        ->setId('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
        ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
        ->setNotBefore(time())// Configures the time that the token can be used (nbf claim)
        ->setExpiration(time() + 60 * 60 * 24 * 182)// Configures the expiration time of the token (nbf claim)
        ->set('pobocka', json_encode($pobocka))// Configures a new claim, called "uid"
        ->sign($signer, SIGN_JWT)
            ->getToken(); // Retrieves the generated token

        $token->getHeaders(); // Retrieves the token headers
        $token->getClaims(); // Retrieves the token claims

        setcookie(COOKIE_POBOCKA, $token, time() + (365 * 24 * 60 * 60), "/");
    }

    private function zrusCookie() {
        setcookie(COOKIE_POBOCKA, $_COOKIE[COOKIE_POBOCKA], time() - (365 * 24 * 60 * 60), "/");
        unset($_SESSION[SESSION_POBOCKA]);
    }
}