<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "group_member".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $object_type
 * @property integer $object_id
 * @property integer $position
 * @property string $status
 *
 * @property Group $group
 */
class _GroupMember extends \yii\db\ActiveRecord
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
            [['group_id'], 'required'],
            [['group_id', 'object_id', 'position'], 'integer'],
            [['object_type'], 'string'],
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
            'group_id' => Yii::t('golf', 'Group ID'),
            'object_type' => Yii::t('golf', 'Object Type'),
            'object_id' => Yii::t('golf', 'Object ID'),
            'position' => Yii::t('golf', 'Position'),
            'status' => Yii::t('golf', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }
}
