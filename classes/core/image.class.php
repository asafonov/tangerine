<?php
/**
 * The class provides the number of methods to process images
 *
 * @example ../examples/image.example.php
 * 
 * @author Alexander Safonov <me@asafonov.org>
 */
class image {
    const CROP = 1;
    const FIT = 2;
    /**
     * @var string $resize_type - the type of the resize (crop or fit)
     */
    private $resize_type;
    /**
     * @var int $quality - quality of the resulting image
     */
    private $quality = 90;
    /**
     * @var string $source - the path to the source image
     */
    private $source;
    /**
     * @var string $destination - the path to the target image
     */
    private $destination;
    /**
     * @var string $type - image type (jpeg, png...)
     */
    private $type;

    public function __construct($source='', $type='') {
        $this->source = $source;
        $this->type = $type;
    }

    public function __set($name, $value) {
        if(property_exists($this, $name)){
            $this->$name = $value;
        } else {
            throw new OutOfBoundsException("Incorrect property name: ".$name);
        }        
    }

    public function __get($name) {
        if (property_exists($this, $name)) {
	        return $this->$name;
        } else {
            throw new OutOfBoundsException("Incorrect property name: ".$name);
        }
    }

    /**
     * Private method to determine the image type using file extension
     */
    private function _setTypeByExt() {
        $spam = explode('.', $this->source);
        $ext = strtolower($spam[count($spam)-1]);
        switch($ext) {
	    case 'png':
	       $this->type='png';
            break;
        case 'gif':
	        $this->type='gif';
	        break;
        default:
	        $this->type='jpeg';
        }
    }

    /**
     * Public method of getting php image object
     * @return $im - image object
     */
    public function getImage() {
        return $this->_getImage();
    }

    /**
     * Private method of getting php image object
     * @return $im - image object
     */
    private function _getImage() {
        if (!$this->type) $this->_setTypeByExt();
        if ($this->type=='jpeg'||$this->type=='jpg') {
	        $im = imagecreatefromjpeg($this->source);
	    } elseif ($this->type=='png') {
  	        $im = imagecreatefrompng($this->source);
        } elseif ($this->type=='gif') {
  	        $im = imagecreatefromgif($this->source);
        } else {
            throw new OutOfBoundsException("Incorrect image type: ".$this->type);
        }
        return $im;
    }

    /**
     * Method for removing image colors
     */
    public function toGrayScale() {
        $im = $this->_getImage();
        imagefilter($im, IMG_FILTER_GRAYSCALE);
        $this->_saveImage($im);
    }

    /**
     * The method allows you to resize the image with crop
     * The target image will be exast size as it set in parameters
     * If image proportions do not allowing it, the image will be cropped
     * @param int $width
     * @param int $height
     */
    public function resizeCrop($width, $height) {
        $im = $this->_getImage();
        if (imagesx($im)<=$width&&imagesy($im)<=$height) {
            $this->_saveImage($im);
            imagedestroy($im); 
            return false;
        }
        $OriginalWidthToHeight = imagesx($im)/imagesy($im);
        $WantedWidthToHeight = $width/$height;
        if ($OriginalWidthToHeight>$WantedWidthToHeight) {
        /*
         * OriginalWidthToHeight>WantedWidthToHeight means that we need to height for main parameter of image scaling.
         * If so, scaled image will have wanted height but it will have larger width - we'll cut it
         */
            $k = $height/imagesy($im);
        } else {
        /*
         * Otherwise we'll cut the image by height
         */
            $k = $width/imagesx($im);
        }

        $w=intval(imagesx($im)*$k);
        $h=intval(imagesy($im)*$k);

        $im1=imagecreatetruecolor($w,$h);
        imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));

        /*
         * We are ready to crop the image now
         * if OriginalWidthToHeight>WantedWidthToHeight we cut image width
         * XPosition must be calculated to center image
         * otherwise me have to calculate YPosition
         */
        if($OriginalWidthToHeight>$WantedWidthToHeight) {
            $YPosition = 0;
	    $XPosition = intval(($w - $width)/2);
        } else {
            $XPosition = 0;
	    $YPosition = intval(($h - $height)/2);
        }
        $im2 = imagecreatetruecolor($width, $height);
        imagecopy($im2, $im1, 0,0, $XPosition, $YPosition ,$width, $height);

        $this->_saveImage($im2);

        imagedestroy($im);
        imagedestroy($im1);
        imagedestroy($im2);
    }


    /**
     * The method allows you to resize the image with fitting
     * The proportions of the image will be saved but it's size will not exeed the size set in parameters
     * @param int $width
     * @param int $height
     */
    public function resizeFit($width, $height) {
        $im = $this->_getImage();
        if (imagesx($im)<=$width&&imagesy($im)<=$height) {
            $this->_saveImage($im);
            imagedestroy($im); 
            return false;
        }
        $k1=$width/imagesx($im);
        $k2=$height/imagesy($im);
        $k=$k1>$k2?$k2:$k1;
        if ($k==0) $k=$k1==0?$k2:$k1;
        $w=intval(imagesx($im)*$k);
        $h=intval(imagesy($im)*$k);

        $im1=imagecreatetruecolor($w,$h);
        imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));

        $this->_saveImage($im1);

        imagedestroy($im);
        imagedestroy($im1);
    }

    /**
     * Image resize by width. The height of the image will be calculated with the proportions saving
     * @param int $width
     */
    public function resizeByWidth($width) {
        $this->resizeFit($width, 0);
    }

    /**
     * Image resize by height. The width of the image will be calculated with the proportions saving
     * @param int $height
     */
    public function resizeByHeight($height) {
        $this->resizeFit(0, $height);
    }
    
    /**
     * Private method for saving image to file
     * @param image $im - ссылка на объект изображения
     */
    private function _saveImage($im) {
        if (!$this->destination) $this->destination = $this->source;
        if ($this->type=='png') {
            imagealphablending($im, false);
            imagesavealpha($im, true);      
            imagepng($im,$this->destination);
        } elseif($this->type=='gif') {
            imagealphablending($im, false);
            imagesavealpha($im, true);      
            imagepng($im,$this->destination);
        } else imagejpeg($im,$this->destination,$this->quality);
    }

    /**
     * Method for image resizing
     * @param int $width
     * @param int $height
     * @param string $resize_type
     */
    public function resize($width=0, $height=0, $resize_type=self::FIT) {
        $this->resize_type = $resize_type;
        if ($this->resize_type==self::CROP) {
	        $this->resizeCrop($width, $height);
        } elseif($this->resize_type==self::FIT) {
	        $this->resizeFit($width, $height); 
        } else {
	        throw new OutOfBoundsException("Incorrect resize type: ".$this->resize_type);
        }
    }

    /**
     * This method allows to get the average color of the image
     * @return int - the average color of the image
     */
    public function getAverageColor() {
        $im = $this->_getImage();
        $width = imagesx($im);
        $height = imagesy($im);
        $ret = 0;
        $total_points = $width*$height;
        for ($i=0; $i<$width; $i++) {
            for ($j=0; $j<$height; $j++) {
                $ret += imagecolorat($im, $i, $j)/$total_points;
            }
        }
        return $ret;
    }

    /**
     * The method returns image as an array of its colors
     * @return array
     */
    public function toArray() {
        $im = $this->_getImage();
        $width = imagesx($im);
        $height = imagesy($im);
        $ret = array();
        for ($i=0; $i<$width; $i++) {
            $ret[$i] = array();
            for ($j=0; $j<$height; $j++) {
                $ret[$i][$j] = imagecolorat($im, $i, $j);
            }
        }
        return $ret;
    }
}

?>
