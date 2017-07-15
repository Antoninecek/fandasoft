<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 15.07.2017
 * Time: 14:27
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
                        <td><button class="btn btn-info">Povys</button></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>