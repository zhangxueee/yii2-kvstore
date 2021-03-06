<?php
/**
 * 键值存储
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    Hongbin Chen <hongbin.chen@aliyun.com>
 * @copyright 2006-2018 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-kvstore/licence.txt BSD Licence
 * @link      http://www.yiiplus.com
 */

namespace yiiplus\kvstore;

use Yii;

/**
 * Module
 *
 * @author Hongbin Chen <hongbin.chen@aliyun.com>
 * @since 2.0.0
 */
class Module extends \yii\base\Module
{
    // 控制器名称空间
    public $controllerNamespace = 'yiiplus\kvstore\controllers';

    // 源语言
    public $sourceLanguage = 'en';

    /**
     * 初始化
     * 
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * 注册翻译文件
     *
     * @return void
     */
    protected function registerTranslations()
    {
        Yii::$app->i18n->translations['yiiplus/kvstore'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => $this->sourceLanguage,
            'basePath' => '@yiiplus/kvstore/messages',
            'fileMap' => [
                'yiiplus/kvstore' => 'kvstore.php',
            ],
        ];
    }

    /**
     * 多语言翻译
     *
     * @param string  $message  消息
     * @param array   $params   参数
     * @param string  $language 语言
     * 
     * @return string 翻译结果
     */
    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('yiiplus/kvstore', $message, $params, $language);
    }
}
