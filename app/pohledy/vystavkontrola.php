<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 29.07.2017
 * Time: 11:22
 */
?>

<h1>Vystaveni</h1>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <a id="zdar" class="btn btn-info col-sm-offset-9" href="zaznam/vystavSap/?vystavtoken=<?= $vystavtoken ?>"
               tabindex="-1">PREVEST DO
                ZDARU</a>
            <table class="table table-responsive">
                <thead>
                <th>ID</th>
                <th>EAN</th>
                <th>ZBOZI</th>
                <th>MODEL</th>
                <th>POPIS</th>
                <th>KUSY</th>
                <th>DATUM</th>
                </thead>
                <tbody>
                <?php
                if (empty($seznam)) {
                    ?>
                    <tr>
                        <td>ZADNE KUSY K PREVEDENI</td>
                    </tr>
                    <?php
                }
                foreach ($seznam as $s) {
                    ?>
                    <tr>
                        <td><?= $s->getId() ?></td>
                        <td><?= $s->getEan() ?></td>
                        <td><?= empty($s->getOra()) ? $s->getZbozi() : $s->getOra() ?></td>
                        <td><?= $s->getModel() ?></td>
                        <td><?= $s->getPopis() ?></td>
                        <td><?= $s->getKusy() ?></td>
                        <td><?= $s->getDatum() ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    if (!empty($seznamZbozi) || !empty($seznamEan)) {
        ?>
        <h1>PREVEDENO</h1>
        <div class="row">
            <div class="col-sm-12">
                <table class="table">
                    <thead>
                    <th>ora/ean</th>
                    <th>kusy</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($seznamZbozi as $z) {
                        ?>
                        <tr>
                            <td><?= empty($z->getOra()) ? $z->getZbozi() : $z->getOra() ?></td>
                            <td class="unselectable"><?= $z->getKusy() ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    ?>
</div>
