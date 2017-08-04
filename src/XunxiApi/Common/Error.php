<?php
/**
 *  PHP SDK FOR XUNXI
 *  Class XunxiApi_Common_Error
 *  Please do not modify the contents of this document
 *
 *  Auth AIPAITEAM KIKI
 *  QQ_GROUP_NUM 542294882
 *  EMAIL 1452489466@qq.com
 *  CREATE_TIME 2017-08-04
 */

class XunxiApi_Common_Error
{
    public static $errorCode = [
        "api_new" => [
            "000002" => "TYPE为空",
            "000003" => "APIKEY错误",
            "000004" => "APIKEY为空",
            "000005" => "SID为空",
        ],
        "token" => [
            "000001" => "获取token成功",
            "000002" => "APIKEY错误",
            "000003" => "SID为空",
            "000004" => "USER为空",
        ],
        "statistics" => [
            "000001" => "统计成功",
            "000002" => "KEY（token）过期",
            "000003" => "KEY（token）不正确",
            "000004" => "TIMESTR为空",
            "000005" => "SID为空",
        ],
        "gettimes" => [
            "000001" => "统计成功",
            "000002" => "TYPE为空",
            "000003" => "KEY（token）过期",
            "000004" => "KEY（token）不正确",
            "000005" => "TIMESTR为空",
            "000006" => "KEY（token）为空",
            "000007" => "SID为空",
        ],
        "timeip" => [
            "000001" => "统计成功",
            "000002" => "KEY（token）过期",
            "000003" => "KEY（token）不正确",
            "000004" => "TIMESTR为空",
            "000005" => "SID为空",
        ]
    ];

    static public function getError($module, $data)
    {
        // TODO BUG clear &#65279;
        $clearData = self::clearSpace($data, ["﻿"]);
        if (self::isModule($module)) {
            if (self::isJson($clearData)) {
                $jd = json_decode($clearData);
                if (isset($jd->code)) {
                    if (isset(self::$errorCode[$module][$jd->code])) {
                        return self::$errorCode[$module][$jd->code];
                    }
                }
            }
        }

        return $clearData;
    }

    static private function isModule($module)
    {
        return isset(self::$errorCode[$module]);
    }

    static private function isJson($str)
    {
        return @is_null(json_decode($str)) ? false : true;
    }

    // TODO &#65279; bom
    static public function clearSpace($str, Array $search = [])
    {
        if (!$search) {
            return str_replace(' ', '', $str);
        } else {
            for ($i = 0; $i < count($search); $i++) {
                $str = str_replace($search[$i], '', $str);
            }
            return $str;
        }
    }
}