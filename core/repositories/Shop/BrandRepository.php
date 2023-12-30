<?php declare(strict_types=1);

namespace core\repositories\Shop;

use core\entities\Shop\Brand;
use core\repositories\AbstractActiveRecordRepository;

class BrandRepository extends AbstractActiveRecordRepository
{
    public function findById($id): ?Brand
    {
        return Brand::findOne($id);
    }

    public function getById($id): Brand
    {
        if (!$entity = $this->findById($id))
            throw new \DomainException('Brand is not found.');

        return $entity;
    }

    public function findBySlug($slug): ?Brand
    {
        return Brand::findOne(['slug' => $slug]);
    }

    public function getBySlug($slug): Brand
    {
        if (!$entity = $this->findBySlug($slug))
            throw new \DomainException('Brand is not found.');

        return $entity;
    }
}