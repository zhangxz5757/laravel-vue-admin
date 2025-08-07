<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

class MonitorController extends BaseController
{

    /**
     * 服务器监控信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function serverAction()
    {
        $linfo = new \Linfo\Linfo;
        $parser = $linfo->getParser();

        $cpu = [
            'num' => count($parser->getCPU()),
            'load_now' => $parser->getLoad()['now'] ?? '',
            'load_5' => $parser->getLoad()['5min'] ?? '',
            'load_15' => $parser->getLoad()['15min'] ?? '',
        ];
        $sysLoad = $parser->getLoad();
        $sys = [
            'hostname'  => $parser->getHostname(),
            'os'        => $parser->getOS(),
            'up_time'   => $parser->getUpTime()['text'] ?? '',
            'framework' => $parser->getCPUArchitecture(),
            'kernel'    => $parser->getKernel(),
            'load' => $sysLoad['now'] ?? '',
            'cpu' => count($parser->getCPU()),
        ];

        return $this->jsonSuccess([
            'cpu'  => $cpu,
            'mem'  => $parser->getRam(),
            'sys'  => $sys,
            'disk' => $parser->getMounts()
        ]);
    }
}
