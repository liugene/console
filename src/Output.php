<?php

namespace linkphp\console;
// +----------------------------------------------------------------------
// | LinkPHP [ Link All Thing ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 http://linkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liugene <liujun2199@vip.qq.com>
// +----------------------------------------------------------------------
// |               命令行输出类
// +----------------------------------------------------------------------

class Output
{

    //命令行所有方法集合
    private $_method;

    private $response;


    /*
     * 命令行主方法
     * 不带参数输出
     * */
    public function main()
    {
        return "Link Console version 0.1 \r\n" .
               "Usage: \n" .
               "command [options] [arguments] \n" .
               "Options: \n".
                 "-h, --help            Display this help message \n".
                 "-V, --version         Display this console version \n".
                 "-q, --quiet           Do not output any message \n".
                 "--ansi                Force ANSI output \n".
                 "--no-ansi             Disable ANSI output \n".
               "Available commands: \n".
                 "build              Build Application Dirs \n".
                 "clear              Clear runtime file \n".
                 "help               Displays help for a command \n".
                 "list               Lists commands \n".
                 "make \n".
                   "make:controller    Create a new resource controller class \n".
                   "make:model         Create a new model class \n";
    }

    public function noFound()
    {
        return 'method not defined';
    }

    public function writeln($data)
    {
        $this->response = $data;
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

}
