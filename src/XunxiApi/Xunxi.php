<?php
/**
 *  PHP SDK FOR XUNXI
 *  Class Xunxi(main)
 *  Please do not modify the contents of this document
 *
 *  Auth AIPAITEAM KIKI
 *  QQ_GROUP_NUM 542294882
 *  EMAIL 1452489466@qq.com
 *  CRETE_TIME 2017-08-04
 */
define('XUNXIAPI_ROOT_PATH', dirname(__FILE__));
require_once XUNXIAPI_ROOT_PATH . DIRECTORY_SEPARATOR . "Common" . DIRECTORY_SEPARATOR . "Error.php";

class Xunxi
{
    const MOUDLE_DIR_PATH = XUNXIAPI_ROOT_PATH . DIRECTORY_SEPARATOR . "Module" . DIRECTORY_SEPARATOR;
    static private $MODULE_TEMPLATE_CONTENTS = "";
    static protected $_config = [];
    static protected $ERROR = null;

    const MODULE_API_NEW = 'api_new';
    const MODULE_GETTIMES = 'gettimes';
    const MODULE_STATISTICS = 'statistics';
    const MODULE_TIMEIP = 'timeip';
    const MODULE_TOKEN = 'token';

    public function __construct()
    {
//        self::_updateModule(); //目前为静态调用 仅用于丢失Module文件夹时使用 默认为关闭状态

    }

    static public function run($module, $config, $isShowExplain = false)
    {
        require_once XUNXIAPI_ROOT_PATH . DIRECTORY_SEPARATOR . "Common" . DIRECTORY_SEPARATOR . "Request.php";
        self::$_config = $config;
        if ($module != self::MODULE_API_NEW) {

            // TODO DEAL GET TOKEN FUNCTION
            $requestToekn = new XunxiApi_Common_Request("token", self::$_config);
            $data = $requestToekn::_send(false);
            $jd = json_decode($data);
            if ($jd->code == "000001") {
                self::$_config["key"] = $jd->token;
            }
        }

        $request = new XunxiApi_Common_Request($module, self::$_config);
        return $request::_send($isShowExplain);
    }

    static private function _updateModule()
    {
        // TODO 目前此方法是静态调用的 下个版本会改为官网动态调用
        $arr = [
            "api_new" => [
                "url" => "http://www.uvsii.cn/api/api_new.php",
                "param" => [
                    "sid", "apikey", "json"
                ]
            ],
            "token" => [
                "url" => "http://www.uvsii.cn/api/token.php",
                "param" => [
                    "user", "sid", "apikey"
                ]
            ],
            "statistics" => [
                "url" => "http://www.uvsii.cn/xunxi/statistics.php",
                "param" => [
                    "sid", "timestr", "key"
                ]
            ],
            "gettimes" => [
                "url" => "http://www.uvsii.cn/main/gettimes.php",
                "param" => [
                    "sid", "timestr", "key", "type"
                ]
            ],
            "timeip" => [
                "url" => "http://www.uvsii.cn/xunxi/timeip.php",
                "param" => [
                    "sid", "timestr", "key"
                ]
            ]
        ];

        foreach ($arr as $k => $v) {
            $moduleName = self::MOUDLE_DIR_PATH . ucfirst(strtolower($k)) . ".php";
            if (!is_file($moduleName)) {
                self::_createModule($moduleName, self::_dealModuleContents($k, $v));
            }
        }
        return true;
    }

    static function _createModule($filename, $content)
    {
        $fp = fopen($filename, "w+");
        if (flock($fp, LOCK_EX)) { // 进行排它型锁定
            $status = fwrite($fp, $content);
            flock($fp, LOCK_UN); // 释放锁定
        } else {
            return false;
        }
        fclose($fp);
        return $status;
    }

    static function _dealModuleContents($moduleName, Array $config)
    {
        $params = $config["param"];
        $param = "";
        for ($i = 0; $i < count($params); $i++) {
            $param .= '"' . $params[$i] . '"' . ($i < count($params) - 1 ? "," : "");
        }

        date_default_timezone_set("PRC");
        $Template = self::_getTemplateModuleContents();
        $tempStr = str_replace('#Module_name', ucfirst($moduleName), $Template);
        $tempStr = str_replace('#Time', date("Y-m-d H:i:s"), $tempStr);
        $tempStr = str_replace('#url', $config["url"], $tempStr);
        $tempStr = str_replace('#param', $param, $tempStr);
        return $tempStr;
    }

    static function _getTemplateModuleContents()
    {
        $fileName = XUNXIAPI_ROOT_PATH . DIRECTORY_SEPARATOR . "Module" . DIRECTORY_SEPARATOR . "Template.txt";
        if (!self::$MODULE_TEMPLATE_CONTENTS) {
            self::$MODULE_TEMPLATE_CONTENTS = file_get_contents($fileName);
        }
        return self::$MODULE_TEMPLATE_CONTENTS;
    }

}