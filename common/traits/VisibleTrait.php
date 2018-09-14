<?php

namespace common\traits;

use common\interfaces\IVisible;
use Yii;
use yii\helpers\ArrayHelper;

trait VisibleTrait
{
    public static function getVisibleStatuses(): array
    {
        return [
            IVisible::VISIBLE_NO => Yii::t('app', 'No'),
            IVisible::VISIBLE_YES => Yii::t('app', 'Yes'),
        ];
    }

    public static function getVisibleValue($status): string
    {
        return ArrayHelper::getValue(static::getVisibleStatuses(), $status);
    }
}
