<?php declare(strict_types=1);

namespace core\repositories;

use yii\db\ActiveRecord;

abstract class AbstractActiveRecordRepository implements ActiveRecordRepositoryInterface
{

    /**
     * @param ActiveRecord $record
     * @return ActiveRecord
     */
    public function save(ActiveRecord $record): ActiveRecord
    {
        $className = get_class($record);

        if (! $record->save()) {
            throw new \RuntimeException("Can't save entity '{$className}' " . print_r($record->errors, true));
        }
        return $record;
    }


    /**
     * @param ActiveRecord $record
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove(ActiveRecord $record): void
    {
        if (false === $record->delete()) {
            $class = get_class($record);
            throw new \RuntimeException("Can't delete entity '{$class}'");
        }
    }
}
