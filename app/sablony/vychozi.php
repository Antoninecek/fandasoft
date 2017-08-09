<!DOCTYPE html>
<html lang="cs">
<head>
    <base href="<?= BASE_HREF ?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" sizes="32x32" href="<?= ROOT_DIR_FILES ?>favicon-32x32.png">

    <title>#FANDASOFT - <?= $titulek ?></title>

    <!-- Bootstrap core CSS -->
    <script src="<?= ROOT_DIR_FILES ?>jquery/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="<?= ROOT_DIR_FILES ?>bootstrap/css/bootstrap.min.css">
    <script src="<?= ROOT_DIR_FILES ?>bootstrap/js/bootstrap.min.js"></script>
    <!--  jsSHA256  -->
    <script type="text/javascript" src="<?= ROOT_DIR_FILES ?>jssha/src/sha256.js"></script>
    <script type="text/javascript" src="<?= ROOT_DIR_FILES ?>jssha/src/sha.js"></script>
    <!-- Custom styles for this template -->
    <link href="<?= ROOT_DIR_FILES ?>mycss.css" rel="stylesheet" type="text/css">

</head>

<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="row">
            <div class="navbar-header col-sm-4">
                <div class="container-fluid">
                    <div class="row text-center nav-logo-center" style="height: 100px;">
                        <div class="col-sm-6 navbar-brand-wrap" style="vertical-align: middle;">
                            <a href="home" class="navbar-brand"
                               style="float: none;">#FANDASOFT</a>
                        </div>
                        <div class="col-sm-6">
                            <a href="pobocka"
                               class="navbar-pobocka">
                                <div><?= !empty($_SESSION[SESSION_POBOCKA]) ? $_SESSION[SESSION_POBOCKA]->getMesto() . '<br>' . $_SESSION[SESSION_POBOCKA]->getNazev() : 'vyber pobocku' ?></div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-sm-4" style="margin-top:5px">
                <div class="row">
                    <ul class="nav navbar-nav hlavni-navigace">
                        <li>
                            <a href="zaznam/pridej">PRIDEJ</a>
                        </li>
                        <li>
                            <a href="zaznam/prehled">PREHLED</a>
                        </li>
                        <li>
                            <a href="zaznam/vystav">VYSTAV</a>
                        </li>
                        <li>
                            <a href="zaznam/zabal">ZABAL</a>
                        </li>
                        <li>
                            <a href="zaznam/vystavsap">PREVEDENI</a>
                        </li>
                        <li>
                            <a href="zaznam/prehledvystav">VYSTAVENI</a>
                        </li>

                        <li>
                            <a href="inventura" class="dis">INVENTURA</a>
                        </li>
<!--                        <li>-->
<!--                            <a href="zaznamy" class="dis">ZAZNAMY</a>-->
<!--                        </li>-->
                        <li>
                            <a href="uzivatel">UZIVATEL</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-2 text-center" style="padding-top: 20px;">
                <?php
                if (!empty($_SESSION['uzivatel'])) {
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <span style="color: lightgrey;">PRAVE JE PRIHLASEN</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <span
                                style="color: lightgrey; font-weight: bold;"><?= $_SESSION['uzivatel']->getJmeno() ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <span style="color: gray;"><a href="uzivatel/odhlaseni"
                                                          style="color: lightgrey; text-decoration: underline;">odhlas
                                    me</a></span>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="col-sm-2">
                <div class="nav navbar-nav navbar-right text-center" style="padding: 10px 20px;">
                    <div style="color: gray;">
                        Frantisek Jukl<br>
                        <span class="glyphicon glyphicon-copyright-mark"></span> 2016 - 2017<br>
                        <a href="mailto:frantisek.jukl@gmail.com" style="color: grey">frantisek.jukl@fandasoft.cz</a><br>
                        <span>+420 607 749 929</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<div id="content" class="container">
    <?php if (isset($upozorneni)) { ?>
        <div class="row">
            <div class="alert alert-<?= $upozorneni->getTyp() ?>">
                <div id="upozorneni-text"><strong><?= $upozorneni->getZprava() ?></strong></div>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <?php echo $content; ?>
    </div>
</div>
<footer class="footer">
    <div class="container-fluid">
        <a href="home" style="color: lightgrey;">#FANDASOFT verze 2.0</a>
    </div>
</footer>

<script type="text/javascript">
    $(document).ready(
        function(){
            $('.dis').on('click', function(e){
                e.preventDefault();
            })
        }
    )
</script>

<?php
if (empty($_SESSION[SESSION_POBOCKA])) {
    ?>
    <script>
        $(function () {
            $(".hlavni-navigace li a").addClass('dis');
            $(".hlavni-navigace li a").click(function (e) {
                e.preventDefault();
            });
        })
        ;
    </script>
    <?php
}
?>
</body>


</html>