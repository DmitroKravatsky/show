<?php
    /* @var $this yii\web\View */
    /* @var $user common\models\user\UserEntity */
?>
<div class="recovery-password">

    <p> Пользователь <?= $id ?> <?= $last_name ?> <?= $name ?> </p>
    <p> Email : <?= $email ?> </p>
    <p> Номер телефона : <?= $phone_number ?>.</p>
    <p> Создал новую заявку с параметрами:</p>
    <p> Сумма отправки: <?= $from_sum ?> </p>
    <p> Сумма получения: <?= $to_sum ?> </p>
    <p> Со счета; <?= $from_wallet ?> </p>
    <p> На счет: <?= $to_wallet ?> </p>
    <p> Из платежной системы: <?= $from_payment_system ?> </p>
    <p> На платежную систему: <?= $to_payment_system ?> </p>
    <p> Из валюты: <?= $from_currency ?> </p>
    <p> В валюту: <?= $to_currency ?> </p>
</div>
