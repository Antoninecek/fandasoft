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
            <form class="form-inline" action="zaznam/prehled" method="post">
                <input class="form-control" type="number" name="id" placeholder="EAN / ORA" required>
                <input class="btn btn-default" type="submit" value="HLEDEJ">
            </form>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-hover table-condensed">
                <thead>
                <th>ID</th>
                <th>IMEI 1</th>
                <th>IMEI 2</th>
                <th>KUSY</th>
                <th>JMENO</th>
                <th>TEXT</th>
                <th>TYP</th>
                <th>DATUM</th>
                </thead>
                <tbody>
                <?php
                if (!empty($zaznamy)) {
                    foreach ($zaznamy as $zaznam) {
                        ?>
                        <tr id="<?= $zaznam->getId() ?>">
                            <td><?php echo $zaznam->getId() ?></td>
                            <td class="prehled-imei1" data-id="<?= $zaznam->getId() ?>"
                                data-prijem="<?= $zaznam->getKusy() < 0 ? 0 : 1 ?>" data-imei="<?= $zaznam->getImei1() ?>"><?php echo $zaznam->getImei1() ?></td>
                            <td class="prehled-imei2"><?php echo $zaznam->getImei2() ?></td>
                            <td><?php echo $zaznam->getKusy() ?></td>
                            <td><?php echo $zaznam->getJmeno() ?></td>
                            <td><?php echo $zaznam->getText() ?></td>
                            <td><?php echo $zaznam->getTyp() ?></td>
                            <td><?php echo $zaznam->getDatum() ?></td>
                        </tr>
                        <?php
                    } ?>
                    <tr style="border-top: 3px solid black">
                        <td colspan="3">suma</td>
                        <td><?= $suma ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var imei1s = $('.prehled-imei1');
        //console.log(imei1s);
        var nevydana = []; // stack
        imei1s.each(function () {   // pro vsechna imei
            var toto = $(this);   // aktualni imei
            if ($(this).data('prijem') === 1) { // pokud je to prijem
                var index = nevydana.findIndex(x => x.data('imei') == toto.data('imei')); // najit index v nevydanych
                if (index > -1) { // pokud takovy index v poli je
                    nevydana.splice(index, 1); // vymaz ho
                }
                nevydana.push(toto); // samozrejme tam pushni aktualni prvek
            } else {
                var index = nevydana.findIndex(x => x.data('imei') == toto.data('imei')); // najit index v nevydanych
                if (index > -1) { // pokud takovy index v poli je
                    nevydana[index].parent('tr').addClass('prehled-zelene');
                    toto.parent('tr').addClass('prehled-zelene');
                    nevydana.splice(index, 1); // vymaz ho
                } else {
                    toto.parent('tr').addClass('prehled-cervene');
                }
            }
        });
        console.log(nevydana);
    })
</script>