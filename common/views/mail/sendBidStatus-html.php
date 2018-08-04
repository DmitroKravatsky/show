<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\user\User */

?>
<div class="recovery-password">
    <p>Уважаемый клиент <?= Html::encode($email) ?>,</p>

    <p>Ваша заявка номер <?= $id ?> теперь в статусе
        <?= $status ?> </p>
</div>
