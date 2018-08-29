<?php

use yiister\gentelella\widgets\Panel;
use yii\widgets\DetailView;
use common\models\userNotifications\UserNotificationsEntity as Notification;

/** @var \yii\web\View $this */
/** @var Notification $notification */

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
                'collapsable' => true,
                'removable' => true,
            ]) ?>
            <?= DetailView::widget([
                'model' => $notification,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'recipient_id',
                        'value' => function (Notification $notification) {
                            return $notification->recipient->profile->name ?? null;
                        }
                    ],
                    [
                        'attribute' => 'text',
                        'value' => function (Notification $notification) {
                            if ($notification->type = Notification::TYPE_NEW_USER) {
                                return Yii::t('app', $notification->text, [
                                    'phone_number' => $notification->custom_data->phone_number ?? null
                                ]);
                            }
                            return null;
                        }
                    ],
                    'created_at:date',
                ],
            ]) ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
