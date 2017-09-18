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
                                data-prijem="<?= $zaznam->getKusy() < 0 ? 0 : 1 ?>"><?php echo $zaznam->getImei1() ?></td>
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
        console.log(imei1s);
        var nevydana = [];
        imei1s.each(function () {
            if ($(this).data('prijem') === 1) {
                var toto = $(this).val();
                nevydana.each(function (_imei) {
                    var imei = _imei;
                    if ($(this).val() === imei) {
                        
                    }
                });
                if ($(this).val()) {

                }
            }
        })
    })
</script>