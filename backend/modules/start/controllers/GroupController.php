<?php

namespace backend\modules\start\controllers;

use common\models\Competition;
use common\models\Registration;
use common\models\Group;

use Yii;
use yii\web\NotFoundHttpException;

class GroupController extends \backend\controllers\DefaultController
{
	const NAME_SEP = ' / ';
	
	/**
	 * Build team and place registration in it. Create flight if necessary.
	 * @return team_id updated or created
	 */
	protected function makeGroup($group_type, $competition, $group_str) {
		$group_arr = explode('-', $group_str->id); // team-123
		$group = Group::findGroup($group_arr[1]);
		if(!$group) { // need to create it
			$group = new Group();
			$group->group_type = $group_type;
			$group->name = $group_type.' '.$group_arr[1];
			$group->save();
			$group->refresh();
		} else {
			$group->clean();
		}
		$name = '';
		foreach($group_str->registrations as $registration_str) {
			$registration_arr = explode('-', $registration_str); // registration-456
			if($registration = Registration::findOne($registration_arr[1])) {
				$group->add($registration);
				$name .= $registration->golfer->name.self::NAME_SEP;			
			}
		}
		$name = rtrim($name, self::NAME_SEP);
		$group->name = substr($name, 0, 80);
		$group->handicap = $group_str->handicap;
		$group->save();
		return $group->id;
	}
	
	
	protected function adjustGroups($savedgroups, $group_type, $competition) {
		$groups = json_decode($savedgroups);

		$existingGroups = [];
		if($ff = $competition->getGroups()->andWhere(['group_type' => $group_type])->all()) {
			foreach($ff as $f) {
				$existingGroups[$f->id] = $f;
			}
		}
			
		foreach($groups as $group) { // update or create each team
			$id = $this->makeGroup($group_type, $competition, $group);
			unset($existingGroups[$id]); // team still used, remove from "oldFlights"
		}
		foreach($existingGroups as $group) { // delete unused teams ($existingGroups minus those still in use.)
			$group->clean();
			$group->delete();
		}
	}

    /**
     * Finds the Flight model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Flight the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCompetition($id)
    {
        if (($model = Competition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
