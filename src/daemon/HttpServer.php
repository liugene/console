<?php

namespace linkphp\console\daemon;

use linkphp\console\Daemon;

class HttpServer extends Daemon
{

    public function start()
    {
        if ($pid = $this->_process->getMasterPid($this->pidFile())) {
            return '存在';
        }
        $this->getServer()
            ->setHost(
                $this->host()
            )->setPort(
                $this->port()
            )->setProcess(
                $this->_process
            )->setting(
                $this->setting()
            )->setEnableStaticHandler(
                $this->enableStaticHandle()
            )->setDocumentRoot(
                $this->documentRoot()
            )->start();
    }

    public function stop()
    {
        if ($pid = $this->_process->getMasterPid($this->pidFile())) {
            $this->_process->kill($pid);
            while ($this->_process->isRunning($pid)) {
                // 等待进程退出
                usleep(100000);
            }
            return 'mix-httpd stop completed.';
        } else {
            return 'mix-httpd is not running.';
        }
    }

}