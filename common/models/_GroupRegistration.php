<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "group_registration".
 *
 * @property integer $id
 * @property integer $position
 * @property integer $group_id
 * @property integer $registration_id
 * @property integer $practice_id
 * @property string $status
 *
 * @property Practice $practice
 * @property Group $group
 * @property Registration $registration
 */
class _GroupRegistration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group_registration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'group_id'], 'required'],
            [['id', 'position', 'group_id', 'registration_id', 'practice_id'], 'integer'],
            [['status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'position' => Yii::t('golf', 'Position'),
            'group_id' => Yii::t('golf', 'Group ID'),
            'registration_id' => Yii::t('golf', 'Registration ID'),
            'practice_id' => Yii::t('golf', 'Practice ID'),
            'status' => Yii::t('golf', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPractice()
    {
        return $this->hasOne(Practice::className(), ['id' => 'practice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistration()
    {
        return $this->hasOne(Registration::className(), ['id' => 'registration_id']);
    }
}
