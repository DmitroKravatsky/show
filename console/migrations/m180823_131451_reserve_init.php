<?php

use yii\db\Migration;
use common\models\reserve\ReserveEntity;

/**
 * Class m180823_131451_reserve_init
 */
class m180823_131451_reserve_init extends Migration
{
    private $tableName = '{{%reserve}}';

    public function up()
    {
        $this->batchInsert($this->tableName, ['payment_system', 'currency', 'sum', 'created_at', 'updated_at'], [
            [ReserveEntity::YANDEX_MONEY, ReserveEntity::RUB, 0, time(), time()],
            [ReserveEntity::PRIVAT24, ReserveEntity::UAH, 0, time(), time()],
            [ReserveEntity::SBERBANK, ReserveEntity::RUB, 0, time(), time()],
            [ReserveEntity::QIWI, ReserveEntity::RUB, 0, time(), time()],
            [ReserveEntity::QIWI, ReserveEntity::USD, 0, time(), time()],
            [ReserveEntity::WEB_MONEY, ReserveEntity::RUB, 0, time(), time()],
            [ReserveEntity::WEB_MONEY, ReserveEntity::USD, 0, time(), time()],
            [ReserveEntity::WEB_MONEY, ReserveEntity::UAH, 0, time(), time()],
            [ReserveEntity::WEB_MONEY, ReserveEntity::EUR, 0, time(), time()],
            [ReserveEntity::TINCOFF, ReserveEntity::RUB, 0, time(), time()],
        ]);
    }

    public function down()
    {
        $this->truncateTable($this->tableName);
    }
}
