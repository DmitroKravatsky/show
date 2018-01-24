<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\models\userProfile\UserProfileEntity;
use yii\rest\Action;

/**
 * Class GetProfileAction
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class GetProfileAction extends Action
{
    /**
     * @param $id
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        /** @var UserProfileEntity $model */
        $model = new $this->modelClass;

        return $model->getProfile($id);
    }
}