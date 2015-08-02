<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "match".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $position
 * @property integer $level
 *
 * @property Registration[] $registrations
 */
class _Match extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'match';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['position', 'level'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'position' => Yii::t('golf', 'Position'),
            'level' => Yii::t('golf', 'Level'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrations()
    {
        return $this->hasMany(Registration::className(), ['match_id' => 'id']);
    }
}
