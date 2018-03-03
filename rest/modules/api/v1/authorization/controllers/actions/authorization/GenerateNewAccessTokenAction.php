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
use Yii; // todo вот такого не должно быть.
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

        $behaviors['responseBehavior'] = ResponseBehavior::className(); // todo тебе phpstrorm подсказывает, что он деприкайтет

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
        /** @var  $restUser RestUserEntity */
        $restUser = new $this->modelClass(); // todo где нотации, чтобы я мог перейти на метод. Добавил для примера
        $responseData = $restUser->generateNewAccessToken();

        return $this->setResponse(201, 'New token is created', $responseData); // todo опять нотации. я должен иметь возможность перейти на setResponse
    }

}
