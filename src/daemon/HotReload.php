<?php

namespace linkphp\console\daemon;

use linkphp\console\Daemon;
use linkphp\swoole\Reload;
use framework\Exception;

class HotReload extends Daemon
{

    public function start()
    {
        if ($pid = $this->_process->getMasterPid($this->pidFile())) {
            return '存在';
        }
        //存放SWOOLE服务的PID
        $file = ROOT_PATH . 'src/runtime/run/link-httpd.pid';
        if (file_exists($file)) {
            $kit = new Reload($file, $this->_process);
            $kit->watch(ROOT_PATH);//监控的目录
            $kit->run();
        } else {
            throw new Exception('httpd没有启动');
        }
    }

    public function stop()
    {
        if ($pid = $this->_process->getMasterPid($this->pidFile())) {
            $this->_process->kill($pid);
            while ($this->_process->isRunning($pid)) {
                // 等待进程退出
                usleep(100000);
            }
            return 'hotReload stop completed.';
        } else {
            return 'hotReload is not running.';
        }
    }

}