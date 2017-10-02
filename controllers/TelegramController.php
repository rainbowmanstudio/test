<?php

namespace app\controllers;

use app\models\TelegramMessage;
use Yii;
use yii\db\IntegrityException;
use yii\web\Controller;

class TelegramController extends Controller
{
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $raw = Yii::$app->request->getRawBody();
        $result = json_decode($raw);
        $chat = new TelegramMessage();
        $chat->fillData($result);
        try {
            $chat->save();
        } catch (IntegrityException $e) {
            return;
        }
        $chat->handle();
    }
	 public function actionIndextwo()
    {
        $raw = Yii::$app->request->getRawBody();
        $result = json_decode($raw);
        $chat = new TelegramMessage();
        $chat->fillData($result);
        try {
            $chat->save();
        } catch (IntegrityException $e) {
            return;
        }
        $chat->handle();
    }
	public function actionIndexthree()
    {
        $raw = Yii::$app->request->getRawBody();
        $result = json_decode($raw);
        $chat = new TelegramMessage();
        $chat->fillData($result);
        try {
            $chat->save();
        } catch (IntegrityException $e) {
            return;
        }
        $chat->handle();
    }
}
