<?php

use yii\db\Migration;

/**
 * Class m180823_123916_add_terms_conditions_to_review_table
 */
class m180823_123916_add_terms_conditions_to_review_table extends Migration
{
    private $tableName = '{{%review}}';
    public function up()
    {
        $this->addColumn($this->tableName, 'terms_condition', $this->smallInteger()->defaultValue(0)->after('text'));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'terms_condition');
    }

}
