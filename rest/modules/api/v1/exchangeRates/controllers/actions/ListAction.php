<?php

namespace rest\modules\api\v1\exchangeRates\controllers\actions;

use yii\rest\Action;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ListAction extends Action
{
    /**
     * Get list of Exchange rates
     *
     * @SWG\Get(path="/exchange-rates",
     *      tags={"Exchange Rates module"},
     *      summary="Exchange rates list",
     *      description="Exchange rates list",
     *      produces={"application/json"},
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *     ),
     *     @SWG\Response (
     *        response = 404,
     *        description = "File is not found"
     *     )
     * )
     *
     * @throws NotFoundHttpException
     * @return string
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_HTML;
        $filePath = Yii::$app->params['rates-xml-file'];
        if (!file_exists($filePath)) {
            throw new NotFoundHttpException('File is not found');
        }

        return file_get_contents($filePath);
    }
}
