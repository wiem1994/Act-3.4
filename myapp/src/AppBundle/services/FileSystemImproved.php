<?php

namespace AppBundle\Controller;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Controller\Dispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class FileSystemImproved extends Controller
{


    public function __construct()
    {
        $current_dir_path = getcwd();
        try {
            $fs = new Filesystem();
            $new_dir_path = $current_dir_path . "/fsi";
            if (!$fs->exists($new_dir_path)) {
                $fs->mkdir($new_dir_path, 0777);
            } else {
                return new Response('<html><body>The folder is already created ! </body></html>');
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating directory at" . $exception->getPath();
        }
        return new Response('<html><body>The folder has been successfully created ! </body></html>');
    }


    /**
     * @Route("/create-file/{filename}")
     */
    public function createFile($filename)
    {
        $fs = new Filesystem();
        $current_dir_path = getcwd();
        try {
            $new_file_path = $current_dir_path . "/fsi/" . $filename;

            if (!$fs->exists($new_file_path)) {
                $fs->touch($new_file_path);
                $fs->chmod($new_file_path, 0777);
                $dispatcher = new Dis();
                $name = 'fileCreated';
                $dispatcher->dispatch($dispatcher, $name);
            } else {
                echo "The file is already created !";
                return $this->getFiles();
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating file at" . $exception->getPath();
        }
        echo "the file : " . "<B>" . $filename . "</B>" . " has been successfully created";
        return $this->getFiles();
    }



    /**
     * @Route("/delete-file/{filename}")
     */
    public function deleteFile($filename)
    {
        $fs = new Filesystem();
        $current_dir_path = getcwd();
        $path = $current_dir_path . "/fsi/" . $filename;
        try {
            if (file_exists($path)) {
                $fs->remove($path);
            } else {
                return new Response('<html><body>false, file does not exist" </body></html>');
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating file at" . $exception->getPath();
            return "false";
        }
        return new Response('<html><body>true </body></html>');
    }


    /**
     * @Route("/read-file/{filename}")
     */
    public function readFile($filename)
    {
        $fs = new Filesystem();
        $current_dir_path = getcwd();
        $path = $current_dir_path . "/fsi/" . $filename;
        try {
            $handle = fopen($path, "r");
            if ($handle) {
                foreach (file($path) as $line) {
                    // echo '<html><body></br>' . $line . '</body></html>';
                    $line;
                }
            } else {
                return new Response('<html><body>you can not read an unexsisted file! </body></html>');
            }
            fclose($handle);
        } catch (IOExceptionInterface $exception) {
            echo "Error creating file at" . $exception->getPath();
        }
        //return new Response('<html><body>file readed </body></html>');
        return new JsonResponse(json_encode(file($path)));
    }

    /**
     * @Route("/write-in-file/{filename}/{text}/{offset}")
     */
    public function writeinFile($filename, $text, $offset = 0)
    {
        $fs = new Filesystem();
        $current_dir_path = getcwd();
        try {
            $new_file_path = $current_dir_path . "/fsi/" . $filename;
            if ($fs->exists($new_file_path)) {

                $file = fopen($new_file_path, "c");
                // fseek($file, $offset);
                $array = file($new_file_path);
                var_dump($array);
                // $array[0] = $text;
                array_unshift($array, $text);
                var_dump($array);
                // fclose($new_file_path);
                $x = implode(",", $array);
                fwrite($file, $x);
            } else {
                return new Response('<html><body>The filename does not exist ! </body></html>');
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating file at" . $exception->getPath();
        }
        return new Response('<html><body>The content has been successfully added to the file ! </body></html>');
    }

    /**
     * @Route("/state")
     */

    public function getFiles()
    {
        $current_dir_path = getcwd();
        $path = $current_dir_path . "/fsi/";
        $files = scandir($path);
        // foreach ($files as &$value) {
        //     echo  $value . "</br>";
        // }
        return new JsonResponse(json_encode($files));
    }
}
