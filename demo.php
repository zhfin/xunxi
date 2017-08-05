<?php

require_once "./src/XunxiApi/Xunxi.php";

$query = [
    "user" => "",// 迅析用户名
    "sid" => "", // 迅析APP SID
    "apikey" => "",// 开放api_key 可在个人中心查询
    "json" => 1,// 不需要改变
    "type" => "update",// 不需要改变(开发者只能传入update值,否则将报错)
];

/**
 * run
 * @param $module 方法名 均可用MODULE_NAME常量来调用
 * @param $query
 * @param $isShowExplain 是否显示中文解释 默认为false(不建议开启,仅可在调试时使用)
 * @return String json || Explain
 */
$xunxi = new Xunxi();
echo $xunxi::run($xunxi::MODULE_TIMEIP,$query);
