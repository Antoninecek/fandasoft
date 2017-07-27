<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 26.07.2017
 * Time: 19:40
 */
?>

<h1>VYSTAV</h1>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <form action="zaznam/vystav/" method="post">
                <input type="number" name="vystavZaznam" class="form-control" placeholder="ORA / EAN">
                <input type="submit" class="btn btn-default">
            </form>
        </div>
    </div>
    <?php if (isset($texty)) {
        echo $texty;
    }
    ?>
    <div class="row">
        <div class="col-sm-12">
            <a id="zdar" class="btn btn-info col-sm-offset-9" href="zaznam/vystavSap/" tabindex="-1">PREVEST DO
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
                foreach ($seznam as $s) {
                    ?>
                    <tr>
                        <td><?= $s->getId() ?></td>
                        <td><?= $s->getEan() ?></td>
                        <td><?= $s->getZbozi() ?></td>
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
</div>

