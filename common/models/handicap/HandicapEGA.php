<?php

namespace common\models\handicap;

use Yii;

/**
 * This is the EGA handicap system calculation.
 *
 */
class HandicapEGA implements HandicapInterface {
	public $golfer;
	public $tees;
	
	function allowed($tees, $golfer) {
		$holes = 18;

		if(		! (	($tees->course_rating > 0)
				&&  ($tees->slope_rating > 0)
				&&  ($golfer->handicap !== '')
				&&  ($golfer->handicap !== null) ) )
			return array_fill(0, $holes, 0);

		$allowed  = array_fill(0, $holes, 0);
		$par      = array_sum($tees->pars());
		$total_allowed  = intval( round( ($golfer->handicap * $tees->slope_rating / 113) + ($tees->course_rating - $par) ) );
		
		Yii::trace($tees->name.'('.$tees->course_rating.'/'.$tees->slope_rating.'):'.$par,'HandicapEGA::allowed');
		Yii::trace($golfer->name.'('.$golfer->handicap.'):'.$total_allowed,'HandicapEGA::allowed');

		$all18    = intval( floor($total_allowed / $holes) );	// number of given strokes for each hole
		$plusmore = $total_allowed % $holes;					// extra given stroke for some holes depending on stroke index.
		$sis      = $tees->sis();
		for($i=0; $i<$holes; $i++)				// number of holes of course
			$allowed[$i] = $all18 + ( ( $plusmore >= $sis[$i] ) ? 1 : 0 );
		
		return $allowed;
	}
}
