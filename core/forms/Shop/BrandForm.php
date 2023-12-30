<?php declare(strict_types=1);

namespace core\forms\Shop;

use core\entities\Shop\Brand;
use yii\base\Model;
use yii\helpers\Inflector;
use yii\helpers\Json;

class BrandForm extends Model
{
    public $name;
    public $slug;
    public $description;
    public $imageJson;

    public $h1;
    public $meta_title;
    public $meta_description;

    public $position;
    public $status;
    public $created_at;
    public $updated_at;
    public $created_by;
    public $updated_by;

    private $entity;

    public function __construct(Brand $entity = null, $config = [])
    {
        $this->status = Brand::STATUS_ACTIVE;
        $this->created_by = \Yii::$app->user->getId();
        $this->created_at = date('Y-m-d H:i:s');
        if ($entity) {
            $this->setAttributes($entity->getAttributes(), false);

            $this->updated_by = \Yii::$app->user->getId();
            $this->updated_at = date('Y-m-d H:i:s');
        }
        parent::__construct($config);
        $this->entity = $entity;
    }

    public function rules()
    {
        return [

            [['name'], 'required'],
            [
                ['name'],
                'unique',
                'targetClass' => Brand::className(),
                'targetAttribute' => 'name',
                'filter' => $this->entity ? ['<>', 'id', $this->entity->id] : null
            ],
            [['name', 'slug'], 'string', 'max' => 255],
            ['slug', 'default', 'value' => function ($model, $attribute) {return Inflector::slug($model->name);}],
            [
                ['slug'],
                'unique',
                'targetClass' => Brand::className(),
                'targetAttribute' => 'slug',
                'filter' => $this->entity ? ['<>', 'id', $this->entity->id] : null
            ],
            [[ 'description'], 'string'],
            [[ 'h1', 'meta_title', 'meta_description'], 'string'],
            [['h1','meta_title','meta_description'], 'default', 'value' => null],

            [['status', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            ['status', 'in', 'range'=>array_keys(self::getStatuses())],

            ['position', 'integer'],
            [['position'], 'default', 'value'=>0],
            ['imageJson', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'slug' => 'Чпу',
            'imageJson' => 'Изображение',
            'description' => 'Описание',
            'path' => 'Изображение',
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

    public function getUploadedImage(): ?UploadedImageForm
    {
        $data = Json::decode($this->imageJson);
        $image = new UploadedImageForm($data);
        if (!$image->validate())
            throw new \DomainException('Проблемы с загрузкой изображения ' . print_r($image->errors, true));
        return $image;
    }

    public function getImageUrl(): ?string
    {
        return empty($this->entity) ? null : $this->entity->getImageUrl();
    }

    public function getID(): int
    {
        return $this->entity->id;
    }

    public function getStatuses(): array
    {
        return Brand::getStatusesArray();
    }
}