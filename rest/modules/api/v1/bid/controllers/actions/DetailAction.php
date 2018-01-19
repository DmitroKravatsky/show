<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\behaviors\ValidateGetParameters;

/**
 * Class DetailAction
 * @mixin ValidateGetParameters
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class DetailAction extends \yii\rest\Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidateGetParameters::className(),
                'inputParams' => ['id']
            ],
        ];
    }

    /**
     * @return bool
     */
    public function beforeRun(): bool
    {
        $this->validationParams();
        return parent::beforeRun();
    }

    /**
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        /** @var \common\models\bid\BidEntity $bid */
        $bid = new $this->modelClass;

        return $bid->getBidDetails(\Yii::$app->request->get('id'));
    }
}