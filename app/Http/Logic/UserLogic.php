<?php

namespace App\Http\Logic;

use Illuminate\Http\Request;
use \App\User;

class UserLogic
{
    /**
     * ユーザ登録処理
     */
    public static function save($request): User
    {
        $user = new User();

        $user->account_id = 'aaa_inoue' . date('ymdhis', strtotime("now"));
        $user->password = '12345'; // 変換して
        $user->name = 'aaa_inoue';
        $user->email = 'inoue@inoue.inoue'. date('ymdhis', strtotime("now"));
        $user->phone = '1;23-456-789';
        $user->department_id = 123;

        $user->save();

        return $user;
    }
}
