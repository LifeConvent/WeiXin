<?php
/**
 * Created by PhpStorm.
 * User: lawrance
 * Date: 16/9/24
 * Time: 下午1:15
 */
namespace Home\Controller;

use Think\Controller;
use Think\Model;

header("Content type:text/html;charset=utf-8");

class GradeController extends Controller
{

    public function deal()
    {
        $data = $this->getUserInfo($_GET['stu_num']);
        $list = $data['data'];
        $jidian = 0;
        $xuefen = 0;
        for ($i = 1; $i <= count($list); $i++) {
            $temp = $list[$i-1];
            if($temp['stu_dian']!=0) {
                $jidian += (float)$temp['stu_fen'] * (float)$temp['stu_dian'];
//            echo $jidian.'----';
                $xuefen += (float)$temp['stu_fen'];
//            echo $xuefen.'----';
            }
        }
        $average = $jidian/$xuefen;
        $average = sprintf('%.3f', (float)$average);
        $this->assign('average','总平均绩点: '.$average);
        $this->assign('list', $list);
        $this->display();
    }

    public function getUserInfo($stu_num)
    {
        $user = M('stu_class_one_grade_info', '', 'mysql://root:123456@localhost:3306/test');//实例化user_info表模型对象
        $condition['stu_num'] = $stu_num;
        $user_info = $user->where($condition)->select();//对象查询
        if ($user_info) {
            //非空时以json对象返回查询结果
            return array("code" => "1", "data" => $user_info);
            // $this->success('操作完成','test');
//            $this->redirect('test', array('status' => 1), 3, '页面跳转中~');
        } else {
            return array("code" => "0", "data" => "获取信息失败！");
        }
    }
}