<?php
/**
 * Created by PhpStorm.
 * User: lawrance
 * Date: 16/10/25
 * Time: 下午2:05
 */

namespace Home\Controller;

use Think\Controller;
use Think\Model;


class MenuController extends Controller
{
    public $appid = "wx2c307a1875247a02";
    public $appsecret = "25cd61683a5b7282524f976cee2b0da9";
    public $appidTest = 'wx9753e9fbf21cbc59';
    public $appsecretTest = 'dcb93846b0a57b0c88ba1837613ed04c';
    public $jsonmenu = '{
          "button":[
          {
                "name":"天气预报",
               "sub_button":[
                {
                   "type":"click",
                   "name":"北京天气",
                   "key":"menu_weather_beijing"
                },
                {
                   "type":"click",
                   "name":"上海天气",
                   "key":"menu_weather_shanghai"
                },
                {
                   "type":"click",
                   "name":"广州天气",
                   "key":"menu_weather_guangzhou"
                },
                {
                   "type":"click",
                   "name":"深圳天气",
                   "key":"menu_weather_shenzhen"
                },
                {
                    "type":"view",
                    "name":"本地天气",
                    "url":"http://m.hao123.com/a/tianqi"
                }]


           },
           {
               "name":"附加功能",
               "sub_button":[
                {
                   "type":"view",
                   "name":"模版样例",
                   "url":"http://203.195.235.76/jssdk/"
                },
                {
                   "type":"click",
                   "name":"公司简介",
                   "key":"menu_company_detail"
                },
                {
                   "type":"click",
                   "name":"趣味游戏",
                   "key":"menu_game_fun"
                },
                {
                    "type":"click",
                    "name":"讲个笑话",
                    "key":"menu_joke"
                }]


           }]
        }';
    public $url = '';

    public function getAccessToken(){
        $upMenu = new MenuController();
        $upMenu->url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$upMenu->appidTest&secret=$upMenu->appsecretTest";
        $output = $this->https_request($upMenu->url);
        $jsoninfo = json_decode($output, true);
        return $jsoninfo["access_token"];
    }

    public function upMenuList()
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $access_token;
        $result = $this->https_request($url, $this->jsonmenu);
        //对返回结果的处理









        var_dump($result);
    }

    public function https_request($url, $data = null)
    {
        $curl = curl_init();
        if (class_exists ( '/CURLFile' )) {//php5.5跟php5.6中的CURLOPT_SAFE_UPLOAD的默认值不同
            curl_setopt ( $curl, CURLOPT_SAFE_UPLOAD, true );
        } else {
            if (defined ( 'CURLOPT_SAFE_UPLOAD' )) {
                curl_setopt ( $curl, CURLOPT_SAFE_UPLOAD, false );
            }
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}


