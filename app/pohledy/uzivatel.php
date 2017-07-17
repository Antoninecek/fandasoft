<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 07.07.2017
 * Time: 23:31
 */
?>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li><a href="uzivatel/zmenheslo">zmena hesla</a></li>
            <li><a href="uzivatel/odhlaseni">odhlaseni</a></li>
            <?php
            if ($_SESSION['uzivatel']->getAdmin()) {
                ?>
                <li><a href="uzivatel/pridejuzivatele">pridej uzivatele</a></li>
                <li><a href="uzivatel/povys">zmen prava uzivatele</a></li>
                <li><a href="uzivatel/resetuj">resetuj heslo uzivatele</a></li>
                <?php
            }
            ?>
        </ul>
    </div>
</nav>
<h1>Uzivatel</h1>
<table class="table table-responsive">
    <tbody>
    <tr>
        <td>Uzivatelske jmeno</td>
        <td><?= $_SESSION['uzivatel']->getJmeno() ?></td>
    </tr>
    <tr>
        <td>Administratorske opravneni</td>
        <td><?= $_SESSION['uzivatel']->getAdmin() ? "ano" : "ne" ?></td>
    </tr>
    <tr>
        <td>Heslo EAN13</td>
        <td id="js-button">
            <button id="button-zobraz-ean" class="btn btn-info">ZISKEJ</button>
        </td>
        <td id="js-potvrzeni" style="display: none;">
            <form class="form-inline" method="post" action="uzivatel/ziskejEanHeslo/">
                <input id="formular-heslo" class="form-control" type="text" name="heslo" placeholder="HESLO" required>
                <input class="btn btn-info" type="submit" value="potvrd">
            </form>
        </td>
    </tr>


    </tbody>
</table>

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

        $('#button-zobraz-ean').on('click', function () {
            $('#js-button').hide();
            $('#js-potvrzeni').show();
        });
    })
</script> 