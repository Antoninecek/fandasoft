<h3>AKTUALNI POBOCKA: <?php echo isset($_SESSION[SESSION_POBOCKA]) ? $_SESSION[SESSION_POBOCKA]->getIdPobocka() . ": " . $_SESSION[SESSION_POBOCKA]->getMesto() . " - " . $_SESSION[SESSION_POBOCKA]->getNazev() : "Neni vybrana pobocka" ?></h3>
<br>
<form class="form-group" action="pobocka/nastav" method="POST" autocomplete="off">
    <select name="pobockyJmeno">
        <?php
        foreach ($pobockyList as $a) {
            ?>

            <option value="<?= $a->getId() ?>"><?= $a->getIdPobocka() . ": " . $a->getMesto() . " - " . $a->getNazev() ?></option>
            <?php
        }
        ?>

    </select>


    <input id="pobocky-heslo" name="pobockyHeslo" type="text" placeholder="HESLO">
    <input type="submit" class="btn btn-default" value="NASTAV">
</form>

<br>

<a href="pobocka/zrus" class="btn btn-danger">zrus aktualni pobocku</a>

<script type="text/javascript">
    // fucking vochcavka ignorace autocomplete
    $('#pobocky-heslo').on('input', function () {
        if ($(this).val() != "") {
            $(this).prop('type', "password");
        } else {
            $(this).prop('type', "text");
        }
    });
</script>