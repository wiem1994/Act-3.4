<?php

namespace AppBundle\Controller;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends Controller
{

    /**
     * @Route("/create/{filename}")
     */
    public function createFile($filename)
    {
        $fs = new Filesystem();
        $current_dir_path = getcwd();
        try {
            $new_file_path = $current_dir_path . "/" . $filename;

            if (!$fs->exists($new_file_path)) {
                $fs->touch($new_file_path);
                $fs->chmod($new_file_path, 0777);
            } else {
                return new Response('<html><body>The file is already created ! </body></html>');
            }
        } catch (IOExceptionInterface $exception) {
            $var = $exception->getPath();
            echo "Error creating file at" . $exception->getPath();
        }
        return new Response('<html><body>The file has been successfully created ! </body></html>');
    }





    /**
     * @Route("/write/{filename}/{text}")
     */
    public function writeFile($filename, $text)
    {
        $fs = new Filesystem();
        $current_dir_path = getcwd();
        try {
            $new_file_path = $current_dir_path . "/" . $filename;

            if ($fs->exists($new_file_path)) {
                $fs->appendToFile($filename, $text . "\n");
            } else {
                return new Response('<html><body>The filename does not exist ! </body></html>');
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating file at" . $exception->getPath();
        }
        return new Response('<html><body>The content has been successfully added to the file ! </body></html>');
    }

    /**
     * @Route("/copy/{from}/{to}")
     */
    public function copyFile($from, $to)
    {
        $fs = new Filesystem();
        $current_dir_path = getcwd();
        $path_sender = $current_dir_path . "/" . $from;
        $path_reciever = $current_dir_path . "/copied/" . $to;
        try {
            if ($fs->exists($path_sender)) {
                $fs->copy($path_sender, $path_reciever);
            } else {
                return new Response('<html><body>The filename does not exist ! </body></html>');
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating file at" . $exception->getPath();
        }
        return new Response('<html><body>The content has been successfully copied ! </body></html>');
    }

    /**
     * @Route("/delete/{filename}")
     */
    public function deleteFile($filename)
    {
        $fs = new Filesystem();
        $current_dir_path = getcwd();
        $path = $current_dir_path . "/" . $filename;
        try {
            if (file_exists($path)) {
                $fs->remove($path);
            } else {
                return new Response('<html><body>The filename does not exist ! </body></html>');
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating file at" . $exception->getPath();
        }
        return new Response('<html><body>The filename has been successfully deleted ! </body></html>');
    }
}
