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
use linkphp\console\command\Output;
use linkphp\interfaces\RunInterface;

class Command implements RunInterface
{
    public function init()
    {
        if(Application::input('server.argc') == 1){
            Output::main();
        } else {
            Output::noFound();
        }
    }
}