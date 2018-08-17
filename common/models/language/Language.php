<?php

namespace common\models\language;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "language".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $visible
 */
class Language extends ActiveRecord
{
    const VISIBLE_NO = 0;
    const VISIBLE_YES = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visible'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'visible' => Yii::t('app', 'Visible'),
        ];
    }

    /**
     * @return array
     */
    public static function getVisibleList()
    {
        return static::find()
            ->select(['name', 'code'])
            ->indexBy('code')
            ->where(['visible' => self::VISIBLE_YES])
            ->column();
    }
}
