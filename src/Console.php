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
use linkphp\boot\Exception;
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

    public function __construct()
    {
        $this->_output = Application::get('linkphp\console\Output');
    }

    public function set(Console $console)
    {
        return $this;
    }

    public function init()
    {
        $argc = Application::input('server.argc');
        switch ($argc) {
            case 0:
                $this->return_data = $this->_output->noFound();
                break;
            case 1:
                $this->return_data = $this->_output->main();
                break;
            case 2:
                $argv = Application::input('server.argv');
                $this->execute($argv[1]);
                break;
            case 3:
                $argv = Application::input('server.argv');
                $this->execute([$argv[1],$argv[2]]);
                break;
            case 4:
                $this->argv = Application::input('server.argv');
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
        $command = Application::get($value);
        $command->configure();
        $this->setCommand($command->getAlias(),$command);
    }

    public function execute($alias)
    {
        if($this->daemon){
            Application::get('linkphp\\console\\Daemon')
                ->start($this);
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
            throw new Exception('命名名已经存在');
        }
        $this->command[$tag] = $command;
    }

    public function getCommand($command)
    {
        if(isset($this->command[$command])){
            return $this->command[$command];
        }
        return false;
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
