<?php

use WfBL\Plugin;
use WfBL\LogFile;

$logFile = new LogFile();


?>

<table class="form-table" role="presentation">
    <tbody>
        <tr>
            <th scope="row"><?php echo __('Log file', Plugin::TEXTDOMAIN) ?></th>
            <td><?php echo $logFile->getFilename(); ?></td>
        </tr>
    </tbody>
</table>
