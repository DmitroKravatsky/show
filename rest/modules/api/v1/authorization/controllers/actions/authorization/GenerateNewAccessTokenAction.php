<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 22.02.18
 * Time: 21:44
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;


use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use yii\web\NotFoundHttpException;

/**
 * Class GenerateNewAccessTokenAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class GenerateNewAccessTokenAction extends Action
{

    public function run()
    {
        $currentRefreshToken = \Yii::$app->getRequest()->getBodyParam('refresh_token');
        var_dump($currentRefreshToken); exit;

        $user = RestUserEntity::findOne(['refresh_token' => $currentRefreshToken]);
        if (!$user) {
            throw new NotFoundHttpException('Пользователь с таким токеном не найден.');
        }
    }
}