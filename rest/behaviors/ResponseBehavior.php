<?php

namespace rest\behaviors;

use Yii;
use yii\base\Behavior;

/**
 * Class ResponseBehavior
 * @package rest\behaviors
 */
class ResponseBehavior extends Behavior
{
    // todo не вижу смысла в этом поведении, так как есть стандартный в контролере
    // Разве что в краткости записи при использовании, удалять?
    /**
     * @param $statusCode
     * @param $message
     * @param array $data
     * @return array
     */
    public function setResponse($statusCode, $message, $data = [])
    {
        Yii::$app->response->setStatusCode($statusCode);
        return [
            'status'  => Yii::$app->response->statusCode,
            'message' => $message,
            'data'    => $data
        ];
    }
}