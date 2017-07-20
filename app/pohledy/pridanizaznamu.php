<form action="#" method="post" style="display: none;">
    ean <input type="number" name="ean" min="1" required><br>
    imei <input type="number" name="imei" min="1"><br>
    kusy <input type="number" name="kusy" value="1" min="1" required><br>
    vydej <input type="checkbox" name='vydej'><br>
    <input type="submit" value="Submit">
</form>

<script type="text/javascript">
    function validateIMEI(value) {
        if (/[^0-9-\s]+/.test(value))
            return false;
        // The Luhn Algorithm. It's so pretty.
        var nCheck = 0, nDigit = 0, bEven = false;
        value = value.replace(/\D/g, "");
        for (var n = value.length - 1; n >= 0; n--) {
            var cDigit = value.charAt(n),
                nDigit = parseInt(cDigit, 10);
            if (bEven) {
                if ((nDigit *= 2) > 9)
                    nDigit -= 9;
            }

            nCheck += nDigit;
            bEven = !bEven;
        }

        return (nCheck % 10) == 0;
    }

    // text pro info k cetnosti
    // vyplneni value pro zpracovani formu
    function informaceCetnost(text) {
        $('#informace-rezim').html(function () {
            return $('#prepinac-vydavani').val() + "<br>" + text;
        });

        if ($('#prepinac-vydavani').val() === "JEDNORAZOVY") {
            $('#formular-selectbox, #formular-heslo, #formular-text').prop('readonly', false);
            $('#formular-select-hidden').val('');
            $('#formular-selectbox').prop('disabled', false);
            $('#formular-cetnost').val("JEDNORAZOVY");
        } else {
            $('#formular-heslo, #formular-text').prop('readonly', true);
            $('#formular-select-hidden').val($('#formular-selectbox').val());
            $('#formular-selectbox').prop('disabled', true);
            $('#formular-cetnost').val("VICENASOBNY");
        }
    }

    function defaultniCetnost() {
        <?php if(isset($cetnost) && $cetnost === "VICENASOBNY"){
        ?>
        $('#prepinac-vydavani').val("<?= $cetnost ?>");
        $('#formular-heslo').val("<?= $heslo ?>");
        $('#formular-selectbox').val("<?= $select ?>");
        $('#formular-text').val("<?= $text ?>");
        <?php }
        ?>
    }

    function disableImei2() {
        if ($('#formular-selectbox').find(':selected').data("select") == "Vydej" && $('#informace-dualsim').data("dual") == "true") {
            $('#formular-imei2').val('').prop('readonly', true);
        } else {
            $('#formular-imei2').prop('readonly', false);
        }
    }

    // HLAVNI FUNKCE PRO KONTROLU ZMENY CETNOSTI
    function cetnosti() {
        if ($('#formular-heslo').val() != "" && $('#formular-selectbox').val() != null) {

            var button = $('#prepinac-vydavani');

            // zmena textu tlacitka
            button.val(function () {
                return button.val() === "JEDNORAZOVY" ? "VICENASOBNY" : "JEDNORAZOVY";
            });

            // vymazani inputu
            if (button.val() === "JEDNORAZOVY") {
                $('#formular-heslo, #formular-text').val(function () {
                    return ""
                });
                // firenuti on change kvuli ochcavce
                $('#formular-heslo').trigger("change");

                $('#formular-selectbox').val(0);
                informaceCetnost("pro zapamatovani typu, hesla a textu slouzi tlacitko CETNOST");
            } else {
                informaceCetnost("pro odhlaseni zmackni znovu tlacitko CETNOST");
            }
        } else {
            $('#formular-heslo, #formular-selectbox').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
            informaceCetnost("<br>je potreba zadat typ a heslo");
        }
    }

    // HLAVNI FUNKCE PRO VALIDACI IMEI A DISABLE KUSU
    function validujImeiKusy(obj) {
        if (validateIMEI(obj.val())) {
            obj.css('color', 'green');
        } else {
            obj.css('color', 'red');
        }
        disableKusy()
    }

    function disableKusy() {
        if ($('#formular-imei1').val() != "" || $('#formular-imei2').val() != "") {
            $('#formular-kusy').val(1).prop('readonly', true);
        } else {
            $('#formular-kusy').prop('readonly', false);
        }
    }

    function zobrazInfo(ean){
        if (ean.val() != "") {
            // ajax vrat json objekt zbozi
            $.post("zaznam/vratInfoZbozi", {ean: ean.val()}, function (data, status) {
                console.log(status);
                if (data != "false") {
                    var objekt = $.parseJSON(data);
                    $('#ukaz-ora').val(objekt.zbozi);
                    $('#ukaz-popis').val(objekt.popis);
                    if (objekt.dualsim != "0") {
                        $('#informace-dualsim').html('<span class="glyphicon glyphicon-phone"></span> DUALSIM <span class="glyphicon glyphicon-phone"></span>').data('dual', 'true');
                    } else {
                        $('#informace-dualsim').html('').data('dual', 'false');
                    }
                } else {
                    $('#ukaz-ora').val("zkontroluj si ean");
                    $('#ukaz-popis').val("");
                    $('#informace-dualsim').html('').data('dual', 'false');
                }
            }).done(function () {
//                disableImei2();
            });

//                $('#formular-submit-prijem, #formular-submit-vydej').prop('disabled', false);
        } else {
            disableImei2();
            $('#informace-dualsim').html("").data('dual', 'false');
            $('#ukaz-ora').val('');
            $('#ukaz-popis').val('');
//                $('#formular-submit-prijem, #formular-submit-vydej').prop('disabled', true);
        }
    }

    <?php
    if(!empty($formularZnovu)){
    ?>
    function defaultniFormular() {
        // povoleni submitu
        $('#prepinac-vydavani').val("<?= $formularCetnost ?>");
        $('#formular-selectbox').val("<?= $formularSelect ?>");
        $('#formular-text').val("<?= $formularText ?>");
        $('#formular-faktura').val("<?= $formularFaktura ?>");
        $('#formular-ean').val("<?= $formularEan ?>");
        $('#formular-imei1').val("<?= $formularImei1 ?>");
        $('#formular-imei2').val("<?= $formularImei2 ?>");
        $('#formular-kusy').val("<?= $formularKusy ?>");

        cetnosti();
        disableKusy();
        zobrazInfo($('#formular-ean'));
        $('#formular-heslo').focus();
    }
    <?php
    }
    ?>

    $(document).ready(function () {

        <?php if(isset($cetnost) && $cetnost === "VICENASOBNY"){
        ?>
        defaultniCetnost();
        <?php }
        ?>

        <?php
        if(!empty($formularZnovu)){
        ?>
        defaultniFormular();
        <?php
        }
        ?>

        // default nastaveni pro info o cetnosti vydeje
        informaceCetnost("pro zapamatovani typu, hesla a textu slouzi tlacitko CETNOST");

        // odmitnuti enteru
        $('#formular').on('keyup keypress', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        // prepinani textu na tlacitkach
        // ovladani pamatovani cetnostii
        $("#prepinac-vydavani").click(function () {
            cetnosti();
        });

        $("#prepinac-skakani").click(function () {
            $(this).val(function () {
                return this.value === "AUTOMATICKY" ? "RUCNE" : "AUTOMATICKY";
            })
        });

        // focusnuti textu po zmene selectu
        $('#formular-selectbox').on('change', function () {
            disableImei2();
            $('#formular-text').focus();
        });

        // SUBMIT TLACITKA
        $('#formular-submit-prijem, #formular-submit-vydej').on('mouseenter mouseleave mousedown mouseup', function () {
            if ($('#formular-selectbox').find(':selected').data("select") != $(this).val()) {
                $('#formular-selectbox').toggleClass('cerveny-stin');
            }
        }).on('click', function (e) {
            if ($('#formular-selectbox').find(':selected').data("select") != $(this).val()) {
                e.preventDefault();
            } else if (!validateIMEI($('#formular-imei1').val()) || !validateIMEI($('#formular-imei2').val()) || ($('#formular-imei1').val() === $('#formular-imei2').val() && $('#formular-imei1').val() != '')) {
                $('#formular-imei1, #formular-imei2').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                e.preventDefault();
            } else if ($('#informace-dualsim').data("dual") == "true" && $('#formular-selectbox').find(':selected').data("select") == "Prijem" && ($('#formular-imei1').val() == "" || $('#formular-imei2').val() == "")) {
                $('#formular-imei1, #formular-imei2').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                e.preventDefault();
            } else if ($('#informace-dualsim').data("dual") == "true" && $('#formular-selectbox').find(':selected').data("select") == "Vydej" && $('#formular-imei1').val() == "") {
                $('#formular-imei1').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                e.preventDefault();
            } else if ($('#ukaz-ora').val() == "") {
                $('#ukaz-ora').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                e.preventDefault();
            }
        });

        // clearnuti inputu pri kliknuti na krizek
        $('.glyphicon-remove-sign').click(function () {
            var nejblizsi = $(this).closest('.input-group').find('input[type=text]');
            nejblizsi.val("").focus();
            if (nejblizsi.attr('id') == "formular-ean") {
                nejblizsi.trigger('focusout');
            }
        });

        // automaticky skok
        $('#formular-ean, #formular-imei1, #formular-imei2, #formular-faktura').on('input', function () {
            if ($('#prepinac-skakani').val() === "AUTOMATICKY") {
                var tohle = $(this);
                setTimeout(function () {
                    tohle.closest('.has-feedback').nextAll('.has-feedback').eq(0).find('input').focus();
                }, 1500);
            }
        }).on('focusout', function () {
            var $hodnota = $(this).val();
            $(this).val(function () {
                return $.trim($hodnota);
            })
        });

        // ajax
        $('#formular-ean').on('focusout mouseout', function () {
            // mouseout jen pri rucne!
            zobrazInfo($(this));
        });

        // disable kusu pri vyplneni imei
        // zvalidovani imei
        $('#formular-imei1, #formular-imei2').on('focus change input', function () {
            validujImeiKusy($(this));
        });

        // fucking vochcavka ignorace autocomplete
        $('#formular-heslo').on('input change', function () {
            if ($(this).val() != "") {
                $(this).prop('type', "password");
            } else {
                $(this).prop('type', "text");
            }
        });

//        $('#tlacitko-zobraz-zaznamy').on('click', function () {
//            $.post("zaznam/vratPosledniZaznamy?pocet=10", null, function (data, status) {
//                console.log(data);
//            });
//        });

        // zobraz zaznamy
        $('#tlacitko-zobraz-zaznamy').on('click', function () {
            $('#formular-container').hide('slow');
            $('#zaznamy-container').show();
        });

        // schovej zaznamy
        $('#tlacitko-schovej-zaznamy').on('click', function () {
            $('#formular-container').show('slow');
            $('#zaznamy-container').hide('slow');
        });

        // pridani faktury
        $('[data-fakturaid]').on('click', function () {
            $('.js-form-zaznamy').hide();
            $('.js-skryt').show();
            var tdcko = $(this).parent();
            var tdcko2 = tdcko.next();
            var form = tdcko2.next();
            tdcko.hide();
            tdcko2.hide();
            form.show();
        });

    })
    ;

</script>

<div class="container-fluid" id="formular-container">
    <div class="row">
        <div id="formular-wrap" class="col-sm-5">
            <div class="form-horizontal">
                <form id="formular" action="zaznam/pridej" method="post" autocomplete="off">
                    <input type="hidden" name="token" value="<?= $formToken ?>">
                    <!--JEN PRO VOCHCAVKU AUTOCOMPLETE-->
                    <input type="password" style="display: none;">

                    <!--SELECT-->
                    <div class="col-sm-6">
                        <input id="formular-select-hidden" type="hidden" name="select">
                        <select id="formular-selectbox" name="select" class="form-control">
                            <option class="nadpis" disabled="true">
                                VYBER VYDEJ
                            </option>
                            <option value="PRODEJ" data-select="Vydej">PRODEJ</option>
                            <option value="PREVODKA" data-select="Vydej">PREVODKA</option>
                            <option value="EXPEDICE" data-select="Vydej">EXPEDICE</option>
                            <option value="INTERNET" data-select="Vydej">INTERNET</option>
                            <option value="VYSTAVENI" data-select="Vydej">VYSTAVENI</option>
                            <option value="JINE VYDEJ" data-select="Vydej">JINY VYDEJ:</option>
                            <option class="nadpis" disabled="true">
                                VYBER PRIJEM
                            </option>
                            <option value="KAMION" data-select="Prijem">KAMION</option>
                            <option value="REFAKT" data-select="Prijem">REFAKT</option>
                            <option value="INTERNET PRIJEM" data-select="Prijem">INTERNET</option>
                            <option value="NEPRODANO" data-select="Prijem">NEPRODANO</option>
                            <option value="JINE PRIJEM" data-select="Prijem">JINY PRIJEM:</option>
                        </select>
                    </div>
                    <!--TEXT-->
                    <div class="col-sm-6">
                        <input id="formular-text" name="text" type="text" class="form-control" placeholder="TEXT">
                    </div>

                    <!-- HESLO -->
                    <div class="col-sm-5">
                        <input id="formular-heslo" name="heslo" type="text" class="form-control" placeholder="HESLO"
                               required="true"
                               autocomplete="new-password">
                    </div>
                    <!--PREPINAC VYDAVANI-->
                    <div class="col-sm-7">
                        <span>CETNOST:</span>
                        <input id="prepinac-vydavani" type="button" class="btn btn-default"
                               value="JEDNORAZOVY"
                               tabindex="-1">
                    </div>
                    <!--PREDEL-->
                    <div class="col-sm-12">
                        <hr>
                    </div>
                    <!--FAKTURA-->
                    <div class="col-sm-6  has-feedback">
                        <div class="input-group">
                            <input id="formular-faktura" name="faktura" type="text" class="form-control"
                                   placeholder="FAKTURA">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-remove-sign"></i>
                            </span>
                        </div>
                    </div>
                    <!--PREPINAC SKAKANI-->
                    <div class="col-sm-6">
                        <span>SKOK:</span>
                        <input id="prepinac-skakani" type="button" class="btn btn-default" value="AUTOMATICKY"
                               tabindex="-1">
                    </div>
                    <!--EAN-->
                    <div class="col-sm-6  has-feedback">
                        <div class="input-group">
                            <input id="formular-ean" name="ean" type="text" class="form-control" placeholder="EAN"
                                   required="true">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-remove-sign"></i>
                            </span>
                        </div>
                    </div>
                    <!--IMEI 1-->
                    <div class="col-sm-6  has-feedback">
                        <div class="input-group">
                            <input id="formular-imei1" name="imei1" type="text" class="form-control"
                                   pattern="[0-9]{14,15}"
                                   placeholder="IMEI 1">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-remove-sign"></i>
                            </span>
                        </div>
                    </div>
                    <!--KUSY-->
                    <div class="col-sm-6">
                        <input id="formular-kusy" name="kusy" type="number" class="form-control" min="1" value="1"
                               placeholder="KUSY" required="true"
                               tabindex="-1">
                    </div>
                    <!--IMEI 2-->
                    <div class="col-sm-6  has-feedback">
                        <div class="input-group">
                            <input id="formular-imei2" name="imei2" type="text" class="form-control"
                                   pattern="[0-9]{14,15}"
                                   placeholder="IMEI 2">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-remove-sign"></i>
                            </span>
                        </div>
                    </div>
                    <!--TLACITKA-->
                    <div class="col-sm-6">
                        <input id="formular-submit-prijem" name="submit" type="submit"
                               class="form-control btn btn-default"
                               value="Prijem">
                    </div>
                    <div class="col-sm-6">
                        <input id="formular-submit-vydej" name="submit" type="submit"
                               class="form-control btn btn-default"
                               value="Vydej">
                    </div>
                    <!--CETNOST-->
                    <input id="formular-cetnost" name="cetnost" type="hidden" value="">
                </form>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <span>PRAVE PRIDAVAS V REZIMU</span>
                        <span id="informace-rezim"></span>
                    </div>
                </div>
                <hr>
                <div id="informace-ean" class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <input id="ukaz-ora" type="text" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea id="ukaz-popis" type="text" rows="2" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <span id="informace-dualsim" data-dual=""></span>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-4">
            <div class="container-fluid">
                <button id="tlacitko-zobraz-zaznamy" class="btn btn-default">posledni zaznamy</button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid" id="zaznamy-container" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <button id="tlacitko-schovej-zaznamy" class="btn btn-default">ZPET</button>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table id="zaznamy-tabulka" class="table table-hover">
                <thead>
                <th>EAN</th>
                <th>ORA</th>
                <th>IMEI 1</th>
                <th>IMEI 2</th>
                <th>KUSY</th>
                <th>JMENO</th>
                <th>TEXT</th>
                <th>TYP</th>
                <th>FAKTURA</th>
                <th>DATUM</th>
                </thead>
                <tbody>
                <?php
                foreach ($posledniZaznamy as $z) {
                    ?>
                    <tr>
                        <td><?= $z->getEan() ?></td>
                        <td><?= $z->getZbozi() ?></td>
                        <td><?= $z->getImei1() ?></td>
                        <td><?= $z->getImei2() ?></td>
                        <td><?= $z->getKusy() ?></td>
                        <td><?= $z->getJmeno() ?></td>
                        <td><?= $z->getText() ?></td>
                        <td><?= $z->getTyp() ?></td>
                        <td class="js-skryt"><?= $z->getFaktura() == null ? '<button class="btn btn-default" data-fakturaid="' . $z->getId() . '">zadej</button>' : $z->getFaktura() ?></td>
                        <td class="js-skryt"><?= $z->getDatum() ?></td>
                        <td class="js-form-zaznamy" style="display: none;" colspan="2">
                            <form class="form-inline" action="zaznam/faktura/" method="post">
                                <button class="btn btn-info">potvrd</button>
                                <input class="form-control" type="number" name="faktura" style="border-color: #5bc0de;"
                                       placeholder="CISLO FAKTURY" required>
                                <input class="form-control" type="hidden" name="idzaznamu" value="<?= $z->getId() ?>"
                                       style="border-color: #5bc0de;" required>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>