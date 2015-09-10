<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "group".
 *
 * @property integer $id
 * @property string $group_type
 * @property string $name
 * @property string $handicap
 * @property integer $position
 * @property string $start_time
 * @property integer $start_hole
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 *
 * @property RegistrationGroup[] $registrationGroups
 */
class _Group extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_type', 'name'], 'required'],
            [['group_type'], 'string'],
            [['handicap'], 'number'],
            [['position', 'start_hole'], 'integer'],
            [['start_time', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 80],
            [['note'], 'string', 'max' => 160]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'group_type' => Yii::t('golf', 'Group Type'),
            'name' => Yii::t('golf', 'Name'),
            'handicap' => Yii::t('golf', 'Handicap'),
            'position' => Yii::t('golf', 'Position'),
            'start_time' => Yii::t('golf', 'Start Time'),
            'start_hole' => Yii::t('golf', 'Start Hole'),
            'note' => Yii::t('golf', 'Note'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrationGroups()
    {
        return $this->hasMany(RegistrationGroup::className(), ['group_id' => 'id']);
    }
}
