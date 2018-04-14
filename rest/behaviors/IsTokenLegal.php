<?php

namespace rest\behaviors;

use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\base\Behavior;
use yii\web\UnauthorizedHttpException;

/**
 * Class ValidationExceptionFirstMessage
 * @package rest\behaviors
 */
class IsTokenLegal extends Behavior
{
    /**
     * Method of estimating token status
     * @return mixed
     * @throws UnauthorizedHttpException
     */
    public function validateToken()
    {
        $model = new RestUserEntity();
        if ($model->isAlreadyBlocked()) {
            throw new UnauthorizedHttpException('Token is blocked');
        }
        return true;

    }

}