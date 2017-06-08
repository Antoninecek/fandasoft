<div class="container-fluid">
    <h1>DETAIL</h1>
</div>
<div class="container-fluid nav-margin">
    <a class="btn btn-primary" id="stav" href="zaznam/stav/?id=<?= $zaznam->getId() ?>">Zmenit stav</a>
    <a id="uprava" class="btn btn-default">Upravit</a>
    <a id="smazani" class="btn btn-danger" href="zaznam/smaz/?id=<?= $zaznam->getId() ?>">Smazat</a>
</div>
<div class="container-fluid content-margin">
    <form action="zaznam/uprava/?id=<?=$zaznam->getId()?>" method="POST">
        <table class="table table-bordered table-detail-zaznam">
            <tbody>
            <tr>
                <td width="30%">ID:
                    <?= $zaznam->getId() ?>
                    <span style="float: right"><?= $zaznam->getSplnen() == 0 ? "NESPLNEN" : "SPLNEN" ?></span>
                </td>
                <td rowspan="4">
                    <div class="long-text">
                    <span data-zadavani="false"><?= $zaznam->getText() ?></span>
                    <span style="display: none" data-zadavani="true"><textarea class="form-control" name="text" cols="120" rows="10" required><?= $zaznam->getText() ?></textarea></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <span data-zadavani="false">Nazev: <?= $zaznam->getNazev() ?></span><span style="display: none"
                                                                                            data-zadavani="true">
                        <textarea class="form-control" name="nazev" cols="35" rows="4" required><?= $zaznam->getNazev() ?></textarea></span>
                </td>
            </tr>
            <tr>
                <td>Uzivatel: <?= $_SESSION['uzivatel']->getJmeno() ?>
                    (<?= $_SESSION['uzivatel']->getSlovniOpravneni() ?>)
                </td>
            </tr>
            <tr>
                <td>
                    Vytvoren: <?= $zaznam->getVytvoren() ?>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="navUkol" data-zadavani="true" style="display: none; margin-top: 10px;">
            <button class="btn btn-default" type="submit">UPRAV</button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#uprava').click(function () {
            $("[data-zadavani='false']").toggle();
            $("[data-zadavani='true']").toggle();
        });
    });
</script>