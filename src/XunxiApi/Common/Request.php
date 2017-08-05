<?php
/**
 *  PHP SDK FOR XUNXI
 *  Class XunxiApi_Common_Request
 *  Please do not modify the contents of this document
 *
 *  Auth AIPAITEAM KIKI
 *  QQ_GROUP_NUM 542294882
 *  EMAIL 1452489466@qq.com
 *  CRETE_TIME 2017-08-04
 */

class XunxiApi_Common_Request
{
    protected static $moduleDir = XUNXIAPI_ROOT_PATH . DIRECTORY_SEPARATOR . "Module" . DIRECTORY_SEPARATOR;
    protected static $_requestUrl = "";
    protected static $_requestData = [];
    protected static $_timeOut = 10;
    private static $moduleName = null;

    public function __construct($module, Array $config)
    {

        $moduleClass = self::autoLoad($module);
        self::$_requestUrl = $moduleClass->_url;
        self::paramBuild($config, $moduleClass->_param);
        require_once dirname(self::$moduleDir) . DIRECTORY_SEPARATOR . "Common/Error.php";
    }

    static private function autoLoad($module)
    {
        $modulePath = self::$moduleDir . ucfirst(strtolower($module)) . ".php";
        if (is_file($modulePath)) {
            self::$moduleName = $module;
            require_once $modulePath;
            $className = "XunxiApi_Module_" . ucfirst(strtolower($module));
            $moduleClass = new $className();
            return $moduleClass;
        } else {
            return null;
        }

    }

    /**
     * @param bool $isShowExplain 是否显示中文解释,默认false
     * @return mixed
     */
    static public function _send($isShowExplain = false)
    {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::$_requestUrl);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, self::$_requestData);
        if (false !== strpos(self::$_requestUrl, "https")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $data = curl_exec($curl);
        curl_close($curl);
        if (!$isShowExplain) {
            return XunxiApi_Common_Error::clearSpace($data, ["﻿"]);
        } else {
            return XunxiApi_Common_Error::getError(self::$moduleName, $data);
        }
    }

    static function paramBuild($allParam, $needParams)
    {
        self::$_requestData = [];
        for ($i = 0; $i < count($needParams); $i++) {
            $needParam = $needParams[$i];
            self::$_requestData[$needParam] = $allParam[$needParam];
        }
    }
}