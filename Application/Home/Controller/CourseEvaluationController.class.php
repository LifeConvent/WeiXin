<?php
/**
 * Created by PhpStorm.
 * User: lawrance
 * Date: 16/10/7
 * Time: 下午1:07
 */

namespace Home\Controller;

use Think\Controller;
use Think\Model;

class CourseEvaluationController extends Controller
{
    public function getCourse()
    {
        $stu_num = $_GET['stu_num'];
        $course = M('course_info_personal', '', 'mysql://root:123456@localhost:3306/course_evaluation');
        $condition['stu_num'] = $stu_num;
        $course_info = $course->where($condition)->select();//对象查询
        if ($course_info) {
            //非空时以json对象返回查询结果
            $count = count($course_info);
            $course_detail_list = array();
            for($i=0;$i<$count;$i++){
                $temp = getCourseSysInfo($course_info[$i]['course_num_sys']);
                if($temp)
                    array_push($course_detail_list,$temp);
            }

        }
        $this->assign('list', $course_detail_list);
        $this->display();
    }


}

function getCourseSysInfo($course_num)
{
    $user = M('course_info_sys','', 'mysql://root:123456@localhost:3306/course_evaluation');
    $condition['course_sys_num'] = $course_num;
    $course_info = $user->where($condition)->select();//对象查询
    if ($course_info) {
        return $course_info[0];
    } else {
        return '';
    }
}