<?php
/**
 * Created by PhpStorm.
 * User: lawrance
 * Date: 16/9/23
 * Time: 下午3:15
 */
if(strstr('survey','yqh_'))
echo 1;
if (checkMenuKey('menu_weather_beijing')) {
    $contentStr = switchMenuKey('menu_weather_beijing');
    $msgType = "text";
    echo $contentStr;
    exit();
}

function checkMenuKey($key)
{
    if (substr($key, 0, strlen('menu_')) === 'menu_')
        return true;
    else
        return false;
}

function switchMenuKey($key)
{
    $message = substr($key, 5);
    return $message;
}