<?php
/**
 * Created by PhpStorm.
 * User: lawrance
 * Date: 16/8/26
 * Time: 上午11:14
 */

namespace Home\Controller;

use Think\Controller;

class EmptyController extends Controller
{
    public function index()
    {
        $this->welcome();
    }

    public function welcome(){
        echo 'EmptyPage';
    }
}