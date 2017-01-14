<?php
/**
 * Created by PhpStorm.
 * User: lio
 * Date: 2017/1/13
 * Time: 下午4:07
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use XLog\XLog;

class LogFormat
{
    public $config = null;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->config = [
            'LOG_ROOT' => sprintf("/tmp/logs/%s/","Lio"),       //日志目录
            'LOG_LEVEL' => "EMERG,ALERT,ERR,DEBUG,INFO,RECORD", //日志级别
        ];

        defined('MODULE_NAME')      || define('MODULE_NAME','这个怎么分的应用模块?');
        defined('CONTROLLER_NAME')  || define('CONTROLLER_NAME',$this->getCurrentControllerName());
        defined('ACTION_NAME')      || define('ACTION_NAME',$this->getCurrentActionName());
        $this->exec();

        return $next($request);
    }

    public function exec(){
        XLog::init($this->config);
    }


    /**
     * 获取当前控制器名
     *
     * @return string
     */
    public function getCurrentControllerName()
    {
        return $this->getCurrentAction()['controller'];
    }

    /**
     * 获取当前方法名
     *
     * @return string
     */
    public function getCurrentActionName()
    {
        return $this->getCurrentAction()['method'];
    }

    /**
     * 获取当前控制器与方法
     *
     * @return array
     */
    public function getCurrentAction()
    {
        $action = Route::current()->getActionName();
        list($class, $method) = explode('@', $action);

        return ['controller' => $class, 'method' => $method];
    }
}