<?php
namespace Home\Controller;

use Think\Controller;
use Think\Model;

class TestController extends Controller
{
    public function test()
    {
        $user = M('ipsen_user', '', 'mysql://root:123456@localhost:3306/test');//实例化user_info表模型对象
        $temp['openid'] = $_GET['id'];//$OpenID;
        $user_info = $user->where($temp)->select();//对象查询
        if (empty($user_info[0])) {
            echo 'empty';
        } else {
            echo 'you';
        }

//        $user = M('ipsen_user', '', 'mysql://root:123456@localhost:3306/test');//实例化user_info表模型对象
//        $OpenID = 'ocoIvxFEW2V7sT3W6t6L8rZtejC0';
//        $temp['openid'] = $OpenID;
//        $user_info = $user->where($temp)->select();//对象查询
//        dump($user_info[0]);

//        $userArray = array('BIAG');
//        $content = '-----------------------------------------------------------------------\\n';
//        $groupsend = new GroupSendController();
//        $groupsend->sendByOpenId($userArray,$content);

//        $userid = array('1', '2', '3', '4', '5', '6');
//        $sendByOpenID1 = '{"touser":["';
//        $sendByOpenID2 = '"],"msgtype": "text","text": { "content": "' . $content . '"}}';
//        for ($i = 0; $i < sizeof($userid); $i++) {
//            if ($i == sizeof($userid) - 1) {
//                $sendByOpenID1 .= $userid[$i];
//            } else {
//                $sendByOpenID1 .= $userid[$i] . '","';
//            }
//        }
//        echo $sendByOpenID1 . $sendByOpenID2;
    }

    public function searchUserByOpenID()
    {
        $user = M('ipsen_user', '', 'mysql://root:123456@localhost:3306/test');//实例化user_info表模型对象
        $temp['openid'] = $_GET['id'];//$OpenID;

        //数据库查询出现错误
        $user_info = $user->where($temp)->select();//对象查询

        if (empty($user_info[0])) {
            echo $user_info[0];
        } else {
            echo $user_info[0]['userid'];
        }
    }

    //数据库连接测试
    public function getUserInfo()
    {
        $user = M('user_info');//实例化user_info表模型对象
        $user_info = $user->where('id' >= 1)->select();//对象查询
        if ($user_info) {
            //非空时以json对象返回查询结果
            $this->ajaxReturn(array("code" => "1", "data" => $user_info));
            // $this->success('操作完成','test');
//            $this->redirect('test', array('status' => 1), 3, '页面跳转中~');
        } else {
            $this->ajaxReturn(array("code" => "0", "data" => "获取信息失败！"));
        }
    }

    //user表添加数据
    public function addUser()
    {
        $user = M('user');//实例化user表模型对象

        $data['username'] = 'test';
        $data['userpassword'] = 'test';

        if (!($user->data($data)->add())) {
            $this->error('插入失败', 'test');
        } else
            $this->success('操作完成', 'test');
    }

    //数据库登录验证
    public function login()
    {
        $account = $_GET['account'];
        $user = M('user_info');//实例化user_info表模型对象
        $condition['user_name'] = $account;
        $user_info = $user->where($condition)->select();//对象查询
        if ($user_info) {
            echo $account;
        } else {
            echo '登录失败';
        }
    }

    public function getStu()
    {
        $stu_num = $_GET['stu_num'];
        $user = M('stu_class_one_grade_info', '', 'mysql://root:123456@localhost:3306/test');//实例化user_info表模型对象
        $condition['stu_num'] = $stu_num;
        $user_info = $user->where($condition)->select();//对象查询
        if ($user_info) {
            //非空时以json对象返回查询结果
            dump(array("code" => "1", "data" => $user_info));
            // $this->success('操作完成','test');
//            $this->redirect('test', array('status' => 1), 3, '页面跳转中~');
        } else {
            dump(array("code" => "0", "data" => "获取信息失败！"));
        }
    }
}