<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use Yii;

/**
 * Class LoginAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class LoginAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * Login action
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function run()
    {
        try {
            /** @var RestUserEntity $userModel */
            $userModel = new $this->modelClass;

            if ($user = $userModel->login(Yii::$app->request->bodyParams)) {
                return $this->controller->setResponse(
                    200, 'Авторизация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]);
            }

            throw new UnauthorizedHttpException();
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new UnauthorizedHttpException('Ошибка авторизации.');
        }
    }
}