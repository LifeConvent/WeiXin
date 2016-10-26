<?php
/**
 * Created by PhpStorm.
 * User: lawrance
 * Date: 16/10/26
 * Time: 下午1:50
 */

namespace Home\Controller;

use Think\Controller;
use Think\Model;

class GroupSendController extends Controller
{
    /**
     * @function 用户群发消息接口
     */
    public function allSendNews(){
        $upMenu = new MenuController();
        $access_token = $upMenu->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$access_token;
        $dataClass = new DataController();
        $result = $upMenu->https_request($url, $dataClass->news_allSend);
        $data = new \stdClass();
        dump($result);


        $data = json_decode($result);
    }
}