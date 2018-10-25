<?php

namespace backend\modules\admin\controllers\actions\exchangeRates;

use console\controllers\RatesXmlConverterController;
use yii\base\Action;
use Yii;
use yii\web\ErrorHandler;

class ExportAction extends Action
{
    /**
     * @return \yii\web\Response
     */
    public function run()
    {
        try {
            $controller = new RatesXmlConverterController('rates-xml-converter', Yii::$app);
            $controller->runAction('create');
            Yii::$app->session->setFlash('success', Yii::t('app', 'File successfully generated.'));
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
        }
        return $this->controller->redirect(Yii::$app->request->referrer);
    }
}
