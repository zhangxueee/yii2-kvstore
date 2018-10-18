<?php

namespace yiiplus\kvstore\models;

use Yii;
use yii\helpers\Json;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;
use yiiplus\kvstore\Module;

class Kvstore extends ActiveRecord implements KvstoreInterface
{
    public static function tableName()
    {
        return '{{%yp_kvstore}}';
    }

    public function rules()
    {
        return [
            [['value'], 'string'],
            [['group', 'key', 'description'], 'string', 'max' => 255],
            [
                ['key'],
                'unique',
                'targetAttribute' => ['group', 'key'],
            ],
            [['created_at', 'updated_at'], 'safe'],
            [['active'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'          => Module::t('ID'),
            'group'       => Module::t('Group'),
            'key'         => Module::t('Key'),
            'value'       => Module::t('Value'),
            'description' => Module::t('Description'),
            'active'      => Module::t('Active'),
            'created_at'  => Module::t('CreatedAt'),
            'updated_at'  => Module::t('UpdatedAt'),
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getkvstore($group, $key)
    {
        $data = static::find()->select('value')->where(['group' => $group, 'key' => $key, 'active' => true])->limit(1)->one();
        if($data) {
            return $data->value;
        }
        return false;
    }

    public function setKvstore($group, $key, $value)
    {
        $model = static::findOne(['group' => $group, 'key' => $key]);

        if ($model === null) {
            $model = new static();
            $model->active = 1;
        }
        $model->group = $group;
        $model->key = $key;
        $model->value = strval($value);

        return $model->save();
    }

    public function activateKvstore($group, $key)
    {
        $model = static::findOne(['group' => $group, 'key' => $key]);

        if ($model && $model->active == 0) {
            $model->active = 1;
            return $model->save();
        }
        return false;
    }

    public function deactivateKvstore($group, $key)
    {
        $model = static::findOne(['group' => $group, 'key' => $key]);

        if ($model && $model->active == 1) {
            $model->active = 0;
            return $model->save();
        }
        return false;
    }

    public function deleteKvstore($group, $key)
    {
        $model = static::findOne(['group' => $group, 'key' => $key]);

        if ($model) {
            return $model->delete();
        }
        return true;
    }
}
