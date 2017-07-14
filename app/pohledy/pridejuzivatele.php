<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 14.07.2017
 * Time: 1:13
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4 text-center">
            <h1>PRIDEJ UZIVATELE</h1>
            <form action="uzivatel/pridejuzivatele" method="post" autocomplete="off">
                <input id="oscislo" class="form-control" type="number" name="oscislo" placeholder="OSOBNI CISLO" required>
                <input class="form-control" type="text" name="jmeno" placeholder="JMENO" required>
                <input class="form-control" type="email" name="email" placeholder="EM@IL" required>
                <div class="form-group form-inline">
                    <label for="formular-heslo" id="label-heslo"></label>
                    <input id="formular-heslo" type="text" class="form-control" name="heslo" placeholder="HESLO"
                           required>
                    <p class="form-text text-muted">
                        Tve heslo se bude skladat z osobniho cisla + tebou vymysleneho retezce znaku.
                    </p>
                </div>
                <div class="text-center">
                    <input type="submit" class="btn btn-default" value="PRIDEJ">
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

        $('#oscislo').on('input', function(){
           $('#label-heslo').html($(this).val());
        });

    });
</script>