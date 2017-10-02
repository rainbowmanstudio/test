<?php

namespace app\controllers;

use app\models\PoolRequest;
use app\models\users\SignupForm;
use app\models\users\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\users\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionGenerator()
    {
        $model = new PoolRequest\Form();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            /** @var PoolRequest\PoolRequest $request */
            $request = $model->save();
            if ($request != false){
                return $this->render('poolRequest/success', ['email' => $request->email, 'link' => $request->getLink()]);
            }
        }
        return $this->render('poolRequest/form', ['model' => $model]);
    }

    public function actionDownload($hash)
    {
        $request = PoolRequest\PoolRequest::find()
            ->where(['filename' => $hash])
            ->one();

        if ($request != null) {
            $path = $request->id < 10 ? $request->id : substr($request->id, 0, 1) . '/' . substr($request->id, 1, 2);
            $file = Yii::getAlias('@codes/') . $path . '/' . $request->filename;
            if (file_exists($file)) {
                return \Yii::$app->response->sendFile($file);
            }
        }
        throw new NotFoundHttpException();
    }

    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                $email = \Yii::$app->mailer->compose('confirmEmail', ['user' => $user])
                    ->setTo($user->email)
                    ->setFrom([\Yii::$app->params['email'] => \Yii::$app->name . ' robot'])
                    ->setSubject(Yii::t('user', 'Confirm email'))
                    ->send();
                if($email){
                    Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Confirmation email has been sent!'));
                }
                else{
                    Yii::$app->getSession()->setFlash('warning', Yii::t('app', 'Error occurred!'));
                }
                return $this->goHome();
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionConfirm($id, $key)
    {
        $user = User::find()->where([
            'id' => $id,
            'auth_key' => $key,
            'status' => User::STATUS_EMAIL_NOT_VERIFIED,
        ])->one();
        if(!empty($user)){
            $user->status = User::STATUS_EMAIL_VERIFIED;
            $user->save();
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Email confirmed! You can login now'));
        }
        else{
            Yii::$app->getSession()->setFlash('warning', Yii::t('app', 'Error occurred!'));
        }
        return $this->goHome();
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
