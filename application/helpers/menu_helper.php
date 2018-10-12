<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * [Fungsi untuk generate menu]
 * @return [array] [Array menu]
 */

	function menu(){
		$CI             =& get_instance();
		$welcome 		= ['name' => 'welcome', 'icon' => 'line-graph', 'path' => 'welcome', 'link' => 'welcome'];
		$master_data	= [
							'name' => 'Master Data', 'icon' => 'symbol', 'path' => '',
							'link' => [
								['name' => 'User management', 'icon' => 'user-settings', 'path' => 'user', 'link' => 'user'],
								['name' => 'Hotel', 'icon' => 'home', 'path' => 'hotel', 'link' => 'hotel'],
								['name' => 'GL Account', 'icon' => 'user', 'path' => 'gl_account', 'link' => 'gl_account'],
								['name' => 'Cost Center Map', 'icon' => 'coins', 'path' => 'ccmap', 'link' => 'ccmap'],
								['name' => 'Station', 'icon' => 'map-location', 'path' => 'station', 'link' => 'station'],
								['name' => 'Room Mapping', 'icon' => 'home-2', 'path' => 'room_map', 'link' => 'room_map'],
								['name' => 'Branch Office', 'icon' => 'placeholder', 'path' => 'bo', 'link' => 'bo']
							]
						];

		$menu 			= [$welcome, $master_data];

		return $menu;
	}


/* End of file menu_helper.php */
/* Location: ./application/helpers/menu_helper.php */