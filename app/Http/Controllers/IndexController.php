<?php
/**
 * Created by PhpStorm.
 * User: lio
 * Date: 2017/1/13
 * Time: 上午11:23
 */

namespace App\Http\Controllers;

use App\Http\Service\TestService;

class IndexController
{
    public function index(){
        $num = TestService::index();

        return $num;
    }
}