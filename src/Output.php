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

use linkphp\console\style\Style;

class Output
{

    /**
     * 间隙字符
     */
    const GAP_CHAR = '  ';
    /**
     * 左边字符
     */
    const LEFT_CHAR = '  ';

    //命令行所有方法集合
    private $_method;

    private $message;

    /**
     * 样式Style对象
     * @var Style
     */
    private $_style;

    public function __construct(Style $style)
    {
        $this->_style = $style;
    }


    /*
     * 命令行主方法
     * 不带参数输出
     * */
    public function main()
    {
        $logo = "
 _        _              _                  
| |      | |   _   ___  | |      ___
| |  ___ | | / / /  _  \| |_   /  _  \
| | | \ \| |/ /  | |_| ||  _ \ | |_| |
| |_| |\ V |\ \  | .___/| | | || .___/
|_____| \ _' \_\ | |    | | | || | 
";
        $this->colored(' ' . \ltrim($logo));
    }

    public function noFound()
    {
        $this->writeln('method not defined');
    }

    /**
     * 输出一行数据
     *
     * @param string|array $messages 信息
     * @param bool   $newline  是否换行
     * @param bool   $quit     是否退出
     * @return $this
     */
    public function writeln($messages = '', $newline = true, $quit = false)
    {
        if (is_array($messages)) {
            $messages = implode($newline ? PHP_EOL : '', $messages);
        }
        // 文字里面颜色标签翻译
        $messages = $this->_style->translate((string)$messages);
        // 输出文字
        echo $messages;
        if ($newline) {
            echo "\n";
        }
        // 是否退出
        if ($quit) {
            exit;
        }
        $this->message = $messages;
        return $this;
    }

    public function getResponse()
    {
        return $this->message;
    }

    /**
     * @param string $text
     * @param string $tag
     */
    public function colored(string $text, string $tag = 'info')
    {
        $this->writeln(sprintf('<%s>%s</%s>', $tag, $text, $tag));
    }
    /**
     * 输出一个列表
     *
     * @param array       $list       列表数据
     * @param string      $titleStyle 标题样式
     * @param string      $cmdStyle   命令样式
     * @param string|null $descStyle  描述样式
     */
    public function writeList(array $list, $titleStyle = 'comment', string $cmdStyle = 'info', string $descStyle = null)
    {
        foreach ($list as $title => $items) {
            // 标题
            $title = "<$titleStyle>$title</$titleStyle>";
            $this->writeln($title);
            // 输出块内容
            $this->writeItems((array)$items, $cmdStyle);
            $this->writeln('');
        }
    }
    /**
     * 显示命令列表一块数据
     *
     * @param array  $items    数据
     * @param string $cmdStyle 命令样式
     */
    private function writeItems(array $items, string $cmdStyle)
    {
        foreach ($items as $cmd => $desc) {
            // 没有命令，只是一行数据
            if (\is_int($cmd)) {
                $message = self::LEFT_CHAR . $desc;
                $this->writeln($message);
                continue;
            }
            // 命令和描述
            $maxLength = $this->getCmdMaxLength(array_keys($items));
            $cmd = \str_pad($cmd, $maxLength, ' ');
            $cmd = "<$cmdStyle>$cmd</$cmdStyle>";
            $message = self::LEFT_CHAR . $cmd . self::GAP_CHAR . $desc;
            $this->writeln($message);
        }
    }
    /**
     * 所有命令最大宽度
     *
     * @param array $commands 所有命令
     * @return int
     */
    private function getCmdMaxLength(array $commands): int
    {
        $max = 0;
        foreach ($commands as $cmd) {
            $length = \strlen($cmd);
            if ($length > $max) {
                $max = $length;
                continue;
            }
        }
        return $max;
    }

}
