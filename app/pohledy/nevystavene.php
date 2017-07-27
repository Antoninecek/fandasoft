<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 20.07.2017
 * Time: 13:15
 */
?>

<h1>Vystaveni</h1>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
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
                    <td>
                        <button class="btn btn-info">zmiz</button>
                        <form class="form-inline" method="post" action="zaznam/vystav" style="display: none;" autocomplete="off">
                            <input class="formular-heslo form-control" type="text" name="heslo"
                                   placeholder="HESLO" required>
                            <input type="number" name="id" value="<?= $s->getId() ?>" hidden>
                            <input class="btn btn-info" type="submit" value="potvrd">
                        </form>
                    </td>
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

        // fucking vochcavka ignorace autocomplete
        $('.formular-heslo').on('input change', function () {
            if ($(this).val() != "") {
                $(this).prop('type', "password");
            } else {
                $(this).prop('type', "text");
            }
        });

        $('button').on('click', function () {
            $('form').hide();
            $('button').show();
            $(this).hide();
            $(this).siblings('form').show();

        });
    })
</script>