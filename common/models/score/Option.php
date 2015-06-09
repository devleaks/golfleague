<?php

namespace common\models\score;

class Option {
	/* Course */
	const PAR			= 'par';
	const SI			= 'si';
	const LENGTH		= 'length';

	/* Scores */
	const NINE			= 'nine';
	const BACK			= 'back';
	const GROSS			= 'gross';
	const NET			= 'net';
	const STABLEFORD	= 'stableford';
	const ALLOWED		= 'allowed';
	const TO_PAR		= 'to_par';

	/* Columns */
	const TOTAL			= 'total';
	const TODAY			= 'today';
	const ROUND			= 'round';
	const ROUNDS		= 'rounds';
	const FRONTBACK		= 'fb';
	const HOLE			= 'hole';
	const HOLES			= 'holes';
	const CARDS			= 'cards';

	/* Appearance */
	const SPLITFLAP		= 'splitflap';
	const COLOR			= 'color';
	const SHAPE			= 'shape';
	const ALLOWED_ICON	= 'allowed_icon';
	const LEGEND		= 'legend';
	const FOOTER		= 'footer';
	const MATCH_NAME	= 'match_name';
	
	/* Other */
	const AUTO_REFRESH	= 'auto_refresh';
	const AUTO_REFRESH_RATE = 'auto_refresh_rate';


	private static $instance;
	private $options;
	

