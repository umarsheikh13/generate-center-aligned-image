# Generate Center Aligned Image

This class allows you to generate an image using an existing image with text center aligned text horizontally and vertically.

## Usage

```
$img = new CenteredTextImage($text, $filename, $font, $font_size, $color, $wrap_size, $line_height);
$img->output(); // Outputs image to the browser
return $img; // Returns image object
```

## Parameters

Attribute    | Type     | Description
---          | ---		  | ---
`text`	     | *string* | The text you want to write on the image.
`filename`   | *string* | Path to JPEG.
`font`       | *string* | Path to your truetype font
`font_size`  | *int*    | Pixel size (GD1) or point size (GD2)
`color`      | *array*  | An array of 3 values representing RGB (0-255)
`wrap_size`  | *int*    | The space around the text in pixels
`line_height`| *int*    | The space between the lines

## Example

```
$img = new CenteredTextImage("Hello world!", 'image.jpg', 'Lato-Bold.ttf', 20, array(255, 255, 255), 200, 10);
$img->output();
```

![Center Aligned Text](http://s14.postimg.org/mvkgzw34h/image.jpg)

## License

[MIT License](http://opensource.org/licenses/MIT)
