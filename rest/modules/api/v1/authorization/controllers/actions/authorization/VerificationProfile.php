<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 02.03.18
 * Time: 22:16
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use rest\behaviors\ResponseBehavior;

class VerificationProfile extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * @return mixed
     */
    public function run()
    {
        /** @var RestUserEntity $model */
        $model = new $this->modelClass;
        $pofileVerification = $model->verifyUser(\Yii::$app->request->bodyParams);

        /** @var $result ResponseBehavior */
        $result = $this->controller;
        return $result->setResponse(201, 'Your profile has been verified');
    }
}