<?php

function merge($thumb, $frame){

    $frame = imagecreatefrompng($frame);
    $thumb = imagecreatefromjpeg($thumb);
    $width = imagesx( $frame );
    $height = imagesy( $frame );

    $img=imagecreatetruecolor( $width, $height );

    imagealphablending($img, true);

    $transparent = imagecolorallocatealpha( $img, 0, 0, 0, 127 );
    imagefill( $img, 0, 0, $transparent );
    imagecopyresampled($img,$thumb,0,0,0,0, 200, 200, imagesx( $thumb ), imagesy( $thumb ) );
    imagecopyresampled($img,$frame,0,0,0,0, $width,$height,$width,$height);
    imagealphablending($img, false);
    imagesavealpha($img,true);

    //-------в последствии заменить на return-------
    return $img;
    //-------в последствии заменить на return-------

    imagedestroy($img);
    exit;
}

$thumb = './test_photo.jpg';
$frame = './Bast_Sales.png';

header('Content-type: image/png');
imagepng( merge($thumb, $frame), NULL, 9 );


?>