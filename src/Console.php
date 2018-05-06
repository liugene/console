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
use linkphp\console\command\Output;
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

    public function __construct(Output $output)
    {
        $this->_output = $output;
    }

    public function set(Console $console)
    {
        return $this;
    }

    public function init()
    {
        if(Application::input('server.argc') == 1){
            $this->return_data = $this->_output->main();
        } else {
            $this->return_data = $this->_output->noFound();
        }
        $argv = Application::input('server.argv');
        $this->execute($argv[1]);
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
        $command = new $value();
        $command->configure();
        $this->setCommand($command->getAlias(),$command);
    }

    public function execute($alias)
    {
        if(isset($this->command[$alias])){
            $this->command[$alias]->execute();
            $this->return_data = Application::get('linkphp\console\command\Output')->getResponse();
            return;
        }
        $this->return_data = $this->_output->noFound();
    }

    public function setCommand($tag,$command)
    {
        if(isset($this->command[$tag])){
            throw new Exception('命名名已经存在');
        }
        $this->command[$tag] = $command;
    }

    public function getReturnData()
    {
        return $this->return_data;
    }
}
