<?php

use yii\db\Migration;
use common\models\user\User;
use common\models\bid\BidEntity;

/**
 * Class m180820_073157_bid_fixture
 */
class m180820_073157_bid_fixture extends Migration
{
    private $tableName = '{{%bid}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $faker = Faker\Factory::create();

        $bidData = [];

        $i = 0;
        while ($i < 15) {
            $fromSum = $faker->numberBetween(100, 10000);
            $toSum = $fromSum * 27;
            $bidData[] = [
                'created_by'          => User::DEFAULT_GUEST_ID,
                'name'                => $faker->firstNameMale,
                'last_name'           => $faker->lastName,
                'email'               => $faker->email,
                'phone_number'        => '+797889' . rand(10000, 99999),
                'status'              => BidEntity::STATUS_DONE,
                'from_payment_system' => BidEntity::WEB_MONEY,
                'to_payment_system'   => BidEntity::PRIVAT24,
                'from_wallet'         => $faker->creditCardNumber,
                'to_wallet'           => $faker->creditCardNumber,
                'from_currency'       => BidEntity::USD,
                'to_currency'         => BidEntity::UAH,
                'from_sum'            => $fromSum,
                'to_sum'              => $toSum,
                'created_at'          => time(),
                'updated_at'          => time()
            ];

            $i++;
        }

        $attributes = [
            'created_by',
            'name',
            'last_name',
            'email',
            'phone_number',
            'status',
            'from_payment_system',
            'to_payment_system',
            'from_wallet',
            'to_wallet',
            'from_currency',
            'to_currency',
            'from_sum',
            'to_sum',
            'created_at',
            'updated_at'
        ];

        $this->batchInsert($this->tableName, $attributes, $bidData);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180820_073157_bid_fixture cannot be reverted.\n";

        return false;
    }
}
