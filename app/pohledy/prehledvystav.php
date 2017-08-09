<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 29.07.2017
 * Time: 16:25
 */

foreach ($seznam as $s) {
    if (!in_array(trim($s->getKategorie()), $kategorie)) {
        $kategorie[] = trim($s->getKategorie());
    }
}
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
                    <th width="10%">ean</th>
                    <th width="10%">ora</th>
                    <th width="20%">model</th>
                    <th width="20%">popis</th>
                    <th width="10%">kategorie</th>
                    <th width="10%">kusy sklad</th>
                    <th width="10%">kusy vystavene</th>
                    <th width="5%"><abbr
                            title="0 - vystaveno, neprevedeno&#13;1 - vystaveno, prevedeno&#13;2 - nechci vystavovat">priznak
                        </abbr></th>
                    <th width="5%"></th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>
                        <select id="kategorie" title="a">
                            <option value="-1">vse</option>
                            <?php
                            foreach ($kategorie as $k) {
                                ?>
                                <option value="<?= $k ?>"><?= $k ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </th>
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
                        </select>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($seznam as $s) {

                    ?>
                    <tr
                        data-sklad="<?php if ($s->getZarkusy() == 0) {
                            echo 0;
                        } else if ($s->getZarkusy() > 0) {
                            echo 1;
                        } else {
                            echo 2;
                        } ?>"
                        data-kategorie="<?= trim($s->getKategorie()) ?>"
                        data-priznak='<?= $s->getPriznak() > -1 ? $s->getPriznak() : -2 ?>'
                        data-nevystavene='<?php if ($s->getNevystavkusy() == 0) {
                            echo 0;
                        } else if ($s->getNevystavkusy() > 0) {
                            echo 1;
                        } else {
                            echo 2;
                        } ?>'>
                        <td><?= $s->getEan() ?></td>
                        <td><?= empty($s->getZbozi()) ? $s->getOra() : $s->getZbozi() ?></td>
                        <td><?= $s->getModel() ?></td>
                        <td><?= $s->getPopis() ?></td>
                        <td><?= $s->getKategorie() ?></td>
                        <td><?= $s->getZarkusy() ?></td>
                        <td><?= $s->getNevystavkusy() != null ? $s->getNevystavkusy() : 0 ?></td>
                        <td class="priznak"><?= $s->getPriznak() ?></td>
                        <td>
                            <select title="a" class="zmenpriznak" data-zmenpriznak="<?= $s->getOra() ?>">
                                <option value="0" <?= $s->getPriznak() == 0 ? "selected" : ""?>>0</option>
                                <option value="1" <?= $s->getPriznak() == 1 ? "selected" : ""?>>1</option>
                                <option value="2" <?= $s->getPriznak() == 2 ? "selected" : ""?>>2</option>
                            </select>
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
// TODO nacpat vse do kategorii

        $('.zmenpriznak').on('change', function(){

            var ora = $(this).data('zmenpriznak');
            var priznak = $(this).find($(':selected')).val();
            var toto = $(this);
            $.ajax({
                url: "zaznam/zmenpriznakajax?ora="+ora+"&priznak="+priznak,
                success: function(result, xhr, status){
                    if(result == true){
                        toto.parent('td').prev().html(priznak);
                        toto.closest('tr').attr('data-priznak', priznak);
                        console.log(toto.closest('tr'));
                    } else{

                    }
                }
            })
        });

        $('#priznak, #nevystavene, #sklad, #kategorie').on('change', function () {
            var priz = $('#priznak').find($(':selected')).val();
            var nevys = $('#nevystavene').find($(':selected')).val();
            var skl = $('#sklad').find($(':selected')).val();
            var kat = $('#kategorie').find($(':selected')).val();

            var priznak;
            var nevystaveno;
            var sklad;
            var kategorie;

            if (kat == -1) {
                kategorie = false;
            } else {
                kategorie = '[data-kategorie="' + kat + '"]';
            }

            if (priz == -1) {
                priznak = '[data-priznak="-2"],[data-priznak="0"],[data-priznak="1"],[data-priznak="2"]';
            } else {
                priznak = '[data-priznak="' + priz + '"]';
            }

            if (skl == -1) {
                sklad = '[data-sklad="0"],[data-sklad="1"],[data-sklad="2"]';
            } else {
                sklad = '[data-sklad="' + skl + '"]';
            }

            if (nevys == -1) {
                nevystaveno = '[data-nevystavene="0"],[data-nevystavene="1"],[data-nevystavene="2"]';
            } else {
                nevystaveno = '[data-nevystavene="' + nevys + '"]';
            }

            if (kategorie) {
                $("tbody tr").hide().filter(priznak).filter(nevystaveno).filter(sklad).filter(kategorie).show();
            } else {
                $("tbody tr").hide().filter(priznak).filter(nevystaveno).filter(sklad).show();
            }

        });


    })
</script>
