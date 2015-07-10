<?php
   /*
   Plugin Name: BP Do Not List Admin
   Plugin URI: https://www.github.com/sburns90/WP-BP-Do-Not-List-Admin/
   Description: Hide BuddyPress admin user from member listing and don't count admin when counting members or group members.
   Version: 1.0
   Author: Stephen Burns
   Author URI: http://www.StephenBurns.net/
   License: GPL2
*/
?>

<?php
/* TODO: Fix group user count in search results.
*/

	add_action('bp_ajax_querystring','bpdev_exclude_users',20,2);

	function bpdev_exclude_users($qs=false,$object=false) {
		//list of users to exclude
		$excluded_user='1';//comma separated ids of users whom you want to exclude
		
		/*
		// Remove the comment lines here to only disable admin listing ONLY on the members page.
		//if($object!='members') {//hide for members only
		//	return $qs;
		//}	
		*/

		$args=wp_parse_args($qs);

		// Are we showing a users friends? If so do not exclude super admin.
		if(!empty($args['user_id'])) {
			return $qs;
		}
		else(!empty($args['exclude'])) {
			$args['exclude']=$args['exclude'].','.$excluded_user;
		}
		else{
			$args['exclude']=$excluded_user;
			$qs=build_query($args);
			return $qs;
		}	
	}

	//Remove admin from member count
	add_filter('bp_get_total_member_count','bpdev_members_correct_count');
	function bpdev_members_correct_count($count){
		$excluded_users_count=1; //change it to the number of users you want to exclude
		return $count-$excluded_users_count;
	}

	//Remove admin from group member count
	add_filter('bp_get_group_member_count','bpdev_group_members_correct_count');
	function bpdev_group_members_correct_count($total_member_count){
		$excluded_users_count=1; //change it to the number of users you want to exclude
		return $total_member_count-$excluded_users_count;
	}
?>