	function __construct() {
		self::$instance = $this;
		$this->options = $this->defaults();
	}
	
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			new self;
		}
		return self::$instance;
	}

	public static function defaults() {
		return array(
				self::PAR 		=> false,
				self::SI 			=> false,
				self::LENGTH 		=> false,

				/* Scores */
				self::NINE 		=> false,
				self::BACK 		=> false,
				self::GROSS 		=> false,
				self::NET 		=> false,
				self::STABLEFORD	=> false,
				self::ALLOWED 	=> false,
				self::TO_PAR 		=> false,

				/* Columns */
				self::TODAY 		=> false,
				self::ROUNDS 		=> false,
				self::FRONTBACK 	=> false,
				self::HOLES 		=> false,
				self::CARDS 		=> false,

				/* Appearance */
				self::SPLITFLAP 	=> false,
				self::COLOR 		=> false,
				self::SHAPE 		=> false,
				self::ALLOWED_ICON => '•',
				self::LEGEND 		=> false,
				self::FOOTER 		=> false,
				self::MATCH_NAME	=> true,
				
				/* Other */
				self::AUTO_REFRESH => false,
				self::AUTO_REFRESH_RATE => 600000,
		);
	}

	public function reset_to_defaults() {
		return $this->options = $this->defaults();
	}
	
	function is_valid($name) {
		return array_key_exists($name, $this->options);
	}

	function option($name) {
		if($this->is_valid($name))
			return $this->options[$name];
		return false;
	}

	function get_options() {
		return $this->options;
	}

	function set_options($argv) {
		$valid_options = array();
		
		if(isset($argv['options']))
		if($argv['options'] != '') {
			$params = explode(',', $argv['options']);		
			foreach($params as $param) {
				if($this->is_valid($param))
					$valid_options[] = $param;
				$this->set_option($param, true);
			}
			if(in_array('fb1', $params)) $this->set_option(self::FRONTBACK, 1);
			if(in_array('fb2', $params)) $this->set_option(self::FRONTBACK, 2);
		}
		
		if(isset($argv['refresh']))
		if(($r = intval($argv['refresh'])) > 0) { // supplied in minutes
			if($r < 2)  $r = 2;
			if($r > 20) $r = 20;			
			$this->set_option(self::AUTO_REFRESH, true);
			$this->set_option(self::AUTO_REFRESH_RATE, (60000*$r));
		}

		return $valid_options;
	}

	function set_option($name, $value) {
		if($this->is_valid($name))
			$this->options[$name] = $value;
	}

	function add_option($name, $value) {
		$this->options[$name] = $value;
	}

	public static function score_names() {
		return array(
			-4 => 'condor',
			-3 => 'albatross',
			-2 => 'eagles',
			-1 => 'birdie',
			 0 => 'par',
			 1 => 'bogey',
			 2 => 'double_bogey',
			 3 => 'triple_bogey',
		);
	}

	public static function stat_name($id) {
		switch ($id) {
			case -4: return __('Condor', 'golfturbo'); break;
			case -3: return __('Albatross', 'golfturbo'); break;
			case -2: return __('Eagle', 'golfturbo'); break;
			case -1: return __('Birdy', 'golfturbo'); break;
			case  0: return __('Par', 'golfturbo'); break;
			case  1: return __('Bogey', 'golfturbo'); break;
			case  2: return __('Double bogey', 'golfturbo'); break;
			case  3: return __('Tripple bogey or worse', 'golfturbo'); break;
		}
	}

	public static function pretty_name($name) {
		switch($name) {
			case 'stableford':	$display_name = __('Stableford', 'golfturbo');	break;
			case 'net':			$display_name = __('Net', 'golfturbo');			break;
			case 'allowed':		$display_name = __('Allowed', 'golfturbo');		break;
			case 'to_par':		$display_name = __('To Par', 'golfturbo');		break;
			case 'gross':		$display_name = __('Gross', 'golfturbo');		break;
			default:			$display_name = $name;							break;
		}
		return $display_name;
	}

	public static function legend($numeric = false, $m = 'c') {
		$p = ($m == 'c') ? 'color c' : 'shape s';
		return $numeric ?
'<table class="scorecard-legend"><tr>
<td><div class="'.$p.'-3">-3</div> '.__('or less', 'golfturbo').'</td>
<td><div class="'.$p.'-2">-2</div></td>
<td><div class="'.$p.'-1">-1</div></td>
<td><div class="'.$p.'0">Par</div></td>
<td><div class="'.$p.'1">+1</div></td>
<td><div class="'.$p.'2">+2</div></td>
<td><div class="'.$p.'3">+3</div> '.__('or more', 'golfturbo').'</td>
</tr></table>'
			:
'<table class="scorecard-legend"><tr>
<td class="'.$p.'-3">'.self::stat_name(-3).' '.__('or less', 'golfturbo').'</td>
<td class="'.$p.'-2">'.self::stat_name(-2).'</td>
<td class="'.$p.'-1">'.self::stat_name(-1).'</td>
<td class="'.$p.'0">'. self::stat_name(0) .'</td>
<td class="'.$p.'1">'. self::stat_name(1) .'</td>
<td class="'.$p.'2">'. self::stat_name(2) .'</td>
<td class="'.$p.'3">'. self::stat_name(3) .' '.__('or more', 'golfturbo').'</td>
</tr></table>'
			;
	}

	public static function stableford_points($points = 36) {
		switch ($points) {
			default:
			case 36:
				return array(
					-4 => 6, //sure
					-3 => 5,
					-2 => 4,
					-1 => 3,
					 0 => 2,
					 1 => 1,
					 2 => 0,
					 3 => 0,
				);
		}
	}

	public static function scorecard_color_style() {
		$settings = GolfTurboSettings::get_settings();
		$scorelabels = array(
			 3 => 'triple-bogey',
			 2 => 'double-bogey',
			 1 => 'bogey',
			 0 => 'par',
			-1 => 'birdie',
			-2 => 'eagle',
			-3 => 'albatross',
			-4 => 'condor'
		);
		echo '<style type="text/css">';
		foreach($scorelabels as $idx => $val)
			echo '.d'.$idx.',.'.$val.'{background-color:'.$settings['color'.$idx].';}';
		echo '</style>';
	}

	public static function adjustBrightness($hex, $steps) {
	    // Steps should be between -255 and 255. Negative = darker, positive = lighter
	    $steps = max(-255, min(255, $steps));

	    // Format the hex color string
	    $hex = str_replace('#', '', $hex);
	    if (strlen($hex) == 3) {
	        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
	    }

	    // Get decimal values
	    $r = hexdec(substr($hex,0,2));
	    $g = hexdec(substr($hex,2,2));
	    $b = hexdec(substr($hex,4,2));

	    // Adjust number of steps and keep it inside 0 to 255
	    $r = max(0,min(255,$r + $steps));
	    $g = max(0,min(255,$g + $steps));  
	    $b = max(0,min(255,$b + $steps));

	    $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
	    $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
	    $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

	    return '#'.$r_hex.$g_hex.$b_hex;
	}

}

