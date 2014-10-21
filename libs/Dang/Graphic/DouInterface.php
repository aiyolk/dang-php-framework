<?php

namespace Dang\Graphic;

interface DouInterface
{
    public function rotate($newFile, $angle);

    public function flip($newFile, $mode);

    public function watermarkImage($overlayFile, $position);

    public function watermarkText($overlayText, $position, 
                            $param = array('color' => 'FFF', 'rotation' => 0, 'opacity' => 50, 'size' => null));
    
    public function _resize($newFile, $newWidth, $newHeight);

    public function _crop($newFile, $resizeWidth, $resizeHeight, 
                                $newWidth, $newHeight, $cropX, $cropY, $resize = true);    
}
