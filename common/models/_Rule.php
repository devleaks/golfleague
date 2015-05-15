<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rule".
 *
 * @property integer $id
 * @property string $competition_type
 * @property string $object_type
 * @property string $rule_type
 * @property integer $team
 * @property string $name
 * @property string $description
 * @property string $note
 * @property string $classname
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Competition[] $competitions
 * @property Competition[] $competitions0
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
            [['competition_type'], 'required'],
            [['team'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['competition_type'], 'string', 'max' => 20],
            [['object_type', 'rule_type'], 'string', 'max' => 40],
            [['name', 'classname'], 'string', 'max' => 80],
            [['description'], 'string', 'max' => 255],
            [['note'], 'string', 'max' => 160],
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
            'id' => Yii::t('igolf', 'ID'),
            'competition_type' => Yii::t('igolf', 'Competition Type'),
            'object_type' => Yii::t('igolf', 'Object Type'),
            'rule_type' => Yii::t('igolf', 'Rule Type'),
            'team' => Yii::t('igolf', 'Team'),
            'name' => Yii::t('igolf', 'Name'),
            'description' => Yii::t('igolf', 'Description'),
            'note' => Yii::t('igolf', 'Note'),
            'classname' => Yii::t('igolf', 'Classname'),
            'created_at' => Yii::t('igolf', 'Created At'),
            'updated_at' => Yii::t('igolf', 'Updated At'),
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
    public function getCompetitions0()
    {
        return $this->hasMany(Competition::className(), ['rule_final_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoints()
    {
        return $this->hasMany(Point::className(), ['rule_id' => 'id']);
    }
}
