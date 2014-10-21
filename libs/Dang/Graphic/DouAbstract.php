<?php

namespace Dang\Graphic;

use Dang\Graphic\DouInterface;

abstract class DouAbstract implements DouInterface
{
    /**
     * Watermark positions
     * @since 2.0.4
     */
    const POS_TOP_LEFT      = 'top_left';
    const POS_TOP_RIGHT     = 'top_right';
    const POS_BOTTOM_LEFT   = 'bottom_left';
    const POS_BOTTOM_RIGHT  = 'bottom_right';
    const POS_MIDDLE_CENTER = 'middle_center';
    
    const FLIP_VERTICAL   = 'vertical';
    const FLIP_HORIZONTAL = 'horizontal';
    
    /**
     * The file name
     * @var string
     */
    protected $_file;

    /**
     * File type: gif, jpg, jpeg, png
     * @var string
     */
    protected $_fileType;

    /**
     * Width of image
     * @var int
     */
    protected $_width;

    /**
     * Height of image
     * @var int
     */
    protected $_height;
    
    /**
     * The font used for creating watermark
     * @var string
     */
    protected $_watermarkFont;
    
    /**
     * @param string $file
     */
    public function setSourceFile($file) 
    {
        $this->_file = $file;

        /**
         * Get size of image
         */
        $info = getimagesize($file);

        $this->_width  = $info[0];
        $this->_height = $info[1];

        $this->_fileType = \Dang\Utility::getFileExtension($file);
        
        return $this;
    }
    
    /**
     * @param string $font
     */
    public function setWatermarkFont($font)
    {
        $this->_watermarkFont = $font;
        return $this;
    }
    
    /**
     * Get image width
     * 
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }
    
    /**
     * Get image height
     * 
     * @return int
     */
    public function getHeight()
    {
        return $this->_height;
    }

    public function resizeLimit($newFile, $newWidth, $newHeight) 
    {
        \Dang\Utility::prepareDir(dirname($newFile));
                
        $percent   = ($this->_width > $newWidth) ? (($newWidth * 100) / $this->_width) : 100;
        $newWidth  = ($this->_width * $percent) / 100;
        $newHeight = ($this->_height * $percent) / 100;
        $this->_resize($newFile, $newWidth, $newHeight);
    }

    public function resize($newFile, $targetWidth, $targetHeight) 
    {
        \Dang\Utility::prepareDir(dirname($newFile));
        
        if(($targetWidth && $this->_width > $targetWidth) || ($targetHeight && $this->_height > $targetHeight)){
            $resizeWidth = false;
            if($targetWidth && $this->_width > $targetWidth){
                $widthRatio = $targetWidth/$this->_width;
                $resizeWidth = true;
            }
            $resizeHeight = false;
            if($targetHeight && $this->_height > $targetHeight){
                $heightRatio = $targetHeight/$this->_height;
                $resizeHeight = true;
            }
            if($resizeWidth && $resizeHeight){
                if($widthRatio < $heightRatio){
                    $ratio = $widthRatio;
                }else{
                    $ratio = $heightRatio;
                }
            }elseif($resizeWidth){
                $ratio = $widthRatio;
            }elseif($resizeHeight){
                $ratio = $heightRatio;
            }

            $newWidth  = $this->_width * $ratio;
            $newHeight = $this->_height * $ratio;
        }else{
            $newWidth  = $this->_width;
            $newHeight = $this->_height;
        }

        $this->_resize($newFile, $newWidth, $newHeight);
    }
    
    public function crop($newFile, $newWidth, $newHeight, $resize = true, $cropX = null, $cropY = null) 
    {
        \Dang\Utility::prepareDir(dirname($newFile));
        
        /**
         * Maintain ratio if image is smaller than resize
         */
        $percent = ($this->_width > $newWidth) ? ($newWidth * 100) / ($this->_width) : 100;

        /**
         * Resize to one side to newWidth or newHeight
         */
        $percentWidght       = ($newWidth * 100) / $this->_width;
        $percentHeight       = ($newHeight * 100) / $this->_height;
        $percent = ($percentWidght > $percentHeight) ? $percentWidght : $percentHeight;
        if($percentWidght > $percentHeight){
            $resizeWidth  = $newWidth;
            $resizeHeight = ($this->_height * $percent) / 100;
        } else {
            $resizeHeight = $newHeight;
            $resizeWidth  = ($this->_width * $percent) / 100;
        }

        $cropX = (null == $cropX) ? ($resizeWidth - $newWidth) / 2 : $cropX;
        $cropY = (null == $cropY) ? ($resizeHeight - $newHeight) / 2 : $cropY;

        $this->_crop($newFile, $resizeWidth, $resizeHeight, $newWidth, $newHeight, $cropX, $cropY, $resize);
    }    


}
