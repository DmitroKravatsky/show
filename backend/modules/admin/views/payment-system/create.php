<?php

/** @var \yii\web\View $this */
/* @var \common\models\paymentSystem\PaymentSystem $paymentSystem */

$this->title = Yii::t('app', 'Create Payment System');
?>

<?= $this->render('_form', ['paymentSystem' => $paymentSystem]) ?>

