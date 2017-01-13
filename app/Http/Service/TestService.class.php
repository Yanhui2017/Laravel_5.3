<?php

/**
 * Created by PhpStorm.
 * User: lio
 * Date: 2017/1/13
 * Time: 上午11:19
 */
namespace App\Http\Service;

use App\Http\Model\User;

class TestService
{
    public static function index(){

        $user = User::where('id',1)->first();
        return $user;
    }
}