<?php declare(strict_types=1);

namespace core\repositories;

use yii\db\ActiveRecord;

interface ActiveRecordRepositoryInterface
{
    public function save(ActiveRecord $record): ActiveRecord;

    public function remove(ActiveRecord $record): void;
}
