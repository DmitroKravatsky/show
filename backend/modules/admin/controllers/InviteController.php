<?php

namespace backend\modules\admin\controllers;

use yii\web\Controller;
use backend\modules\admin\controllers\actions\invite\DestroyAction;

class InviteController extends Controller
{
    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'destroy'  => [
                'class' => DestroyAction::class
            ],
        ];
    }
}
