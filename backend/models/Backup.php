<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "backup".
 *
 * @property integer $id
 * @property string $filename
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Backup extends _Backup
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                'timestamp' => [
                        'class' => 'yii\behaviors\TimestampBehavior',
                        'attributes' => [
                                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                        ],
                        'value' => function() { return date('Y-m-d H:i:s'); },
                ],
        ];
    }

	/**
	 *		mysql:host=localhost;port=3307;dbname=testdb
	 *		mysql:unix_socket=/tmp/mysql.sock;dbname=testdb
	 */
	public static function parseDSN($dsn) {
		$db = [];
		foreach(explode(';', str_replace('mysql:', '', $dsn)) as $e) {
			$a = explode('=', $e);
			$db[$a[0]] = $a[1];
		}
		return $db;
	}


	/**
	 *	Returns database name, or check whether database name is equal to supplied name.
	 *  @param string Database name to test.
	 *  @return boolean | string . If database name is supplied, returns if current db name matches. Otherwise returns current database name.
	 */
	public static function getDbName($name) {
		$db  = self::parseDSN(Yii::$app->getDb()->dsn);
		return $name ? $name == $db['dbname'] : $db['dbname'];
	}


	/**
	 * @param boolean $full Whether to make a backup of the database only, or pictures and documents included (full).
	 * @param boolean $uniq Whether to generate unique, time-base file name.
	 */
	protected function getBackupRoot() {
		$backup_dir = Yii::getAlias('@runtime') . '/backup/';
		if(!is_dir($backup_dir))
			mkdir($backup_dir);
		return $backup_dir;
	}
	
	public function getFilename($template, $name) {
		$template = str_replace('{date}', date('Y-m-d'), $template);
		$template = str_replace('{datetime}', date('Y-m-d-H-i-s'), $template);
		$template = str_replace('{name}', $name, $template);
		return $template;
	}

	protected function executeBackup($full, $uniq) {
		$now = date("Y-m-d-H-i-s");
		$dsn = $this->getDb()->dsn;
		$db  = Backup::parseDSN($dsn);
		$dbhost = $db['host'];
		$dbname = $db['dbname'];
		$dbuser = $this->getDb()->username;
		$dbpass = $this->getDb()->password;

		$backup_dir  = $this->getBackupRoot();
			
		// Database
		$mylsqldump = Yii::$app->params['mysql_home'] . 'bin/mysqldump';
		$db_backup_file = $this->getFilename($uniq ? '{name}-{datetime}.gz' : '{name}.gz', $dbname);
		$command = $mylsqldump . " --opt -h $dbhost -u $dbuser -p$dbpass ".$dbname.
		           "| gzip > ". $backup_dir . $db_backup_file;
		system($command, $status);
		Yii::trace($command.': '.$status, 'BackupController::doBackup');

		if($full) {	// Media
			$media_backup_file = $this->getFilename($uniq ? 'media-{datetime}.taz' : 'media.taz', $dbname);
			$command = "(cd ".Yii::getAlias('@common')." ; tar czf ".$backup_dir . $media_backup_file." uploads )";
			system($command, $status);
			Yii::trace($command.': '.$status, 'BackupController::doBackup');
		}

		if($status == 0) { // ok
			$this->filename = $db_backup_file;
			$this->status = 'OK';
		}
		return ($status == 0);
	}
	
	public function doBackup($uniq = true) {
		return $this->executeBackup(false, $uniq);
	}

	public function doFullBackup($uniq = true) {
		return $this->executeBackup(true, $uniq);
	}

	public function delete() {
		$backup_file = $this->getBackupRoot() . $this->filename;
		if(is_file($backup_file))
			unlink($backup_file);
		parent::delete();
	}
	
	public static function restore($filename = null) {
		return;
		$logfile = Yii::getAlias('@runtime').'/logs/restore.log';
		$command = $filename ?
				Yii::getAlias('@runtime').'/etc/restore-dev.sh '.$filename.' 2>&1 >> '.$logfile
				:
				Yii::getAlias('@runtime').'/etc/restore.sh 2>&1 >> '.$logfile;
		system($command, $status);
		Yii::trace($command.': '.$status, 'BackupController::doBackup');
		return $status == 0;
	}

}
