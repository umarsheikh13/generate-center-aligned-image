<?php
/**
 * Generate Center Aligned Image
 *
 * This class allows you to generate an image using an existing image
 * with text center aligned text horizontally and vertically.
 *
 * @version 0.1.0
 * @author Umar Sheikh <hello@umarsheikh.co.uk>
 */

namespace UmarSheikh\CenteredTextImage;

class CenteredTextImage
{

    /**
     * The configuration variables
     */
    public $text;
    public $filename;
    public $font;
    public $font_size;
    public $color;
    public $angle;
    public $wrap_size;
    public $line_height;

    /**
     * Sets all the variables upon class instantiation
     *
     * @param str $text The text you want to write on the image
     * @param str $filename Path to JPEG
     * @param str $font Path to your truetype font
     * @param int $font_size Pixel size (GD1) or point size (GD2)
     * @param array $color An array of 3 values representing RGB (0-255)
     * @param int $wrap_size The space around the text in pixels
     * @param int $line_height The space between the lines
     * @param int $offset_y Offset the text's vertical alignment in pixels
     *
     * @return void
     */
    public function __construct($text, $filename, $font, $font_size, $color, $wrap_size, $line_height)
    {
        $this->text            = $text;
        $this->filename        = $filename;
        $this->font            = $font;
        $this->font_size       = $font_size;
        $this->color           = $color;
        $this->wrap_size       = $wrap_size;
        $this->line_height     = $line_height;
    }

    /**
     * Generate multiple lines from the given text
     *
     * @param int $width The width of the bounding box
     *
     * @return array The text separated into multiple lines
     */
    protected function lines($width)
    {
        $lines = '';
        $arr = explode(' ', $this->text);
        foreach ($arr as $word) {
            $teststring = $lines . ' ' . $word;
            $testbox = imagettfbbox($this->font_size, 0, $this->font, $teststring);
            if ($testbox[2] > $width) {
                $lines .= ( $lines == '' ? '' : "\n" ) . $word;
            } else {
                $lines .= ( $lines == '' ? '' : ' ' ) . $word;
            }
        }
        $dims = imagettfbbox($this->font_size, 0, $this->font, 'The quick brown fox jumps over the lazy dog');
        $ascent = abs($dims[7]);
        $descent = abs($dims[1]);
        $height = $ascent + $descent;
        return array(
            'height' => $height,
            'lines' => explode("\n", $lines)
        );
    }

    /**
     * Generate image with text
     *
     * @return image resource identifier
     */
    public function image()
    {

        // Create image using existing file

        $image = imagecreatefromjpeg($this->filename);

        // Image dimensions

        $image_x = imagesx($image);
        $image_y = imagesy($image);

        // Get generated lines

        $width_wwrap = ($image_x - $this->wrap_size);
        $lines = $this->lines($width_wwrap);

        // Create color for font

        $font_color = imagecolorallocate($image, $this->color[0], $this->color[1], $this->color[2]);

        // Add line(s) to the image

        foreach ($lines['lines'] as $line_key => $line_val) {
            $dims = imagettfbbox($this->font_size, 0, $this->font, $line_val);

            // Get dimensions from bounding box and calculate

            $width = abs($dims[0]) + abs($dims[2]);
            $height = $lines['height'] + $this->line_height;

            $x = ( $image_x - $width ) / 2;
            $y = ( ( $image_x / 2 ) - ( $height / 2 ) ) + $this->font_size;

            $bbox_height = ( ( $image_y - ( count($lines['lines']) * $height ) ) / 2 ) + $lines['height'];

            $y = ( $bbox_height + ( $line_key * $height ) );

            // Write text on the image

            imagettftext($image, $this->font_size, 0, $x, $y, $font_color, $this->font, $line_val);
        }

        return $image;

    }

    /**
     * Displays image in the browser
     *
     * @return Outputs image to browser
     */
    public function output()
    {
        $image = $this->image();
        header('Content-type: image/jpeg');
        imagejpeg($image, null, 100);
        imagedestroy($image);
    }
}
