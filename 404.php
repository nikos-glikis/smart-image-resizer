<?php

require_once(__DIR__ . "/vendor/autoload.php");
use Intervention\Image\ImageManager;

require_once('config.php');

try
{
    $currentDir = str_replace($_SERVER['DOCUMENT_ROOT'], "", __DIR__);
    if ($debug)
    {
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);
    }
    else
    {
        ini_set('display_errors', 'off');

    }

    $filename = $_SERVER['REQUEST_URI'];
    $filename = str_replace($currentDir . "/", "", $filename);

    while (strpos($filename, "/") !== FALSE)
    {
        $filename = substr($filename, strpos($filename, "/") + 1, strlen($filename));
    }


    $domain = $_SERVER['HTTP_HOST'];

    $parts = explode('-', $filename);
    if (count($parts) != 4)
    {
        throw new \Exception("Invalid url -" . count($parts));
    }

    $data = $parts[1];

    if (!(base64_encode(base64_decode($data, true)) === $data))
    {
        throw new \Exception($data . ' is not a valid base64 string');
    }


    $image = base64_decode($data);
    if (filter_var($image, FILTER_VALIDATE_URL) === FALSE)
    {
        throw new \Exception("Not a valid URL: " . $image);
    }

    if (strpos($image, 'http') !== 0)
    {
        throw new \Exception("Not a valid protocol: " . $image);
    }

    $width = $parts[2];
    $height = $parts[3];


    $height = str_replace('.jpg', '', $height);
    $lfilename = '' . $filename[0] . '/' . $filename[1] . '/' . $filename[2] . '/' . $filename[3] . '/' . $filename;
    if (!is_numeric($height) || !is_numeric($width) || $height < 1 || $width < 1)
    {
        throw new \Exception("Invalid sizes.");
    }
    try
    {
        $manager = new ImageManager(array('driver' => $driver));

        $img = $manager->make($image);

        $img->fit($width, $height);

        $dir = dirname($lfilename);
        if (!is_dir($dir))
        {
            mkdir($dir, 755, true);
        }
        $img->save($lfilename);
    }
    catch (\Exception $e)
    {
        if ($onePixelImageOneError)
        {
            file_put_contents($lfilename, base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII="));
        }
        throw $e;
    }

    redirectToSaved();
}
catch (Exception $e)
{
    if ($logErrors)
    {
        $actualLink = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $message = "Error: " . $e->getMessage() . " Request url: " . $actualLink;
        file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX);
    }
    if ($debug)
    {
        die($e->getMessage());
    }
    header("HTTP/1.1 500 Internal Server Error");
}

function redirectToSaved()
{
    header("Location: " . $_SERVER['REQUEST_URI']);
}

?>
