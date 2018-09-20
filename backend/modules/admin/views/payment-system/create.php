<?php

/** @var \yii\web\View $this */
/* @var \common\models\paymentSystem\PaymentSystem $paymentSystem */

$this->title = Yii::t('app', 'Create Payment System');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Systems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['paymentSystem' => $paymentSystem]) ?>

