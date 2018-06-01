<?php

// +----------------------------------------------------------------------
// | LinkPHP [ Link All Thing ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 http://linkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liugene <liujun2199@vip.qq.com>
// +----------------------------------------------------------------------
// |               命令行类
// +----------------------------------------------------------------------

namespace linkphp\console;
use linkphp\Application;
use linkphp\Exception;
use linkphp\interfaces\RunInterface;

class Console implements RunInterface
{

    private $return_data;

    //命令文件
    private $command_file = [];

    private $command = [];

    /**
     * Output对象
     * @var Output
     */
    private $_output;

    private $argv;

    private $daemon = false;

    private $daemon_config = [];

    /**
     * Application对象
     * @var Application
     */
    private $_app;

    public function __construct(Output $output,Application $application)
    {
        $this->_output = $output;
        $this->_app = $application;
    }

    public function app()
    {
        return $this->_app;
    }

    public function init()
    {
        $argc = $this->app()->input('server.argc');
        $this->argv = $this->app()->input('server.argv');
        switch ($argc) {
            case 0:
                $this->return_data = $this->output()->noFound();
                break;
            case 1:
                $this->return_data = $this->output()->main();
                break;
            case 2:
                $this->execute($this->argv[1]);
                break;
            case 3:
                $this->execute([$this->argv[1],$this->argv[2]]);
                break;
            case 4:
                $this->execute([$this->argv[1],$this->argv[2]]);
                break;
        }
    }

    public function import($command)
    {
        if(empty($this->command_file)){
            $this->command_file = $command;
        }
        array_walk_recursive($this->command_file,[$this, 'configure']);
        return $this;
    }

    public function configure($value,$key)
    {
        $command = $this->app()->get($value);
        $command->configure();
        $this->setCommand($command->getAlias(),$command);
    }

    public function execute($alias)
    {
        if($this->daemon){
            $this->setReturnDate(
                $this->app()->get(
                    $this->daemon_config['daemon']
                )->command($this)
            );
        } else {
            if(is_array($alias)){
                if(isset($this->command[$alias[0]])){
                    $this->command[$alias[0]]->commandHandle($alias[1]);
                    $this->return_data = $this->_output->getResponse();
                    return;
                }
                $this->return_data = $this->_output->noFound();
                return;
            }
            if(isset($this->command[$alias])){
                $this->command[$alias]->execute();
                $this->return_data = $this->_output->getResponse();
                return;
            }
            $this->return_data = $this->_output->noFound();
        }
    }

    public function setCommand($tag,$command)
    {
        if(isset($this->command[$tag])){
            throw new Exception('命名已经存在');
        }
        $this->command[$tag] = $command;
    }

    public function getCommand($command = null)
    {
        if(isset($command)) {
            if(isset($this->command[$command])){
                return $this->command[$command];
            }
            return false;
        }
        return $this->command;
    }

    public function getArgv($argv)
    {
        if(isset($this->argv[$argv])){
            return $this->argv[$argv];
        }
        return false;
    }

    public function setReturnDate($data)
    {
        $this->return_data = $data;
    }

    public function getReturnData()
    {
        return $this->return_data;
    }

    public function setDaemon($bool)
    {
        $this->daemon = $bool;
        return $this;
    }

    public function setDaemonConfig($config)
    {
        if(file_exists($config)){
            $this->daemon_config = require_once($config);
            return $this;
        } else {
            throw new Exception('config file not exists');
        }
    }

    public function getDaemonConfig()
    {
        return $this->daemon_config;
    }

    public function output()
    {
        return $this->_output;
    }

}
