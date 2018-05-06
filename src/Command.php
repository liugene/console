<?php

namespace linkphp\console;

use linkphp\console\command\Output;

class Command
{

    //报错命令别名
    private $alias;

    //命令描述
    private $description;

    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function configure(){}

    public function execute(){}

}