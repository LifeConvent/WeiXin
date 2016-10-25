<?php
/**
 * Created by PhpStorm.
 * User: lawrance
 * Date: 16/10/25
 * Time: 下午1:40
 */

namespace Home\Controller;

use Think\Controller;
use Think\Model;

class UploaderController extends Controller
{
    public function upLoaderImage(){
        $upMenu = new MenuController();
        $access_token = $upMenu->getAccessToken();
        //文件类型问题





        $filename = $_GET['file'].'.jpg';
        $type = 'image';
        //路径设置
        $filepath = dirname ( dirname ( dirname ( dirname ( __FILE__ )))) . '/Public/image/'.$filename;
        //关键是判断curlfile,官网推荐php5.5或更高的版本使用curlfile来实例文件
        if (class_exists ( '\CURLFile' )) {
            $filedata = array (
                'fieldname' => new \CURLFile ( realpath ( $filepath ), 'image/jpeg' )
            );
        } else {
            $filedata = array (
                'fieldname' => '@' . realpath ( $filepath )
            );
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type='.$type;
        $result = $upMenu->https_request($url,$filedata);
        //对结果的处理  media_id与对应的文件名及文件信息相匹配







        var_dump($result);
    }
}
