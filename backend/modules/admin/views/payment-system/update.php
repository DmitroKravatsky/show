<?php

/** @var \yii\web\View $this */
/* @var \common\models\paymentSystem\PaymentSystem $paymentSystem */

$this->title = Yii::t('app', 'Update Payment System') . ': ' . $paymentSystem->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment systems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['paymentSystem' => $paymentSystem]) ?>
