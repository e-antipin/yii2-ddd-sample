<?php

namespace core\entities\Shop;

use core\entities\User\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "brand".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $h1
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property int|null $position
 * @property int $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Brand extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['description', 'h1', 'meta_title', 'meta_description'], 'string'],
            [['position', 'status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug' ], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['slug'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Чпу',
            'description' => 'Описание',
            'h1' => 'H1',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'position' => 'Позиция',
            'status' => 'Статус',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    public function getStatusName(): string
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    public static function getStatusesArray(): array
    {
        return [
            self::STATUS_INACTIVE => 'OFF',
            self::STATUS_ACTIVE => 'ON',
        ];
    }

    public function getUrl(): string
    {
        return Yii::$app->frontendUrlManager->createUrl(['/brand/view','slug'=> $this->slug]);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

	public static function findByName(string $name): ?self
	{
		return static::find()
			->andWhere(['name' => $name])
			->limit(1)
			->one();
	}

    public static function tableName()
    {
        return '{{%brand}}';
    }
}
