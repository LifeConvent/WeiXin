<?php
/**
 * Created by PhpStorm.
 * User: lawrance
 * Date: 16/10/20
 * Time: 下午3:15
 */

namespace Home\Controller;

use Think\Controller;
use Think\Model;

define("TOKEN", "scce");

//traceHttp();

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
//        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
//        取得XML数据包信息
        $postStr = file_get_contents('php://input');

        //extract post data
        if (!empty($postStr)) {
            //启动安全防御
            libxml_disable_entity_loader(true);
            //解析XML数据包
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            echo catchEvent($postObj);
        }
    }

    public function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * @function 处理微发来的消息时间
 * @param $object
 * @return string
 */
function catchEvent($object)
{
    //对象为空时要向微信返回空字符串
    if (empty($object))
        return '';
    switch ($object->MsgType) {
        case 'text': {
            $keyword = trim($object->Content);
            $data = getUserInfo($keyword);
            if ($data['code'] == 1) {
                $newsArray = array();
                $newsArray[] = array("Title" => $keyword . "的成绩查询结果", "Description" => "成绩查询结果", "PicUrl" => 'http://' . $_SERVER['HTTP_HOST'] . "/WeiXin/Public/image/sample.jpg", "Url" => $_SERVER['HTTP_HOST'] . "/WeiXin/index.php/Home/Grade/deal?stu_num=" . $keyword);
                echo transmitNews($object, $newsArray);
                exit();
            } else if (strstr($keyword, '课程评价+')) {
                $stu_num = substr($keyword, 13, 8);
                $data = getCourseInfo($stu_num);
                if ($data['code'] == 1) {
                    $newsArray = array();
                    $newsArray[] = array("Title" => "课程评价", "Description" => "课程评价", "PicUrl" => 'http://' . $_SERVER['HTTP_HOST'] . "/WeiXin/Public/image/sample.jpg", "Url" => $_SERVER['HTTP_HOST'] . "/WeiXin/index.php/Home/CourseEvaluation/getCourse?stu_num=" . $stu_num);
                    echo transmitNews($object, $newsArray);
                    exit();
                } else {
                    $contentStr = '请输入正确学好进行查询!';
                    echo transmitText($object, $contentStr);
                    exit();
                }
            } else {
                $contentStr = '1.输入"学号"查询成绩                2.输入"课程评价+学号"进行课程评价';
                echo transmitText($object, $contentStr);
                exit();
            }
            break;
        }
        case 'event': {
            switch ($object->Event) {
                case 'subscribe': {
                    echo transmitText($object, "欢迎订阅\n微信公众平台");
                    exit();
                    break;
                }
                case 'CLICK': {
                    $key = $object->EventKey;
                    if (checkMenuKey($key)) {
                        //处理菜单点击事件
                        $contentStr = switchMenuKey($key);
                        echo transmitText($object, actionByMenuKey($contentStr));
                        exit();
                    }
                }
            }
            break;
        }
        default:
            echo '';
            break;
    }
}

/**
 * @function 回复图文消息
 * @param $object
 * @param $newsArray
 * @return string
 */
function transmitNews($object, $newsArray)
{
    if (!is_array($newsArray)) {
        return '';
    }
    $itemTpl = "<item>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                </item>";
    $item_str = "";
    foreach ($newsArray as $item) {
        $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
    }
    $xmlTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>%s</ArticleCount>
                    <Articles>
                        $item_str
                    </Articles>
               </xml>";

    $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
    return $result;
}

/**
 * @function 回复文字消息
 * @param $object
 * @param $contentStr
 * @return string
 */
function transmitText($object, $contentStr)
{
    $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
    return sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), 'text', $contentStr);
}

function traceHttp()
{
    logger("REMOTE_ADDR:" . $_SERVER["REMOTE_ADDR"] . ((strpos($_SERVER["REMOTE_ADDR"], "101.226")) ? "From WeiXin" : "Unkonow IP"));
    logger("QUERY_STRING:" . $_SERVER["QUERY_STRING"]);
}

function logger($content)
{
    file_put_contents("log.html", date('Y-m-d H:i:s  ') . $content . "<br>", FILE_APPEND);
}

/**
 * @function 拆分菜单编码前缀
 * @param $key
 * @return bool
 */
function checkMenuKey($key)
{
    if (substr($key, 0, strlen('menu_')) === 'menu_')
        return true;
    else
        return false;
}

/**
 * @function 匹配菜单编码事件
 * @param $key
 * @return string
 */
function actionByMenuKey($key){
    $contentStr = '';
    switch (substr($key, 0, 7)) {
        case 'weather':
            $contentStr = '天气';
            break;
        case 'company':
            $contentStr = '公司简介';
            break;
        case 'game_fu':
            $contentStr = '游戏';
            break;
    }
    return $contentStr;
}

/**
 * @function 拆分菜单编码
 * @param $key
 * @return string
 */
function switchMenuKey($key)
{
    $message = substr($key, 5);
    return $message;
}

/**
 * @function 查询用户是否存在
 * @param $stu_num
 * @return array
 */
function getUserInfo($stu_num)
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

/**
 * @function 查询课程信息
 * @param $stu_num
 * @return array
 */
function getCourseInfo($stu_num)
{
//    $mysqlli = new mysqli('localhost','root','123456','course_evaluation');
//    $result = $mysqlli->query('select * from course_info_personal where stu_num ='.$stu_num);
    $user = M('course_info_personal', '', 'mysql://root:123456@localhost:3306/course_evaluation');//实例化user_info表模型对象
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

class AccessController extends Controller
{
    public function access()
    {
        $wechatObj = new wechatCallbackapiTest();
        if ($_GET['echostr']) {
            $wechatObj->valid(); //如果发来了echostr则进行验证
        } else {
            $wechatObj->responseMsg(); //如果没有echostr，则返回消息
        }
    }
}

