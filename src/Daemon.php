<?php

namespace linkphp\console;

use framework\Exception;
use linkphp\process\drives\Swoole;
use linkphp\process\Process;
use framework\Application;
use linkphp\swoole\HttpServer;

abstract class Daemon
{

    /**
     * Process对象
     * @var Process
     */
    protected $_process;

    /**
     * Application对象
     * @var Application
     */
    private $_app;

    private $config;

    public function __construct(Console $console,Swoole $swoole,Application $application)
    {
        $this->config = $console->getDaemonConfig();
        $this->_process = $swoole;
        $this->_app = $application;
    }

    public function app()
    {
        return $this->_app;
    }

    /**
     * @return HttpServer
     */
    public function getServer()
    {
        return $this->app()->make($this->class());
    }

    public function class()
    {
        return $this->config['server']['class'];
    }

    public function host()
    {
        return $this->config['server']['host'];
    }

    public function port()
    {
        return $this->config['server']['port'];
    }

    public function pidFile()
    {
        return $this->config['server']['setting']['pid_file'];
    }

    public function logFile()
    {
        return $this->config['server']['setting']['log_file'];
    }

    public function maxProcessNum()
    {
        return $this->config['server']['setting']['max_request'];
    }

    public function setting()
    {
        return $this->config['server']['setting'];
    }

    public function enableStaticHandle()
    {
        return $this->config['enable_static_handler'];
    }

    public function documentRoot()
    {
        return $this->config['document_root'];
    }

    public function command(Console $console)
    {
        return call_user_func([$this,$console->getArgv(1)]);
    }

    abstract public function start();

    abstract public function stop();

}