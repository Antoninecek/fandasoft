<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 13.07.2017
 * Time: 17:38
 */
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4">

        </div>
        <div class="col-sm-4 text-center">
            <h1>Zmena hesla</h1>
            <form action="uzivatel/zmenheslo" method="post">
                <input class="form-control" type="text" name="stare" placeholder="STARE HESLO" required>
                <input class="form-control" type="text" name="nove1" placeholder="NOVE HESLO" required>
                <input class="form-control" type="text" name="nove2" placeholder="ZNOVU NOVE HESLO" required>
                <input class="btn btn-default" type="submit" value="Potvrd zmenu">
            </form>
        </div>
        <div class="col-sm-4">

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        // fucking vochcavka ignorace autocomplete
        $('input').on('input change', function () {
            if ($(this).val() != "") {
                $(this).prop('type', "password");
            } else {
                $(this).prop('type', "text");
            }
        });

    });
</script>