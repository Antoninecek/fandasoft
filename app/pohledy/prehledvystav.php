<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 29.07.2017
 * Time: 16:25
 */
?>

<h1>
    Vystaveni
</h1>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ean</th>
                    <th>ora</th>
                    <th>model</th>
                    <th>popis</th>
                    <th>kategorie</th>
                    <th>kusy sklad</th>
                    <th>kusy vystavene</th>
                    <th>priznak</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>
                        <select id="sklad" title="a">
                            <option value="-1">vse</option>
                            <option value="0">0</option>
                            <option value="1">0+</option>
                            <option value="2">0-</option>
                        </select>
                    </th>
                    <th>
                        <select id="nevystavene" title="a">
                            <option value="-1">vse</option>
                            <option value="0">0</option>
                            <option value="1">0+</option>
                            <option value="2">0-</option>
                        </select>
                    </th>
                    <th>
                        <select id="priznak" title="a">
                            <option value="-1">vse</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
<!--                          <!-- -2 bez prirazeni -->
                        </select>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($seznam as $s) {
                    ?>
                    <tr data-sklad="<?php if($s->getZarkusy() == 0){echo 0;} else if($s->getZarkusy() > 0){echo 1;} else {echo 2;} ?>" data-priznak='<?= $s->getPriznak() > -1 ? $s->getPriznak() : -2 ?>' data-nevystavene='<?php if($s->getNevystavkusy() == 0){echo 0;} else if($s->getNevystavkusy() > 0){echo 1;} else {echo 2;} ?>' >
                        <td><?= $s->getEan() ?></td>
                        <td><?= empty($s->getZbozi()) ? $s->getOra() : $s->getZbozi() ?></td>
                        <td><?= $s->getModel() ?></td>
                        <td><?= $s->getPopis() ?></td>
                        <td><?= "kategorie" ?></td>
                        <td><?= $s->getZarkusy() ?></td>
                        <td><?= $s->getNevystavkusy() ?></td>
                        <td><abbr
                                title="0 - vystaveno, prevedeno&#13;1 - vystaveno, neprevedeno&#13;2 - nechci vystavovat"><?= $s->getPriznak() ?></abbr>
                        </td>
                    </tr>
                    <?php
//                    TODO nejak poskladat - do sapu vlozit kategorie, do prehledu sumu, co se zapornym skladem?
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {


        $('#priznak, #nevystavene, #sklad').on('change', function () {
            var priz = $('#priznak').find($(':selected')).val();
            var nevys = $('#nevystavene').find($(':selected')).val();
            var skl = $('#sklad').find($(':selected')).val();

            var priznak;
            var nevystaveno;
            var sklad;

            if(priz == -1){
                priznak = '[data-priznak="-2"],[data-priznak="0"],[data-priznak="1"],[data-priznak="2"]';
            } else {
                priznak = '[data-priznak="' + priz + '"]';
            }

            if(skl == -1){
                sklad = '[data-sklad="0"],[data-sklad="1"],[data-sklad="2"]';
            } else {
                sklad = '[data-sklad="' + skl + '"]';
            }

            if(nevys == -1){
                nevystaveno = '[data-nevystavene="0"],[data-nevystavene="1"],[data-nevystavene="2"]';
            } else {
                nevystaveno = '[data-nevystavene="' + nevys + '"]';
            }

            $("tbody tr").hide().filter(priznak).filter(nevystaveno).filter(sklad).show();
        });


    })
</script>
