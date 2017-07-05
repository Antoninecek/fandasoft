<div>
    <h2>Chyba v programu:</h2>
    <table class="table">
        <tr class="warning">
            <th>Type</th>
            <td><?php echo get_class( $myException )?></td>
        </tr>
        <tr class="warning">
            <th>Message</th>
            <td><?php echo $myException->getMessage()?></td>
        </tr>
        <tr class="danger">
            <th>File</th>
            <td><?php echo $myException->getFile()?></td>
        </tr>
        <tr class="danger">
            <th>Line</th>
            <td><?php echo $myException->getLine()?></td>
        </tr>
        <tr>
            <th>Trace call</th>
            <td>
                <?php
                $separator = '<br/>';
                $formated = str_replace("\n", $separator, $myException->getTraceAsString());
                echo $formated;
                ?>
            </td>
        </tr>

        <tr>
            <th>Full Exception</th>
            <td><?php var_dump($myException);?></td>
        </tr>


    </table>
</div>