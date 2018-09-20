<?php

use common\models\reserve\ReserveEntity;

/** @var \yii\web\View $this */
/* @var ReserveEntity $reserve */

$this->title = Yii::t('app', 'Create Reserve');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reserves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['reserve' => $reserve]) ?>
