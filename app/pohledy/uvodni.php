<div class="container-fluid">
    <div class="row">
        <h1>#FANDASOFT</h1>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h4>unor 2016 - Problem! Jak evidovat zbozi v bezpecaku?</h4>
            <h4>* 22. brezen 2016 #FANDASOFT v. 1.0 - Aplikace bezi primo na PC v bezpecaku.</h4>
            <h3>SEZNAM ZMEN</h3>
            <ul>
                <li>V. 1.0</li>
                <ul>
                    <li>Prijem/vydej zbozi.</li>
                    <li>Kontrola dle ORA.</li>
                    <li>Vyhledani zaznamu.</li>
                    <li>Inventura.</li>
                    <li>kveten 2016 - Mlada Boleslav</li>
                    <li>cerven 2016 - pridano heslo</li>
                    <li>spren 2016 - komunikace se slovenskym vedenim</li>
                </ul>
                <li>zari 2k16 #FANDASOFT v. 2.0</li>
                <ul>
                    <li>webhosting na adrese http://nay2.sk/sklad_new/sklad</li>
                    <li>zari 2016 - duben 2017</li>
                    <ul>
                        <li>vylepseni a zrychleni pridavani polozek - automaticky skok, zapamatovani prihlaseni</li>
                        <li>kontrola dualsim telefonu</li>
                        <li>typy pohybu</li>
                        <li>pri vydeji automaticke dohledani druheho imei</li>
                        <li>posledni pohyby</li>
                        <li>synchronizace exportu ze sapu</li>
                        <li>kontrola vystaveni dle ZDARu</li>
                    </ul>
                </ul>
                <li>18. cervenec 2k17 #FANDASOFT v. 3.0</li>
                <ul>
                    <li>prepsani kodu #FANDASOFTu - registrovana vlastni domena https://fandasoft.cz</li>
                    <ul>
                        <li>rozdeleni pobocek</li>
                        <li>certifikat pro bezpecnost prenasenych informaci</li>
                    </ul>
                    <li>cervenec 2017 - srpen 2017</li>
                    <ul>
                        <li>zrychleni formulare pri prijmu/vydeji zbozi</li>
                        <li>pridani faktury</li>
                        <li>menu pro zaznam vystaveni a zabaleni zbozi - kontrola zdaru</li>
                        <li>simulace zdaru s filtrovanim vystaveneho/nevystaveneho zbozi</li>
                        <li>uzivatelske menu pro spravu uzivatelu</li>
                    </ul>
                    <li>13. zari 2017</li>
                    <ul>
                        <li>Opravena chyba, kde v menu Pridej skoncilo odeslani formulare chybou.</li>
                        <li>Zjisteno, ze Datec konecne komunikuje s menu ZDAR v SAPu.</li>
                    </ul>
                    <li>19. zari 2k17 Do prehledu pridana SUMa a obarveni imei.</li>
                </ul>
            </ul>
        </div>
        <div class="col-sm-6 text-center">
            <div id="kontakt" style="text-align: center;">
                <?php if (!empty($kontakt)) {
                    echo $kontakt;
                } else {
                    ?>
                    <a href="kontakt"><img id="kontaktimg" class="img-responsive" src="logo.png"></a>
                    <span id="kontaktklik" style="text-transform: uppercase"><span class="glyphicon glyphicon-arrow-up"></span> klikni pro kontakt <span class="glyphicon glyphicon-arrow-up"></span></span>
                    <?php
                } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#kontaktimg").css('height', $(document).scrollTop());
        $('#kontaktklik').hide();
        $(document).scroll(function () {
            $("#kontaktimg").css('height', $(document).scrollTop());
            if ($(document).height() - $(window).height() - $(window).scrollTop() < 100) {
                $('#kontaktklik').show();
            } else {
                $('#kontaktklik').hide();
            }
        })
    })
</script>

