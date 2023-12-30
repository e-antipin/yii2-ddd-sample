<?php

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

    public function edit(Brand $model,BrandForm $form) : Brand
    {
        $model->setAttributes($form->getAttributes());
        $this->transaction->wrap(function () use ($model,$form) {

            $this->repository->save($model);

            $this->dispatcher->dispatch(new BrandSaveEvent($model));
        });

        return $model;
    }

    public function remove(Brand $model) : void
    {
        $this->transaction->wrap(function () use ($model) {
            $this->repository->remove($model);
        });
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }
}