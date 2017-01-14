<?php
/**
 * Created by PhpStorm.
 * User: lio
 * Date: 2017/1/13
 * Time: 上午11:23
 */

namespace App\Http\Controllers;

use App\Http\Common\Utils\CLog;
use App\Http\Service\TestService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use XLog\XLog;

class IndexController
{
    public function index()
    {
        CLog::pay(123);
        $num = TestService::index();

//        DB::connection()->enableQueryLog(); // 开启查询日志
//        DB::table('uses')->where('id',7)->first(); // 要查看的sql
//        $query = DB::getQueryLog(); // 获取查询日志
//        dump($query);


//        DB::enableQueryLog();
//        $user = User::where('user_id',1)->first();
//        $query = DB::getQueryLog(); // 获取查询日志
//        dump($query);


        //config/app.php-> 'log_max_files' => 30, 日志保留30天
        //.env.local ->APP_LOG=daily， 格式每天生产一个文件
        //手动添加日志例子：
        //Log::info('Showing user profile for user: '.$id);
        return $num;
    }


    public function pay(){
        //$num = TestService::index();

        $orderby = $date = '';
        if(!empty($request['sort'])){
            $orderby .= " order by {$request['sort']} {$request['order']}";
        }else{
            $orderby .= " order by id desc ";
        }
        if(!empty($request['search'])){
            $date .= ' AND date_format(cc.create_time,"%Y-%m-%d") ='. "'{$request['search']}'";
        }
        $page_size = empty($request['limit']) ? 10 : $request['limit'];
        $offset = empty($request['offset']) ? 0 : $request['offset'];
        $sql = <<<EOT
select aa.id as 'id', aa.mc_name as 'merchant_name',dd.order_no as 'order_no',dd.account_name as 'merchant_account',dd.account_no as 'account',dd.bank_name as 'bank_title',dd.branch_name as 'bank_name',dd.open_province as 'province',dd.open_city as 'city',dd.funds as 'funds',dd.remark as 'extra',
case dd.account_type
    when 1 then '对公'
    when 0 then '对私' END as 'merchants_type',
case cc.order_status
    when 0 then substring(cc.pay_return,81,4)
    when 1 then substring(cc.pay_return,39,1)
    when 2 then
        case substring(cc.pay_return,37,4)
            when '0061' then '参数不合法'
            when '0095' then '开户行所在省不能为空或不合法'
            when '0036' then '不支持该银行编码和银行名称' else cc.pay_return
        END
    END as 'code'
    /* if(substring(cc.pay_return,37,4)='0061','参数不合法',substring(cc.pay_return,37,4)) END as 'code' */,
case cc.order_status
    when 1 then '成功'
    when 2 then '失败'
    when 0 then '打款结果等待中' END as 'result',
'' as '备注',cc.create_time
from brd_qb_merchants aa
left join brd_qb_merchants_pay bb ON aa.mc_no = bb.mc_no
left join brd_order_cash cc ON bb.order_no = cc.order_no
left join brd_op_cash_order dd ON cc.order_no = dd.order_no
where cc.uid = -1 AND cc.business = 6 $date $orderby
limit $offset,$page_size;
EOT;


        $countsql = <<<EOT
select count(*) as count from brd_qb_merchants aa
left join brd_qb_merchants_pay bb ON aa.mc_no = bb.mc_no
left join brd_order_cash cc ON bb.order_no = cc.order_no
left join brd_op_cash_order dd ON cc.order_no = dd.order_no
where cc.uid = -1 AND cc.business = 6 $date;
EOT;
//AND date_format(cc.create_time,'%Y-%m-%d') = '2016-12-16'
        $users = DB::connection('mysql_pay');
        $data['rows'] = $users->select($sql);
        $data['total'] = $users->select($countsql);
        $data['total'] = json_decode(json_encode($data['total'][0]),true)['count'];
        return json_encode($data);

        //return $num;
    }
}