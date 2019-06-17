<?php
$src_image = "images/test.png";
$back_image = "images/mask.png";
$result_image = "images/result.png";
replaceWhites($src_image, $back_image, $result_image);

function replaceWhites($src_image, $back_image, $result_image) {
	
	//------Transparent the Image-----------
	$color = '#ffffff';
	$alpha = 0;
	$fuzz = 0.2;
	$imagick = new \Imagick(realpath($src_image));
	$imagick->setimageformat('png');
	$imagick->transparentPaintImage( $color, $alpha, $fuzz * \Imagick::getQuantum(), false);
	$imagick->despeckleimage();


	//-----Merge two layout-----------
	$imagick_back = new \Imagick(realpath($back_image));
	$imagick_back->addImage($imagick);
	$imagick_back->setImageFormat('png');
	$result = $imagick_back->mergeImageLayers(Imagick::LAYERMETHOD_MERGE);


	//----------Save Image-------------------
	file_put_contents ($result_image, $result);
}

?>