<?php
/**
 * Created by PhpStorm.
 * User: lawrance
 * Date: 16/9/23
 * Time: 下午3:15
 */
define("TOKEN", "scce");

valid(); //如果发来了echostr则进行验证

function valid()
{
    $echoStr = $_GET["echostr"];
    //valid signature , option
    if (checkSignature()) {
        echo $echoStr;
        exit;
    }
}

function checkSignature()
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
