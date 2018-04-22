<?php

use yii\db\Migration;

/**
 * Class m180411_050148_add_basic_admin
 */
class m180411_050148_add_basic_admin extends Migration
{
    public function up()
    {
        $this->insert('{{%user}}', [
            'id'                    => 2,
            'auth_key'              => Yii::$app->security->generateRandomString(),
            'refresh_token'         => Yii::$app->security->generateRandomString(),
            'created_refresh_token' => time(),
            'password'              => Yii::$app->security->generatePasswordHash('admin01'),
            'password_reset_token'  => Yii::$app->security->generateRandomString() . '_' . time(),
            'phone_number'          => '+380939757500',
            'status'                => \rest\modules\api\v1\authorization\models\RestUserEntity::STATUS_VERIFIED,
            'created_at'            => time(),
            'updated_at'            => time()
        ]);

        $this->insert('{{%auth_item}}',
            [
                'name' => 'admin',
                'type' => 1,
                'description' => 'Admin Role',
                'created_at' => time(),
                'updated_at' => time()
            ]
        );

        $this->insert('{{%auth_assignment}}', [
            'item_name' => 'admin',
            'user_id'   => 2
        ]);

    }

        public function down()
    {
        $this->delete('{{%auth_assignment}}', ['user_id' => 2]);
        $this->delete('{{%user}}', ['id' => 2]);
        $this->delete('{{%auth_item}}', ['name' => 'admin']);
    }

}
