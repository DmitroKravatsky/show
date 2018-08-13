<?php

use yiister\gentelella\widgets\Panel;
use yii\widgets\DetailView;
use common\models\userNotifications\UserNotificationsEntity;

/** @var \yii\web\View $this */
/** @var \common\models\userNotifications\UserNotificationsEntity $notification */

$this->title = Yii::t('app', 'Notification') . ': ' . $notification->id;
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
                        'value' => function (UserNotificationsEntity $userNotification) {
                            return $userNotification->recipient->profile->name ?? null;
                        }
                    ],
                    'text:ntext',
                    'created_at:date',
                ],
            ]) ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
