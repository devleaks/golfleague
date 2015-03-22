<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property integer $id
 * @property string $object_type
 * @property integer $object_id
 * @property string $media_type
 * @property string $name
 * @property string $description
 * @property string $mime_type
 * @property string $filename
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class _Media extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_type'], 'string'],
            [['object_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['media_type'], 'string', 'max' => 40],
            [['name', 'mime_type'], 'string', 'max' => 80],
            [['description', 'filename'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('igolf', 'ID'),
            'object_type' => Yii::t('igolf', 'Object Type'),
            'object_id' => Yii::t('igolf', 'Object'),
            'media_type' => Yii::t('igolf', 'Media Type'),
            'name' => Yii::t('igolf', 'Name'),
            'description' => Yii::t('igolf', 'Description'),
            'mime_type' => Yii::t('igolf', 'Mime Type'),
            'filename' => Yii::t('igolf', 'Filename'),
            'status' => Yii::t('igolf', 'Status'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
        ];
    }
}
