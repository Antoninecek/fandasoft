<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 05.08.2017
 * Time: 9:58
 */
?>
<form action="zaloha/vlozsap" method="post" enctype="multipart/form-data">
    <h4>Nahraj soubor <code style="font-size: small;">sap.csv</code></h4>
    <input style="cursor:pointer" type="file" name="fileToUpload" id="fileToUpload" class="btn btn-default">
    <input type="submit" value="Posli a zahaj synchronizaci" name="submit" class="btn btn-default"
           style="margin-top: 20px;">
</form>
