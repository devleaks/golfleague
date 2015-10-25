<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "rule".
 *
 * @property integer $id
 * @property integer $league_id
 * @property string $name
 * @property string $description
 * @property string $note
 * @property string $competition_type
 * @property string $rule_type
 * @property integer $team_size
 * @property integer $team_score
 * @property integer $handicap
 * @property string $data_type
 * @property string $source_direction
 * @property string $destination_type
 * @property string $destination_format
 * @property string $classname
 * @property string $parameters
 * @property string $flightMethod
 * @property string $teamMethod
 * @property string $matchMethod
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $source_type
 *
 * @property Competition[] $competitions
 * @property Competition[] $competitions0
 * @property Point[] $points
 * @property League $league
 */
class Rule extends \yii\db\ActiveRecord
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
            [['league_id', 'team_size', 'team_score', 'handicap'], 'integer'],
            [['competition_type'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'classname'], 'string', 'max' => 80],
            [['description', 'parameters'], 'string', 'max' => 255],
            [['note', 'flightMethod', 'teamMethod', 'matchMethod'], 'string', 'max' => 160],
            [['competition_type', 'data_type', 'source_direction', 'status'], 'string', 'max' => 20],
            [['rule_type', 'destination_type', 'destination_format', 'source_type'], 'string', 'max' => 40],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('golf', 'ID'),
            'league_id' => Yii::t('golf', 'League ID'),
            'name' => Yii::t('golf', 'Name'),
            'description' => Yii::t('golf', 'Description'),
            'note' => Yii::t('golf', 'Note'),
            'competition_type' => Yii::t('golf', 'Competition Type'),
            'rule_type' => Yii::t('golf', 'Rule Type'),
            'team_size' => Yii::t('golf', 'Team Size'),
            'team_score' => Yii::t('golf', 'Team Score'),
            'handicap' => Yii::t('golf', 'Handicap'),
            'data_type' => Yii::t('golf', 'Data Type'),
            'source_direction' => Yii::t('golf', 'Source Direction'),
            'destination_type' => Yii::t('golf', 'Destination Type'),
            'destination_format' => Yii::t('golf', 'Destination Format'),
            'classname' => Yii::t('golf', 'Classname'),
            'parameters' => Yii::t('golf', 'Parameters'),
            'flightMethod' => Yii::t('golf', 'Flight Method'),
            'teamMethod' => Yii::t('golf', 'Team Method'),
            'matchMethod' => Yii::t('golf', 'Match Method'),
            'status' => Yii::t('golf', 'Status'),
            'created_at' => Yii::t('golf', 'Created At'),
            'updated_at' => Yii::t('golf', 'Updated At'),
            'source_type' => Yii::t('golf', 'Source Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitions()
    {
        return $this->hasMany(\common\models\Competition::className(), ['rule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompetitions0()
    {
        return $this->hasMany(\common\models\Competition::className(), ['final_rule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoints()
    {
        return $this->hasMany(\common\models\Point::className(), ['rule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeague()
    {
        return $this->hasOne(\common\models\League::className(), ['id' => 'league_id']);
    }
}
