<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 07.07.2017
 * Time: 0:31
 */
?>
<div class="text-center">
    <h1>PRIHLASENI</h1>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <form action="uzivatel/prihlas" method="post" autocomplete="off">
                <!--JEN PRO VOCHCAVKU AUTOCOMPLETE-->
                <input type="password" style="display: none;">
                <input class="form-control" type="text" name="jmeno" placeholder="OSOBNI CISLO">
                <input id="formular-heslo" type="text" class="form-control" name="heslo" placeholder="HESLO">
                <div class="text-center">
                    <input type="submit" class="btn btn-default" value="PRIHLAS">
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        // fucking vochcavka ignorace autocomplete
        $('#formular-heslo').on('input change', function () {
            if ($(this).val() != "") {
                $(this).prop('type', "password");
            } else {
                $(this).prop('type', "text");
            }
        });

    });
</script>