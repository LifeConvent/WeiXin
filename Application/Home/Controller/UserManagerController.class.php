<?php
/**
 * Created by PhpStorm.
 * User: lawrance
 * Date: 16/10/26
 * Time: 下午2:17
 */

namespace Home\Controller;

use Think\Controller;
use Think\Model;

class UserManagerController extends Controller
{
    public function uploaderSetTag()
    {
        $upMenu = new MenuController();
        $access_token = $upMenu->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token=' . $access_token;
        $dataClass = new DataController();
        $result = $upMenu->https_request($url, $dataClass->tag);
        $data = new \stdClass();
        $data = json_decode($result);
        $data = $data->tag;
        //将获得的对应标签用户id name 存储用户OpenID至数据库  未处理错误时的情况
        $data->id;
        $data->name;
    }

    public function setTagUser()
    {
        $upMenu = new MenuController();
        $access_token = $upMenu->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=' . $access_token;
        $dataClass = new DataController();
        $result = $upMenu->https_request($url, $dataClass->user_tags);
        $data = new \stdClass();
        $data = json_decode($result);
        if ($data->errmsg == 'ok')
            return true;
        else
            return false;
    }

    public function addOpenInfo()
    {
        $this->display();
    }

    public function bindOpenId()
    {
        $OpenID = $_GET['id'];
        $this->assign('openid', $OpenID);
        $this->display();
    }

    public function bindUserInfo()
    {
        $OpenID = $_POST['openid'];
        $initial = $_POST['initial'];
        $res = $this->addUserInfo($OpenID, $initial);
        if ($res) {
            $result['status'] = 'success';
            $result['hint'] = '绑定成功！';
        } else {
            $result['status'] = 'failed';
            $result['hint'] = '绑定失败！';
        }
        exit(json_encode($result));
    }

    public function addUserInfo($OpenID, $initial)
    {
        $user = M('ipsen_user', '', 'mysql://root:123456@localhost:3306/test');//实例化user_info表模型对象
        $condition['openid'] = $OpenID;
        $condition['userid'] = $initial;
        $temp['userid'] = "$initial";
        if ($user->where($temp)->find()) {
            return false;
        }
        $result = $user->add($condition);//对象插入
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function searchUserByOpenID($OpenID)
    {
        $user = M('ipsen_user', '', 'mysql://root:123456@localhost:3306/test');//实例化user_info表模型对象
        $temp = array();
        $temp['openid'] = 'ocoIvxABH5qrX70_m1Zp5P0Mdxkk';
        $temp["openid"] = "$OpenID";

        $list = $user->where($temp)->find();//对象查询

        if ($list) {
            return true;
        } else {
            return false;
        }
    }
}