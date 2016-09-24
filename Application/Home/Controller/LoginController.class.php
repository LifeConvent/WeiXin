<?php
namespace Home\Controller;

use Think\Controller;

class LoginController extends Controller
{
    //数据库登录验证
    public function login()
    {
        $account = $_POST['account'];
        $user = M('user');//实例化user表模型对象
        $condition['username'] = $account;
        $user_info = $user->where($condition)->select();//对象查询
//        dump($user_info);
        if ($user_info[0]) {
            if($user_info[0]['userpassword']==md5($_POST['password']))
                echo 'SUCCESS';
            else{
                echo 'WRONG_PASSWORD';
            }
        } else {
            echo 'ACCOUNT_NOT_EXIST';
        }
    }

    public function test(){
        echo 'ceshi';
    }
}