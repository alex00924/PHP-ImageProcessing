<?php
$src_image = "images/test.png";
$back_image = "images/mask.png";
$result_image = "images/result.png";
//replaceWhites($src_image, $back_image, $result_image);

$t = 15;

$imagick = new \Imagick(realpath($src_image));
$imagick->transparentPaintImage( "rgb(227, 198, 166)", 1, 0 * \Imagick::getQuantum(), false);

$iterator=$imagick->getPixelIterator();
foreach ($iterator as $row=>$pixels) {
    foreach ( $pixels as $column => $pixel ){
        $un_color=$pixel->getColor(); //unnormalized color
        $nor_color=$pixel->getColor(true); //normalized color
		$mean_color = ( $un_color['r'] + $un_color['b'] + $un_color['g'] ) / 3;
		if( $mean_color > 150 &&
			abs($un_color['r'] - $mean_color) < $t &&
			abs($un_color['g'] - $mean_color) < $t &&
			abs($un_color['b'] - $mean_color) < $t)
			{
				$alpha = min((255 - $mean_color)/80,0.9);
				$pixel->setColor('rgba('.$un_color['r'].','.$un_color['g'].','.$un_color['b'].','.$alpha.')');	
//				$pixel->setColor('rgba(255,0,0, ' . $alpha . ')');
			}
    }
	$iterator->syncIterator();
}

	$imagick_back = new \Imagick(realpath($back_image));
	$imagick_back->addImage($imagick);
	$imagick_back->setImageFormat('png');
	$result = $imagick_back->mergeImageLayers(Imagick::LAYERMETHOD_MERGE);


	//----------Save Image-------------------
	file_put_contents ($result_image, $result);
	
//file_put_contents ($result_image, $imagick);




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