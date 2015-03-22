<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rule".
 *
 * @property integer $id
 * @property string $object_type
 * @property string $rule_type
 * @property string $name
 * @property string $description
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 * @property string $competition_type
 * @property string $classname
 *
 * @property Competition[] $competitions
 * @property Point[] $points
 */
class _Rule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['competition_type'], 'required'],
            [['object_type', 'rule_type'], 'string', 'max' => 40],
            [['name', 'classname'], 'string', 'max' => 80],
            [['description', 'note'], 'string', 'max' => 255],
            [['competition_type'], 'string', 'max' => 20],
            [['name'], 'unique'],
            [['classname'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golfleague', 'ID'),
            'object_type' => Yii::t('golfleague', 'Object Type'),
            'rule_type' => Yii::t('golfleague', 'Rule Type'),
            'name' => Yii::t('golfleague', 'Name'),
            'description' => Yii::t('golfleague', 'Description'),
            'note' => Yii::t('golfleague', 'Note'),
            'created_at' => Yii::t('golfleague', 'Created At'),
            'updated_at' => Yii::t('golfleague', 'Updated At'),
            'competition_type' => Yii::t('golfleague', 'Competition Type'),
            'classname' => Yii::t('golfleague', 'Classname'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitions()
    {
        return $this->hasMany(Competition::className(), ['rule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoints()
    {
        return $this->hasMany(Point::className(), ['rule_id' => 'id']);
    }
}
