<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 07.07.2017
 * Time: 0:27
 */
?>
<ol class="navodSeznam">
    <li><a href="navod#Nseznameni">seznameni</a></li>
    <li><a href="navod#Npridat">pridani zaznamu</a></li>
    <li><a href="navod#Nprehled">ziskani prehledu o pohybu zbozi</a></li>
    <li><a href="navod#Nsynchro">synchronizace polozek se sapem</a></li>
    <li><a href="navod#Nvystav">kontrola vystavenosti zbozi</a></li>
    <li><a href="navod#Ninventura">inventura skladu</a></li>
    <li><a href="navod#Nlogy">zaznamy</a></li>
    <li><a href="navod#Nzaloha">zaloha do souboru</a></li>
</ol>

<div class="Nnavod">
    <h2 id="seznameni">Seznameni</h2>
    <div>
        <p>
            Aplikace slouzi pro evidenci bezpecnostniho skladu.
            Pomoci intuitivniho menu lze pridavat zaznamy a sledovat pohyb zbozi v bezpectnostnim sklade.
            Diky moznosti synchronizovat data sapu s nactenymi polozkami, lze kontrolovat vystaveni a delat inventury.
        </p>
    </div>
    <hr>
    <div class="row">
        <h2 id="Npridat">Pridani zaznamu</h2>
        <div class="pull-right col-sm-7">
            <img class="img-responsive" src="pics/pridej.png">
        </div>
        <div class="pull-left col-sm-5">
            K pridani zaznamu o prijmu, nebo vydani zbozi ze skladu slouzi menu PRIDEJ.
            Zaroven se zde zobrazuji i posledni zaznamy.
            <br>
            <ul>
                <li>Do kolonky JMENO napis sve jmeno.</li>
                <li>Checkbox <code>Automat skakani</code> je zaskrtnuty pro automaticke preskoceni kurzoru na IMEI po naskenovani EANu.
                    V pripade, ze potrebujes zadat EAN rucne, musis checkbox odskrtnout.</li>
                <li>Do pole EAN naskenuj carovy kod zbozi, v pripade zaskrtnute moznosti <code>Automat skakani</code>
                    ti kurzor preskoci po nacteni EANu rovnou do pole IMEI.
                    Zaroven se po naskenovani EANu zobrazi dve policka s ORA kodem a ITEMem. Pokud se zobrazi hlaska
                    <code>novy, sparuj se SAPem</code>, zkontroluj, zda skenujes spravny kod. V pripade, ze skenujes spravny kod, ale objevuje se hlaska,
                    je velmi doporucena <a href="navod/#Nsynchro">synchronizace se SAPem</a>.</li>
                <li>Dalsi polozka je IMEI. U dvousimkovych MT je dulezite pro spravnou a sjednocenou identifikaci skenovat vzdy <code>IMEI1</code></li>
                <li>Pokud zadavas IMEI, kolonka POCET nebude k dispozici. V ostatnich pripadech muzes prijmout a vydat najednou vice polozek.</li>
                <li>PRIJEM pro prijmuti zbozi do skladu, VYDEJ pro vydani.</li>
            </ul>

        </div>
    </div>
    <hr>
    <div class="row">
        <h2 id="Nprehled">Ziskani prehledu o pohybu zbozi</h2>
        <div class="pull-right col-sm-8">
            <img class="img-responsive" src="pics/prehled.png">
        </div>
        <div class="pull-left col-sm-4">
            Pro zjisteni pohybu zbozi slouzi PREHLED. Je moznost vyhledavat pomoci EANu nebo ORA. Po vyplneni prislusneho policka se zobrazi kolonky EAN, ITEM, ORA.
            <ul>
                <li>ORA muze byt nekdy ucinejsi pri dohledavani (jeden ORA muze mit ze sapu prirazeno vice EANu).
                    Pri dohledavani veci, co jsou v bezpecaku, musi fungovat EAN, protoze se pod timto EANem prijmoval.</li>
                <li>Prehled MT se zvyraznuje pro lepsi prehled barvami zelena (prijata a vydana IMEI) a cervena (vydani IMEI, ktere nebylo prijmuto).</li>
            </ul>
        </div>
    </div>
    <hr>
    <!-------------->
    <!-------------->
    <!-------------->
    <!-------------->
    <div class="row">
        <h2 id="Nsynchro">Synchronizace polozek se sapem</h2>
        <div class="pull-right col-sm-7">
            <img id="imageSynchro" title="0" class="img-responsive" src="pics/synchro.png">

        </div>
        <div class="pull-left col-sm-5">
            Pro zobrazovani popisku k itemum a uspesne propojeni EANu s ORA.
            <ul>
                <li>Doporucuje se delat (neni nezbytne), jakmile se narazi na neznamy EAN (kontrola pro obsluhu skladu).</li>
                <li>Velmi se doporucuje delat pred vystavovani a inventurou (doplneni popisu, itemu, kusu).</li>
                <li>Pro uspesny import je doporucen nasledujici navod (prepinani obrazku pro navod pod timto textem):
                    <ol>
                        <li>Pracovat na pocitaci s MS Office (warehouse, storemanager) a pod spravnym prihlasenim v sapu (nejspise 13).
                            LibreOffice neumi zpracovat export ze SAPu a jine menu nez WH nebo SM neumi vyexportovat
                            tabulku v MS Office.</li>
                        <li>V sapu v menu <code>MB52 - Seznam skladovych zasob (Zobrazeni skladove zasoby zbozi)</code> vybrat spravny layout
                            a vyexportovat veskerou skladovou zasobu vcetne nulovych polozek (F8 nebo hodiny)</li>
                        <li>Exportovat ze sapu jako kalkulacni tabulku MSOffice, ulozit (treba na plochu) a potvrdit pripadne varovani.</li>
                        <li>Otevrit v MS Office a ulozit jako soubor <code>sap.CSV (coma separated values)</code></li>
                        <li>Potvrdit kompatibilitu</li>
                        <li>zavrit bez ulozeni zmen</li>
                        <li>Tento soubor pote nahrat pres web</li>
                    </ol>
                </li>
            </ul>
            <b>Prochazeni obrazku</b>
            <a class="btn btn-default" onclick="changeImageBack()">predchozi</a>
            <a class="btn btn-default" onclick="changeImage()">dalsi</a>

        </div>
    </div>
    <hr>
    <div class="row">
        <h2 id="Nvystav">Kontrola vystavenosti zbozi</h2>
        <div class="pull-right col-sm-7">
            <img id="imageVystav" title="0" class="img-responsive" src="pics/vystav.png">

        </div>
        <div class="pull-left col-sm-5">
            Slouzi k zjisteni, ktere zbozi neni ze skladu vystavene. Je zapotrebi mit v poradku sap ZDAR. Tato operace muze nejakou minutu trvat.
            <ul>
                <li>Pro uspesny vystup je doporucen nasledujici navod (prepinani obrazku pro navod pod timto textem):</li>
                <li>
                    <ol>
                        <li>Pracovat na pocitaci s MS Office (warehouse, storemanager) a pod spravnym prihlasenim v sapu (nejspise 13).
                            LibreOffice neumi zpracovat export ze SAPu a jine menu nez WH nebo SM neumi vyexportovat
                            tabulku v MS Office.</li>
                        <li>V sapu menu <code>Zobrazeni displej artiklu</code></li>
                        <li>Vyjet veskerou zasobu (F8 nebo hodiny)</li>
                        <li>Zvolit spravny layout</li>
                        <li>Exportovat ze sapu jako kalkulacni tabulku.</li>
                        <li>Otevrit v MS Office a ulozit jako soubor <code>vystav.CSV (coma separated values)</code></li>
                        <li>Potvrdit kompatibilitu</li>
                        <li>Zavrit bez ulozeni zmen</li>
                        <li>Tento soubor pote nahrat pres web</li>
                    </ol>
                </li>
                <li>Vystup mimo zakladnich udaju ukazuje pocet kusu v bezpecaku a pocet volnych jednotek v sapu.</li>
                <li>Je mozne vyfiltrovat pouze urcitou skupinu (stejne se SAP) bud pomoci pole pro to urcene nebo pomoci odkazu v kazdem radku tabulky.</li>
                <li>Je mozne znovu vyvolat tabulku i po zavreni okna pomoci tlacitka <code>Znovu se stejnym souborem</code></li>
                <li>Tlacitko slouzi zaroven jako znovunacteni vsech skupin po filtraci jedne skupiny.</li>
            </ul>
            <b>Prochazeni obrazku</b>
            <a class="btn btn-default" onclick="changeImageBackVystav()">predchozi</a>
            <a class="btn btn-default" onclick="changeImageVystav()">dalsi</a>

        </div>
    </div>
    <hr>
    <div class="row">
        <h2 id="Ninventura">Inventura skladu</h2>
        <div class="pull-right col-sm-7">
            <img id="imageVystav" title="0" class="img-responsive" src="pics/inventura.png">

        </div>
        <div class="pull-left col-sm-5">
            Inventura zbozi na sklade.
            <ul>
                <li>Velmi doporuceno pro spravnou funkci inveturniho vypisu <a href="navod/#Nsynchro">sparovat se sapem</a> pred zacatkem inventury</li>
                <li>Pro vyvolani inventurniho vypisu je nutne importovat ze skeneru soubor <code>trans.dat</code></li>
                <li>Tento vypis je mozne znovu vyvolat tlacitkem <code>Znova se stejnym souborem</code></li>
                <li>Inventurni vypis ukaze ean/ora/item/popis/nactene kusy/kusy ve sklade/rozdil/kusy v sapu/moznost opravit inventuru</li>
                <li>Tabulku lze dostat do excelu oznacenim cele stranky (ctrl+a) a vlozenim (ctrl+v) do souboru</li>
                <li>Kolonky <code>O. nacteno</code> a <code>O. bezpecak</code> slouzi k oprave inventury</li>
                <li>Pro opravu inventury, at uz nactenych nebo skladovych kusu, slouzi tlacitko <code>Aktualizuj</code></li>
                <li style="font-weight: bolder">V pripade pridani jakehokoliv zaznamu pres menu PRIDEJ v prubehu upravy inventury, je potreba nacist inventuru <code>Znova se stejnym souborem</code>.
                    Protoze pri kliknuti na <code>Aktualizuj</code> se vsechny momentalne provedene zmeny v PRIDEJ vyrusi.</li>
                <li>Srovnane rozdily z tabulky po kliknuti na <code>Aktualizuj</code> zmizi.</li>
                <li>Nulove rozdily lze zobrazit a skryt tlacitkem <code>Zobraz nuly</code></li>
                <li>Pro srovnani MT jsou moznosti:
                    <ol>
                        <li>bezpecak > nacteno => lze srovnat pomoci menu inventura (prida se zaznam o vydani MT bez prirazenych IMEI)</li>
                        <li>bezpecak > nacteno => lepsi zpusob (prida se zaznam o vydani MT bez prirazenych IMEI)</li>
                        <li>nacteno > bezpecak => aby byla veskera IMEI evidovana, doporucuje se pridat standartnim zpusobem pres menu PRIDEJ a pote znovu
                            vyvolat inventuru tlacitkem <code>Znova se stejnym souborem</code></li>
                    </ol>
                </li>
            </ul>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="pull-right col-sm-7">
            <img class="img-responsive" src="pics/logy.png">
        </div>

        <div class="pull-left col-sm-5">
            <h2 id="Nlogy">Zaznamy</h2>
            <div>
                Kdyz se vyda MT s IMEI, ktery by podle systemu nemel byt k dispozici, zaznamena se tato udalost, aby pri inventure byl snadneji dohledan.
                Zaroven je moznost tyto zaznamy oznacit jako vyreseny a naopak jako nevyreseny stiskem symbolu fajfky/krizku u kazdeho logu.
            </div>
        </div>

    </div>
    <hr>
    <div class="row">
        <div class="pull-right col-sm-7">
            <img class="img-responsive" src="pics/zaloha.png">
            <img class="img-responsive" src="pics/pruzkumnik.png">
        </div>
        <h2 id="Nzaloha">Zaloha do souboru</h2>
        <div class="pull-left col-sm-5">
            Pro zachovani dat i po selhani pocitace, nebo zasahu do systemu, ktery by mohl poskodit databazi, je dobre pravidelne zalohovani.
            <ul>
                <li>System provede zalohu do souboru, ke kterymu ti vypise cestu</li>
                <li>V adresari najdes poslednich 20 zaloh</li>
                <li>Tento soubor muzes pote zalohovat na jiny pocitac</li>
                <li>TIP: Pro lehci hledani souboru zkopiruj cestu do posledniho lomitka a vloz ji do adresoveho radku pruzkumnika (viz obrazek).</li>
            </ul>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">

    function changeImageBackVystav() {
        imageData = [
            "pics/vystav.png",
            "pics/zdar.png",
            "pics/layout.png",
            "pics/zvollayout.png",
            "pics/kalkulacnitabulka.png",
            "pics/ulozitmsoffice.png",
            "pics/ulozitjako.png",
            "pics/csv.png",
            "pics/kompatibilita.png",
            "pics/ulozenizmen.png"
        ];
        var n = document.getElementById("imageVystav").getAttribute("title");

        n--;
        if (n < 0) {
            n = imageData.length - 1;
        }
        document.getElementById("imageVystav").title = n;
        document.getElementById("imageVystav").src = imageData[n];
    }

    function changeImageVystav() {
        imageData = [
            "pics/vystav.png",
            "pics/zdar.png",
            "pics/layout.png",
            "pics/zvollayout.png",
            "pics/kalkulacnitabulka.png",
            "pics/ulozitmsoffice.png",
            "pics/ulozitjako.png",
            "pics/csv.png",
            "pics/kompatibilita.png",
            "pics/ulozenizmen.png"
        ];
        var n = document.getElementById("imageVystav").getAttribute("title");

        n++;
        if (n >= imageData.length) {
            n = 0;
        }
        document.getElementById("imageVystav").title = n;
        document.getElementById("imageVystav").src = imageData[n];
    }

    function changeImageBack() {
        imageData = [
            "pics/synchro.png",
            "pics/skladzasob.png",
            "pics/kalkulacnitabulka.png",
            "pics/ulozitmsoffice.png",
            "pics/ulozitjako.png",
            "pics/csv.png",
            "pics/kompatibilita.png",
            "pics/ulozenizmen.png"
        ];
        var n = document.getElementById("imageSynchro").getAttribute("title");

        n--;
        if (n < 0) {
            n = imageData.length - 1;
        }
        document.getElementById("imageSynchro").title = n;
        document.getElementById("imageSynchro").src = imageData[n];
    }

    function changeImage() {
        imageData = [
            "pics/synchro.png",
            "pics/skladzasob.png",
            "pics/kalkulacnitabulka.png",
            "pics/ulozitmsoffice.png",
            "pics/ulozitjako.png",
            "pics/csv.png",
            "pics/kompatibilita.png",
            "pics/ulozenizmen.png"
        ];
        var n = document.getElementById("imageSynchro").getAttribute("title");

        n++;
        if (n >= imageData.length) {
            n = 0;
        }
        document.getElementById("imageSynchro").title = n;
        document.getElementById("imageSynchro").src = imageData[n];
    }
</script>
