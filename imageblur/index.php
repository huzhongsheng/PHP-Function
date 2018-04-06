<?php
require_once 'imageBlur.php';
$image_blur = new image_blur();
$i = $image_blur->gaussian_blur("./1.jpg",null,'4.jpg',4);
echo $i;
?>