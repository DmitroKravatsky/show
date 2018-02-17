<?php

use yii\db\Migration;
use rest\modules\api\v1\authorization\models\RestUserEntity;

/**
 * Class m180216_152039_fixture_for_user_guest
 */
class m180216_152039_fixture_for_user_guest extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->delete('{{%user}}');
        $this->delete('{{%auth_assignment}}');

        $this->insert('{{%user}}', [
            'id'                   => 1,
            'password'             => Yii::$app->security->generatePasswordHash(Yii::$app->params['guest-password']),
            'email'                => Yii::$app->params['guest-email'],
            'terms_condition'      => 1,
            'auth_key'             => Yii::$app->security->generateRandomString(32),
            'password_reset_token' => Yii::$app->security->generateRandomString() . '_' . time(),
            'created_at'           => time(),
            'updated_at'           => time()
        ]);

        $this->insert('{{%auth_item}}',
            [
                'name'        => 'guest',
                'type'        => 2,
                'description' => 'Guest Role',
                'created_at'  => time(),
                'updated_at'  => time()
            ]
        );

        $this->insert('{{%auth_assignment}}', [
            'item_name'  => 'guest',
            'user_id'    => 1,
            'created_at' => time()
        ]);

        $this->insert('{{%user_profile}}', [
            'user_id'            => 1,
            'name'               => 'guest',
            'last_name'          => 'guest',
            'created_at'         => time(),
            'updated_at'         => time()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('{{%user}}', ['email' => Yii::$app->params['guest-email']]);
    }
}
