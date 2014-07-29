<?php
namespace Whybug;

use Composer\Script\Event;

class Installer {

    public function postInstall(Event $event)
    {

    }

    public function postUpdate(Event $event)
    {

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
