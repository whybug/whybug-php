<?php
namespace Whybug;

use Composer\Script\Event;

class Installer {

    public function postInstall(Event $event)
    {
        var_dump($event);
        file_put_contents('test1', print_r($event, true));
    }

    public function postUpdate(Event $event)
    {
        var_dump($event);
        file_put_contents('test2', print_r($event, true));
    }

    public function install()
    {
        $this->dumpAutoload();
        $this->updatePhpIni();
    }

    protected function dumpAutoload()
    {
    }

    protected function updatePhpIni()
    {

    }
}
