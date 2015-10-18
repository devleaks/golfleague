<?php

namespace backend\commands;

use backend\models\Backup;

use yii\console\Controller;
use Yii;

class BackupController extends Controller {
	/**
	 *  Create performs a mysql database backup.
	 *
	 */
    public function actionCreate($uniq = true) {
        $model = new Backup();

		if($model->doBackup($uniq != 'false')) {
			if(!$model->save()) {
				echo Yii::t('golf', 'Backup info not saved.');
			}
		} else {
			echo Yii::t('golf', 'There was an error producing the backup.');
		}
    }

    public function actionFull($uniq = true) {
        $model = new Backup();

		if($model->doFullBackup($uniq != 'false')) {
			if(!$model->save()) {
				echo Yii::t('golf', 'Backup info not saved.');
			}
		} else {
			echo Yii::t('golf', 'There was an error producing the backup.');
		}
    }

	/**
	 *  Deletes all backup older than given days.
	 *
	 *	@param integer $days Number of days to keep backup. Must be larger than 7. Defaults to 7.
	 */
    public function actionDelete($days = 7) {
		if(intval($days)<7) $days = 7;
		$last = date('Y-m-d', strtotime($days.' days ago'));
		$fn = Backup::getDbName(false).'.gz';
		foreach(Backup::find()
					->andWhere(['not',['filename' => $fn]])
					->andWhere(['<=','created_at',$last])
					->each() as $backup)
			$backup->delete();
		// echo Yii::t('golf', 'Backup older than {0} deleted.', [$last]);
    }

    public function actionDb() {
        echo Backup::getDbName(false);
    }

}