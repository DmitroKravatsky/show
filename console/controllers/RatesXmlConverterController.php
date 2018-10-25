<?php

namespace console\controllers;

use common\models\paymentSystem\PaymentSystem;
use yii\console\Controller;
use common\models\exchangeRates\ExchangeRates;
use Yii;
use yii\helpers\FileHelper;

class RatesXmlConverterController extends Controller
{
    protected $pathToFile;
    protected $fileName = '/rates.xml';

    public function init()
    {
        $this->pathToFile = Yii::getAlias('@frontend/web/xml');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function actionCreate()
    {
        $rates = $this->getRates();

        try {
            $ratesArray = [];
            foreach ($rates as $rate) {
                /** @var $rate ExchangeRates */
                $ratesArray[] = [
                    'from'      => $rate->fromPaymentSystem->currency_code,
                    'to'        => $rate->toPaymentSystem->currency_code,
                    'in'        => 1,
                    'out'       => $rate->value,
                    'amount'    => $rate->toPaymentSystem->reserve->sum,
                    'minamount' => $rate->fromPaymentSystem->min_transaction_sum
                        . ' ' . PaymentSystem::getCurrencyValue($rate->fromPaymentSystem->currency),
                ];
            }

            $xml = new \SimpleXMLElement("<?xml version=\"1.0\"?><rates></rates>");
            $this->arrayToXml($ratesArray, $xml);

            $this->createDir();

            return $xml->asXML($this->pathToFile . $this->fileName);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->pathToFile . $this->fileName;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    protected function getRates()
    {
        return ExchangeRates::find()
            ->with('fromPaymentSystem')
            ->with('toPaymentSystem')
            ->all();
    }

    /**
     * @param $array
     * @param $xml
     */
    protected function arrayToXml($array, &$xml)
    {
        foreach ($array as $key => $value) {
            /** @var $xml \SimpleXMLElement */
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subNode = $xml->addChild("$key");
                    $this->arrayToXml($value, $subNode);
                } else {
                    $subNode = $xml->addChild("item");
                    $this->arrayToXml($value, $subNode);
                }
            } else {
                $xml->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    protected function createDir()
    {
        if (!is_dir($this->pathToFile)) {
            FileHelper::createDirectory($this->pathToFile);
        }
    }
}
