<?php



namespace AppBundle\Controller;

use AppBundle\Controller\FileSystemImproved;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;




class Dis

{
    public function dispatch()
    {
        $dispatcher = new EventDispatcher;
        $dispatcher->addListener('fileCreated', function () {
            $current_dir_path = getcwd();
            $new_file_path = $current_dir_path . "/fsi/history.txt";
            $files = new FileSystemImproved();
            $files->writeinFile($new_file_path, "a new file has been created", $offset = 0);
            return new Response('<html><body>history saved ! </body></html>');
        });
    }
}
