<?php

namespace backend\controllers;

use core\entities\Shop\search\BrandSearch;
use core\forms\Shop\BrandForm;
use core\services\Shop\BrandService;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

final class BrandController extends Controller
{
    private $service;

    public function __construct(
        string $id,
        Module $module,
        BrandService $service,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            'bulk',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        Url::remember();

        $searchModel = new BrandSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $form = new BrandForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->create($form);

                Yii::$app->session->addFlash('success','Запись создана.');

                $url = Url::previous();
                return $this->redirect($url ?: Url::to(['index']));

            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->service->getById($id);

        $form = new BrandForm($model);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($model,$form);

                Yii::$app->session->addFlash('success','Запись сохранена.');

                $url = Url::previous();
                return $this->redirect($url ?: Url::to(['index']));

            } catch (\DomainException $e)
            {

                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->service->getById($id);

        try
        {
            $this->service->remove($model);

            Yii::$app->session->addFlash('success','Запись удалена.');
        }
        catch (\DomainException $e)
        {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        $url = Url::previous();
        return $this->redirect($url ?: Url::to(['index']));
    }

}