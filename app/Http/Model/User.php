<?php
/**
 * Created by PhpStorm.
 * User: lio
 * Date: 2017/1/13
 * Time: 上午11:41
 */


namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'mysql';
    protected $table = 'users';
    protected $primaryKey='id';
    public $timestamps=true;

}