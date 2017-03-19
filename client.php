<?php
/**
 * @param $url - The url to download.
 * @param int $width - The desired width.
 * @param int $height - The desired height.
 * @param $baseUrl - The url where smart-image-resizer is installed, including last / (http://images.example.com/thumbs/)
 *
 * @return string - The url of the image.
 */
function createThumbUrl($url, $width = 140, $height = 200, $baseUrl)
{
    $file = basename($url);
    $efile = urlencode($file);
    $url = str_replace($file, $efile, $url);
    $url = base64_encode($url);
    $path = $url;
    $qpos = strpos($path, "?");
    $filename = md5($url) . '-' . $url . '-' . $width . '-' . $height;
    if ($qpos !== false) $path = substr($path, 0, $qpos);
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    if ($extension == 'xpng')
    {
        $extension = 'png';
    }
    $extensions = array();
    $extensions[] = 'jpg';
    $extensions[] = 'jpeg';
    $extensions[] = 'JPG';
    $extensions[] = 'JPEG';
    $extensions[] = 'bmp';
    $extensions[] = 'BMP';
    $extensions[] = 'png';
    $extensions[] = 'gif';
    if (!in_array($extension, $extensions))
    {
        $extension = 'jpg';
    }

    return $baseUrl . $filename[0] . '/' . $filename[1] . '/' . $filename[2] . '/' . $filename[3] . '/' . $filename . '.' . $extension;
}

?>