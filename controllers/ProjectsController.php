<?php

namespace app\controllers;

use app\models\Project;
use app\models\users\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;


{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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
        ];
    }

    /**
     * Displays projects.
     *
     * @return string
     */
    public function actionIndex()
    {
        $user = User::findOne(\Yii::$app->user->id);
        $dataProvider = new ActiveDataProvider([
            'query' => $user->getProjects(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * Creating project.
     *
     * @return Response|string
     */
    public function actionCreate()
    {
        $model = new Project();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash(Yii::t('project', 'Project created!'));

            return $this->redirect(['index']);
        }
        return $this->render('form', [
            'model' => $model,
        ]);
    }

    /**
     * Updating project.
     *
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->getUserProject($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash(Yii::t('project', 'Project changed!'));

            return $this->redirect(['index']);
        }
        return $this->render('form', [
            'model' => $model,
        ]);
    }

    /**
     * Delete project.
     *
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->getUserProject($id);
        if ($model->delete()) {
            Yii::$app->session->setFlash(Yii::t('project', 'Project deleted!'));
        } else {
            Yii::$app->session->setFlash(Yii::t('project', 'Error occurred!'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Generates new secret.
     *
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionSecret($id)
    {
        $model = $this->getUserProject($id);
        $model->secret = md5(uniqid());
        if ($model->save(false)) {
            Yii::$app->session->setFlash(Yii::t('project', 'API key changed!'));
        } else {
            Yii::$app->session->setFlash(Yii::t('project', 'Error occurred!'));
        }
        return $this->redirect(['index']);
    }

    private function getUserProject($id)
    {
        $model = Project::find()
            ->where([
                'id' => $id,
                'created_by' => \Yii::$app->user->id
            ])
            ->one();
        if ($model == null) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}
