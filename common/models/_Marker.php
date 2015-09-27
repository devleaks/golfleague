<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "marker".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $created_at
 */
class _Marker extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'marker';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 80],
            [['email'], 'string', 'max' => 40],
            [['name'], 'unique'],
            [['email'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'name' => Yii::t('golf', 'Name'),
            'email' => Yii::t('golf', 'Email'),
            'created_at' => Yii::t('golf', 'Created At'),
        ];
    }
}
