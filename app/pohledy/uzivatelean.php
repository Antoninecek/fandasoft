<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 15.07.2017
 * Time: 10:07
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1>Ziskani hesla v podobe EAN13</h1>
            <p>Abys mohl/a vyuzivat k prihlaseni verzi hesla s EANem, potrebujes si zvolit heslo, ktere bude presne 12
                cislic, posledni cislice ti bude pridelena.</p>
            <form class="form-inline" action="uzivatel/ziskejEanHeslo" method="post" autocomplete="off">
                <input class="form-control" type="text" name="heslo1" pattern="[0-9]{12}" placeholder="HESLO" required>
                <input class="form-control" type="text" name="heslo2" pattern="[0-9]{12}" placeholder="POTVRD HESLO" required>
                <input type="hidden" name="sirka" value="2">
                <input type="hidden" name="vyska" value="30">
                <input class="btn btn-default" type="submit" value="ZMEN HESLO">
            </form>
        </div>
        <div class="col-sm-6" style="margin-top: 20px;">
            <div style="background-color: white; padding:50px 50px;">
                <h3>EAN a heslo</h3>
                <?= isset($eankod) ? $eankod : '' ?>
                <?= isset($eancislo) ? $eancislo : '' ?>
            </div>
        </div>
    </div>
</div>
