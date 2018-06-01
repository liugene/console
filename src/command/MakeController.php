<?php

namespace linkphp\console\command;

use linkphp\Application;
use linkphp\console\Command;

class MakeController extends Command
{

    public function configure()
    {
        $this->setAlias('make:controller')->setDescription('生成控制器');
    }

    public function execute()
    {
        Application::get('linkphp\console\Output')->writeln($this->make());
    }

    public function make()
    {
        $content = "<?php\nnamespace app\\controller\\main;\n\nclass Make\n{\n\n}";
        $dir = APPLICATION_PATH . 'controller/main/';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $file = fopen($dir . 'Make' . EXT, "w+");
        if (flock($file, LOCK_EX)) {
            fwrite($file, $content);
            flock($file, LOCK_UN);
        } else {
            return "无法生成 \n";
        }
        fclose($file);
        return "新建控制器成功 \n";
    }

}