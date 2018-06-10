<?php

namespace linkphp\console\command;

use framework\Application;
use linkphp\console\Command;

class MakeModel extends Command
{

    public function configure()
    {
        $this->setAlias('make:model')->setDescription('生成模型');
    }

    public function execute()
    {
        Application::get('linkphp\console\Output')->writeln($this->make());
    }

    public function make()
    {
        $content = "<?php\nnamespace app\\model\\main;\n\nclass Make\n{\n\n}";
        $dir = APPLICATION_PATH . 'model/main/';
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
        return "新建模型成功 \n";
    }

}