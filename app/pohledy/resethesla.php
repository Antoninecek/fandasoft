<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 17.07.2017
 * Time: 12:45
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <table class="table-responsive table">
                <thead>
                <th>id</th>
                <th>oscislo</th>
                <th>jmeno</th>
                <th>email</th>
                <th>admin</th>
                <th>datum</th>
                </thead>
                <tbody>
                <?php
                foreach ($seznam as $zaznam) {
                    ?>
                    <tr>
                        <td><?= $zaznam->getId() ?></td>
                        <td><?= $zaznam->getOscislo() ?></td>
                        <td><?= $zaznam->getJmeno() ?></td>
                        <td><?= $zaznam->getEmail() ?></td>
                        <td><?= $zaznam->getAdmin() ?></td>
                        <td><?= $zaznam->getDatum() ?></td>
                        <td>
                            <button class="btn btn-info">RESETUJ</button>
                            <form class="form-inline" method="post" action="uzivatel/resetuj/" style="display: none;" autocomplete="off">
                                <input class="formular-heslo form-control" type="text" name="heslo"
                                       placeholder="TVE HESLO" required>
                                <input type="number" name="id" value="<?= $zaznam->getId() ?>" hidden>
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
