<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 22.02.18
 * Time: 21:44
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use Yii;
use yii\rest\Action;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class GenerateNewAccessTokenAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class GenerateNewAccessTokenAction extends Action
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['responseBehavior'] = ResponseBehavior::className();

        return $behaviors;
    }

    /**
     * @return mixed
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        $restUser = new $this->modelClass();
        $responseData = $restUser->generateNewAccessToken();

        return $this->setResponse(201, 'New token is created', $responseData);
    }

}
