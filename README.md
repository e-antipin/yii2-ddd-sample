Используются тонкие контроллеры. 
Данные прилетают в форму, а не в сущность.


```php
namespace backend\controllers;

use core\forms\Shop\BrandForm;
use core\services\Shop\BrandService;

   private $service;

    public function __construct(
        string $id,
        Module $module,
        // подключаем сервис в конструкторе
        BrandService $service,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionCreate()
    {
        $form = new BrandForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
            
            // вызываем сервис, передавая туда форму
            //----------------------------------------
                $this->service->create($form);
            //----------------------------------------
            
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

```

Основной код находится в сервисах

```php
namespace core\services\Shop;

use core\dispatchers\EventDispatcherInterface;
use core\entities\Shop\Brand;
use core\events\Shop\BrandSaveEvent;
use core\forms\Shop\BrandForm;
use core\repositories\Shop\BrandRepository;
use core\services\TransactionManager;

class BrandService
{
    private $repository;
    private $transaction;
    private $dispatcher;

    public function __construct(
        BrandRepository $repository,
        TransactionManager $transaction,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
        $this->dispatcher = $dispatcher;
    }

    public function create(BrandForm $form) : Brand
    {
        $model = new Brand();
        $model->setAttributes($form->getAttributes());

        $this->transaction->wrap(function () use ($model,$form) {
            $this->repository->save($model);

            $this->dispatcher->dispatch(new BrandSaveEvent($model));
        });

        return $model;
    }
```

