<?php
define("HANDLERS_PATH", _DIR_ . "/handlers");


?>
<html>
    <body>
    <?php 
    include_once HANDLERS_PATH . "/mongodbChecker.handler.php";
    include_once HANDLERS_PATH . "/postgreChecker.handler.php";
    ?>
    </body>
</html>