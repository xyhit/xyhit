<?php
	namespace LaneWeChat\Core;
	require ("./core/responseinitiative.lib.php");
	use LaneWeChat\Core\ResponseInitiative;
	
	class SendNewsToMany{
		public static function send($user, $array){
			return ResponseInitiative::news($user, $array);
		}
	}
?>