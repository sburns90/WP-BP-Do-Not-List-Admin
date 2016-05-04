<?php
	/*
	Plugin Name: SB - BuddyPress Don't List Admin
	Plugin URI: http://StephenBurns.net
	Description: Hide BuddyPress admin user from member listing and doesn't count admin when counting members or group members. Used code snippets from Brajesh Singh and philipstancil at buddypress.org.
	Version: 1.0
	Author: Stephen Burns
	Author URI: http://www.StephenBurns.net/
	License: GPL2
	*/
   	
	/*  Copyright 2015 Stephen Burns  (email : Stephen@StephenBurns.net)
	
    	This program is free software; you can redistribute it and/or modify
    	it under the terms of the GNU General Public License, version 2, as 
    	published by the Free Software Foundation.
	
    	This program is distributed in the hope that it will be useful,
    	but WITHOUT ANY WARRANTY; without even the implied warranty of
    	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    	GNU General Public License for more details.
	
    	You should have received a copy of the GNU General Public License
    	along with this program; if not, write to the Free Software
    	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
  	
	/* TO DO:
	* UNFISHIED (4-18-16) Clean up code.
	* UNFISHIED (4-18-16) Add initial values for the variables and also try to reduce the number of variables used.
	* UNFISHIED (4-18-16) Fix group user count in search results. 
	* */
	
	/* NOTE:
	* Code concept from http://www.hongkiat.com/blog/buddypress-tips-resources/
	* */
?>

<?php
add_action('bp_ajax_querystring','bpdev_exclude_users',20,2);
function bpdev_exclude_users($qs=false,$object=false)
{
//list of users to exclude
$excluded_user='1';//comma separated ids of users whom you want to exclude
/*
// Remove the comment lines here to only disable admin listing ONLY on the members page.
//if($object!='members')//hide for members only
//{
//return $qs;
//}	
*/

$args=wp_parse_args($qs);

//check if we are listing friends?, do not exclude in this case
if(!empty($args['user_id']))
return $qs;

if(!empty($args['exclude']))
$args['exclude']=$args['exclude'].','.$excluded_user;
else
$args['exclude']=$excluded_user;

$qs=build_query($args);
return $qs;
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
