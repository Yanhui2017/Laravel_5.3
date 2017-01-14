<?php
/**
 * Created by PhpStorm.
 * User: lio
 * Date: 2017/1/13
 * Time: 下午3:54
 */

namespace App\Http\Common\Utils;

use XLog\XLog;

class CLog
{
    const PAY     = "PAY";
    const SMS     = "SMS";
    const CAR     = "CAR";
    const RISK    = "RISK";
    const SERVICE = "SERVICE";

    public static function sms($info)
    {
        XLog::log($info,'SMS',XLog::INFO);
    }

    public static function api($info)
    {
        XLog::log($info,'API',XLog::INFO);
    }

    public static function apiVisit($info)
    {
        XLog::log($info,'API_VISIT',XLog::INFO);
    }

    public static function pay($info)
    {
        XLog::log($info,'PAY',XLog::INFO);
    }

    public static function wechat($info)
    {
        XLog::log($info,'WECHAT',XLog::INFO);
    }

    public static function sunshine($info)
    {
        XLog::log($info,'SUNSHINE',XLog::INFO);
    }

    public static function fuiou($info)
    {
        XLog::log($info,'FUIOU',XLog::INFO);
    }

    public static function session($info)
    {
        XLog::log($info,'SESSION',XLog::INFO);
    }

    /**
     * 调试Log信息记录
     * @param $info mixed 需要记录的信息
     */
    public static function info($info) {
        XLog::log($info,'INFO');
    }

    /**
     * 这个应该放在请求结束中间件中
     */
    protected function statistics()
    {
        $mark = MODULE_NAME . CONTROLLER_NAME . ACTION_NAME;
        G($mark . 'end');
        $url_this = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
        $parameter = http_build_query($_REQUEST);
        if (empty($parameter)) $parameter = file_get_contents('php://input');
        $data = [
            gethostname(),//hostname
            get_client_ip(),
            intval(G($mark . 'start', $mark . 'end', 6) * 1000),//接口耗时毫秒
            session('uid'),
            session_id(),
            cookie('D'),
            MODULE_NAME,
            CONTROLLER_NAME,
            ACTION_NAME,
            explode('?', $url_this)[0],//请求url(不带参数)
            $parameter,
        ];
        XLog::api(implode('^', $data));
    }

    /**
     * 调用错误日志
     * @param $info string 日志信息
     */
    public static function error($info)
    {
        $errorMessage['error_code'] = $info;
        $errorMessage['error_message'] = C($info) ? C($info) : $info;
        $url_this = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
        $selfMessage = [
            gethostname(),
            get_client_ip(),
            session('uid'),
            session_id(),
            cookie('D'),
            MODULE_NAME,
            CONTROLLER_NAME,
            ACTION_NAME,
            explode('?', $url_this)[0],//请求url(不带参数)
            json_encode($_REQUEST, JSON_UNESCAPED_UNICODE),
            json_encode($errorMessage, JSON_UNESCAPED_UNICODE)
        ];
        XLog::error($selfMessage);
    }

    /**
     * 远程调用日志
     * @param $firmid string 只能为PAY,SMS,CAR,RISK,SERVICE
     * @param $url string 接口地址
     * @param $parameter mixed 接口请求参数
     * @param $response mixed 接口返回数据
     */
    public static function remote($firmid, $time, $url, $parameter, $response)
    {
        $data = array(
            gethostname(),
            $firmid,
            $time,
            $url,
            $parameter,
            $response,
        );
        XLog::access($data);
    }

    /**
     * 远程调用错误日志,调用远程接口[第三方]时返回错误的log日志
     * @param $firmid  string 业务id
     * @param $url  string 接口地址
     * @param $parameter mixed 接口请求参数列表
     * @param $response mixed 接口返回数据
     * @param $errorCode mixed 错误码
     */
    public static function rerror($firmid, $time, $url, $parameter, $response, $errorCode)
    {
        $errorMessage['error_code'] = $errorCode;
        $errorMessage['error_message'] = C($errorCode) ? C($errorCode) : $errorCode;
        $data = array(
            gethostname(),
            $firmid,
            $time,
            $url,
            $parameter,
            $response,
            json_encode($errorMessage, JSON_UNESCAPED_UNICODE)
        );
        XLog::access_error($data);
    }

    /**
     * @param string $sql
     * @param null $times
     * @param null $memories
     */
    public static function sqlInfo($sql = '',$times = null,$memories = null){
        $url_this = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
        $data = [
            gethostname(),
            get_client_ip(),
            $times,
            $memories,
            explode('?', $url_this)[0],
            $sql
        ];
        XLog::log($data,'SQL','SQL');
    }
}