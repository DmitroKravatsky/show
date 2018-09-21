<?php

namespace backend\modules\admin\controllers\actions\reserve;

use backend\modules\admin\controllers\ReserveController;
use common\models\reserve\ReserveEntity;
use yii\base\Action;
use Yii;
use yii\helpers\Url;

class CreateAction extends Action
{
    /** @var ReserveController */
    public $controller;

    /**
     * @return string|\yii\web\Response
     */
    public function run()
    {
        //Yii::$app->language = Yii::$app->session->get('language', Yii::$app->language);
        $reserve = new ReserveEntity();
        if ($reserve->load(Yii::$app->request->post()) && $reserve->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Reserve successfully created.'));
            return $this->controller->redirect(Url::to(['index']));
        }

        return $this->controller->render('create', [
            'reserve' => $reserve,
        ]);
    }
}
