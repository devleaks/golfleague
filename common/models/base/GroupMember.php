<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "group_member".
 *
 * @property integer $id
 * @property integer $registration_id
 * @property integer $group_id
 * @property integer $position
 * @property string $status
 *
 * @property Group $group
 * @property Registration $registration
 */
class GroupMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['registration_id', 'group_id'], 'required'],
            [['registration_id', 'group_id', 'position'], 'integer'],
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
            'registration_id' => Yii::t('golf', 'Registration ID'),
            'group_id' => Yii::t('golf', 'Group ID'),
            'position' => Yii::t('golf', 'Position'),
            'status' => Yii::t('golf', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(\common\models\Group::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistration()
    {
        return $this->hasOne(\common\models\Registration::className(), ['id' => 'registration_id']);
    }
}
