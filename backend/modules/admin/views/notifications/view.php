<?php

use common\models\userNotifications\NotificationsEntity;
use yiister\gentelella\widgets\Panel;
use yii\widgets\DetailView;

/** @var \yii\web\View $this */
/** @var NotificationsEntity $notification */

$this->title = Yii::t('app', 'Notification') . ': ' . $notification->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs']['title'] = $this->title;
?>

<div class="notification-view">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">
        </label>
        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Notification'),
            ]) ?>
            <?= DetailView::widget([
                'model' => $notification,
                'attributes' => [
                    [
                        'attribute' => 'text',
                        'value' => function (NotificationsEntity $notification) {
                            return Yii::t('app', $notification->text, [
                                'full_name'=> $notification->custom_data->full_name ?? null,
                                'sum'      => $notification->custom_data->sum ?? null,
                                'currency' => $notification->custom_data->currency ?? null,
                                'wallet'   => $notification->custom_data->wallet ?? null,
                                'phone_number' => $notification->custom_data->phone_number ?? null,
                            ]);
                        }
                    ],
                    'created_at:date',
                ],
            ]) ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
