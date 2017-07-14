<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 06.07.2017
 * Time: 19:35
 */

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4">
            <h1>Oprava</h1>
            <p>Prave se chystas opravit tento typ zaznamu proveden primo tebou, zadej prosim znovu sve heslo pro
                potvrzeni.</p>
        </div>
        <div class="col-sm-8">
            <form class="" action="zaznam/oprav" method="post">
                <input class="form-control" type="hidden" name="formtoken" value="<?= $formtoken ?>">
                <div class="form-group">
                    <label class="control-label col-sm-2">EAN</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="ean" value="<?= $zaznam->getEan() ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">IMEI1</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="imei1" value="<?= $zaznam->getImei1() ?>"
                               placeholder="---" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">IMEI2</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="imei2" value="<?= $zaznam->getImei2() ?>"
                               placeholder="---" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">KUSY</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="kusy" value="<?= $zaznam->getKusy() ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">JMENO</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" value="<?= $uzivatel->getJmeno() ?>" readonly>
                        <input class="form-control" type="hidden" name="jmeno" value="<?= $zaznam->getJmeno() ?>"
                               readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">TYP</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="typ" value="<?= $zaznam->getTyp() ?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">TEXT</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="text" value="<?= $zaznam->getText() ?>"
                               placeholder="---" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">FAKTURA</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="faktura" value="<?= $zaznam->getFaktura() ?>"
                               placeholder="---"
                               readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">POBOCKA</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text"
                               value="<?= $_SESSION[SESSION_POBOCKA]->getMesto() . " " . $_SESSION[SESSION_POBOCKA]->getNazev() ?>"
                               readonly>
                        <input class="form-control" type="hidden" name="pobocka" value="<?= $zaznam->getPobocka() ?>"
                               readonly>
                    </div>
                </div>
                <div class="form-group">
                    <!--JEN PRO VOCHCAVKU AUTOCOMPLETE-->
                    <input type="password" style="display: none;">

                    <label class="control-label col-sm-2">HESLO</label>
                    <div class="col-sm-4">
                        <input id="formular-heslo" class="form-control" type="text" name="heslo" value="" style="background-color: #31b0d5; color: white;" required>
                    </div>
                </div>
                <div class="col-sm-offset-10">
                    <input class="btn btn-info" type="submit" value="POTVRDIT">
                </div>
            </form>
        </div>

    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        // fucking vochcavka ignorace autocomplete
        $('#formular-heslo').on('input change', function () {
            if ($(this).val() != "") {
                $(this).prop('type', "password");
            } else {
                $(this).prop('type', "text");
            }
        });
    });
</script>