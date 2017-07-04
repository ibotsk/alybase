<?php
/* header("Content-Type: application/vnd.ms-word");//
  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past - so must always re-read
 * 
 */

header("Expires: Mon, 26 Jul 2020 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: application/rtf; charset=UTF-8");
header("content-disposition: attachment;filename=alybase_export.rtf"); //this will be the name of the file the user downloads
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
    </head>
    <body>
        <?php echo $content_for_layout; ?>
    </body>
</html>