<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <h1>
                PREHLED
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table-hover table-condensed">
                <thead>
                <th>ID</th>
                <th>IMEI 1</th>
                <th>IMEI 2</th>
                <th>KUSY</th>
                <th>JMENO</th>
                <th>TEXT</th>
                <th>DATUM</th>
                </thead>
                <tbody>
                <?php
                foreach ($zaznamy as $zaznam) {
                    ?>
                    <tr>
                        <td><?php echo $zaznam->getId() ?></td>
                        <td><?php echo $zaznam->getImei1() ?></td>
                        <td><?php echo $zaznam->getImei2() ?></td>
                        <td><?php echo $zaznam->getKusy() ?></td>
                        <td><?php echo $zaznam->getJmeno() ?></td>
                        <td><?php echo $zaznam->getText() ?></td>
                        <td><?php echo $zaznam->getDatum() ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>