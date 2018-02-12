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
     * @return array
     */
    public function run(): array
    {
        /** @var UserProfileEntity $model */
        $model = new $this->modelClass;
        return $model->getProfile();
    }
}