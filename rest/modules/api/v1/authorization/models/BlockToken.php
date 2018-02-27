<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 22.02.18
 * Time: 22:24
 */

namespace rest\modules\api\v1\authorization\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class BlockToken
 * @package rest\modules\api\v1\authorization\models
 * @property integer $id
 * @property string $token
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $expired_at
 */
class BlockToken extends ActiveRecord
{
    const SCENARIO_CREATE_BLOCK = 'create';
    /**
     * @return string
     */

    /**
     * @inheritdoc
     */
    public $primaryKey = 'token';

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id',
            'token',
            'user_id',
            'created_at',
            'expired_at',
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE_BLOCK] = [
            'id',
            'token',
            'user_id',
            'created_at',
            'expired_at',
        ];

        return $scenarios;
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%block_token}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
                'value'              => function () {
                    return time();
                },
            ],
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['user_id', 'token', 'expired_at'], 'safe'],
            [['token'], 'string', 'max' => 255],
            [['expired_at'], 'integer'],
            [['user_id', 'token', 'expired_at'], 'required', 'on' => self::SCENARIO_CREATE_BLOCK],
        ];
    }

}