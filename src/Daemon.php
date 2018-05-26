<?php

namespace linkphp\console;

use linkphp\boot\Exception;
use linkphp\process\drives\Swoole;
use linkphp\process\Process;
use linkphp\Application;

class Daemon
{

    /**
     * Process对象
     * @var Process
     */
    private $_process;

    private $config;

    public function __construct(Console $console)
    {
        $this->config = $console->getDaemonConfig();
    }

    private function getServer()
    {
        dump(Application::make($this->config['server']['class']));die;
        return Application::make($this->config['server']['class']);
    }

    public function class(){}

    public function host(){}

    public function port(){}

    public function pidFile(){}

    public function logFile(){}

    public function start(Console $console)
    {
        $this->getServer();die;
        $this->getServer()->start(function () use($console){
            if(isset($this->command[$this->argv[1]])){
                $console->getCommand(
                    $console->getArgv(1)
                )->commandHandle(
                    $console->getArgv(2)
                    , $this->_process
                );
                $console->setReturnDate(
                    $console->output()->getResponse()
                );
            }
            $console->setReturnDate(
                $console->output()->noFound()
            );
        });
    }

}