<?php
// Setting debug mode. You should delete this string on the production website
define('DEBUG_MODE', true);

require_once('autoload.php');
$pageController = new pageController();
echo $pageController->run();

?>
