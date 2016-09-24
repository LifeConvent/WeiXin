<?php

if (version_compare(PHP_VERSION, '5.3.0', '<')) die('require PHP > 5.3.0 !');
//开启调试模式，开发阶段开启，部署时关闭
define('APP_DEBUG', True);
//定义应用目录
define('APP_PATH', './Application/');
//引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

//define your token
define("TOKEN", "scce");
traceHttp();
$wechatObj = new wechatCallbackapiTest();

if ($_GET['echostr']) {
    $wechatObj->valid(); //如果发来了echostr则进行验证
} else {
    $wechatObj->responseMsg(); //如果没有echostr，则返回消息
}


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
//        get post data, May be due to the different environments
//        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
//        取得XML数据包信息
        $postStr = file_get_contents('php://input');

        //extract post data
        if (!empty($postStr)) {
            //启动安全防御
            libxml_disable_entity_loader(true);
            //解析XML数据包
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);

            $time = time();
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";

            if ($postObj->Content == 'message') {
                $newsArray = array();
                $newsArray[] = array("Title" => "多图文1标题", "Description" => "简单的描述", "PicUrl" => 'http://'.$_SERVER['HTTP_HOST']."/WeiXin/image/egg.png", "Url" => $_SERVER['HTTP_HOST']."/WeiXin/html/login.html");
                $newsArray[] = array("Title" => "多图文2标题", "Description" => "", "PicUrl" => "http://d.hiphotos.bdimg.com/wisegame/pic/item/f3529822720e0cf3ac9f1ada0846f21fbe09aaa3.jpg", "Url" => "http://m.cnblogs.com/?u=txw1958");
                $newsArray[] = array("Title" => "多图文3标题", "Description" => "", "PicUrl" => "http://g.hiphotos.bdimg.com/wisegame/pic/item/18cb0a46f21fbe090d338acc6a600c338644adfd.jpg", "Url" => "http://m.cnblogs.com/?u=txw1958");
                echo transmitNews($postObj, $newsArray);
                exit();
            }


            if ($postObj->MsgType == 'event') { //如果XML信息里消息类型为event
                if ($postObj->Event == 'subscribe') { //如果是订阅事件
                    $msgType = "text";
                    $contentStr = "欢迎订阅\n微信公众平台";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    logger($resultStr);
                    echo $resultStr;
                    exit();
                }
            }

            if (!empty($keyword)) {
                $msgType = "text";
                $contentStr = '';
                $data = getUserInfo($keyword);
                if ($data['code']==1) {
                    $count = count($data['data']);
                    $newsArray = array();
                    $newsArray[] = array("Title" => $keyword."的成绩查询结果", "Description" => "成绩查询结果", "PicUrl" => 'http://'.$_SERVER['HTTP_HOST']."/WeiXin/image/sample.jpg", "Url" => $_SERVER['HTTP_HOST']."/WeiXin/index.php/Home/Grade/deal?stu_num=".$keyword."&row=".$count);
                    echo transmitNews($postObj, $newsArray);
                    exit();
                } else {
                    $contentStr.= '若需查询成绩,请输入学号进行查询!';
                }
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
                exit();
            } else {
                echo "";
            }

        } else {
            echo "";
            exit();
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

//回复图文消息
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


function traceHttp()
{
    logger("REMOTE_ADDR:" . $_SERVER["REMOTE_ADDR"] . ((strpos($_SERVER["REMOTE_ADDR"], "101.226")) ? "From WeiXin" : "Unkonow IP"));
    logger("QUERY_STRING:" . $_SERVER["QUERY_STRING"]);
}

function logger($content)
{
    file_put_contents("log.html", date('Y-m-d H:i:s  ') . $content . "<br>", FILE_APPEND);
}

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
