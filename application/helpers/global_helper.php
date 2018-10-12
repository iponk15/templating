<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function pre( $var, $exit = null )
	{
		$CI = &get_instance();
		echo '<pre>';
		if ( $var == 'lastdb' ){
			print_r($CI->db->last_query());
		} else if ( $var == 'post' ){
			print_r($CI->input->post());
		} else if ( $var == 'get' ){
			print_r($CI->input->get());
		} else {
			print_r( $var );
		}
		echo '</pre>';

		if ( $exit )
		{
			exit();
		}
	}

	function generate_menu(){
		$CI 	   = &get_instance();
		$select    = 'menu_id,menu_nama,menu_controllers,menu_is_primary,menu_url,menu_sub_menu,menu_status,';
		$data_menu = $CI->m_global->get('cuti_config_menu',null,null,$select);

		return $data_menu;
	}

	function menu_role($param){
		$CI 	   = &get_instance();
		$select    = 'group_id,group_role_id,group_nama,group_deskripsi,group_ip_temp,group_data,group_controller,group_status';
		$data_menu = $CI->m_global->get('user_group',null,null,$select, ['group_role_id' => $param]);

		return $data_menu;
	}

	function isJSON($string){
	   return is_string($string) && is_array(json_decode($string, true)) ? true : false;
	}

	function genPass($string,$password){
		$CI 	= &get_instance();
		$key    = $CI->config->item('secret_key');
		$salt   = sha1(md5($string).$key);
		$hasil  = md5($salt.$password);

		return $hasil;
	}

	function is_ccanalyst($nopeg){
		$CI    = &get_instance();
		$hasil = $CI->m_global->count('cuti_admin',null,['admin_nopeg' => $nopeg]);

		return $hasil;
	}

	function info_ses($user_id){
		$CI   =& get_instance();
		$data = @$CI->m_global->get('user',NULL,['user_id' => $user_id])[0];

		return $data;
	}

	function getUserIP(){
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else{
            $ip = $remote;
        }

        return $ip;
    }

    function state_color($param){
    	$list = ['1'=>'success','2'=>'warning','3'=>'danger','4'=>'info','5'=>'primary','6'=>'secondary'];

    	return $list[$param];
	}
	
	function olah($data){
		$decode  = json_decode($data);
		$menu    = [];
		$submenu = [];

		foreach ($decode as $key) {
			if($key->parent == '#'){
				$menu[] = ['text' => $key->text, 'ID' => $key->ID];
			}else{
				$submenu[] = ['text' => $key->text, 'parent' => $key->parent];
			}
		}

		pre($menu);
		pre($submenu);

		// return $hasil;
		
	}
		function some($data){
		$decode  = json_decode($data);
		$menu    = [];
		$submenu = [];

		foreach ($decode as $key) {
			if($key->parent == '#'){
				$menu[] = ['text' => $key->text, 'ID' => $key->ID,'child'=>array()];
			}else{
				$submenu[] = ['text' => $key->text, 'parent' => $key->parent];
			}
		}

		$ret = array($menu,$submenu);
		return $ret;

		// return $hasil;
		
	}

	function tab_menu ($primary, $controller, $uri, $paramChild){
		if ($primary == 1 && $controller == $uri) {
			return 'm-menu__item--active m-menu__item--active-tab';
		} elseif($primary == null && $controller != $uri && $paramChild==true) {
			return 'm-menu__item--active-tab';
		}
		
	}

	function valid_email($param) {
		return !!filter_var($param, FILTER_VALIDATE_EMAIL);
	}

	function umur($tanggal){
		// Format bulan-tanggal-tahun
		$birthDate = explode("-", $tanggal);
		$umur      = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));
		
		return $umur;
	}

	function unique_multidim_array($array, $key) { 
		$temp_array = array(); 
		$i 			= 0; 
		$key_array  = array(); 
		
		foreach($array as $val) { 
			if (!in_array($val[$key], $key_array)) { 
				$key_array[$i]  = $val[$key]; 
				$temp_array[$i] = $val; 
			} 
			$i++; 
		} 
		
		return $temp_array; 
	} 

	function getSession(){
		$CI   =& get_instance();
		return $CI->session->userdata('hotel_session');
	}

	function templateEmail($param){
		$email = new \SendGrid\Mail\Mail(); 
		$email->setFrom('template.metronic@gmail.com', $param['perusahaan']);
		$email->setSubject($param['subjek']);
		$email->addTo($param['ke'],$param['nama']);
		// $email->addContent("text/plain", 'test email');
		$email->addContent("text/html", $param['deskripsi']);
		
		// Email untuk attachment
		// $path 		  = explode('/',$param['path']);
		// $file_encoded = base64_encode(file_get_contents($param['path']));
		
		// $email->addAttachment(
		    // $file_encoded, //file path
		    // "application/pdf", //Header file
		    // end($path), //Name file yg dikirim di attachment
		    // "attachment"
		// );

		$sendgrid = new \SendGrid("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");

		try {
			// pre($response->statusCode() );
			// pre($response->headers());
			// pre($response->body());
			// print $response->statusCode() . "\n";
			// print_r($response->headers());
			// print $response->body() . "\n";
			$response = $sendgrid->send($email);

			if($response->statusCode() == '202'){
				return $data['status'] = '1';
			}else{
				return $data['status'] = '0';
			}
			
		} catch (Exception $e) {
			echo 'Caught exception: '. $e->getMessage() ."\n";
			return $data['status'] = '0';
		}
	}

	function captcha(){
		$CI   =& get_instance();
		$vals = array(
			'img_path'      => FCPATH.'assets/captcha/',
			'img_url'       => base_url('assets/captcha'),
			'img_width'     => '200',
			'img_height'    => 40,
			'expiration'    => 7200,
			'word_length'   => 3,
			'font_size'     => 300,
			'img_id'        => 'Imageid',
			'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
	
			// White background and border, black text and red grid
			'colors'        => array(
					'background' => array(255, 255, 255),
					'border'     => array(255, 255, 255),
					'text'       => array(0, 0, 0),
					'grid'       => array(255, 40, 40)
			)
		);
		
		$cap    = create_captcha($vals);
		$result = ['capImage' => $cap['image'], 'capWord' => $cap['word'], 'capTime' => $cap['time']];
		
		return $result;
	}

	function log_activity($menu,$note){
		$CI    =& get_instance();

		$role 				 = [1 => 'Superadmin',2 => 'Kilo-kilo',3 => 'AA',4 => 'DM'];
		$logA['logAct_menu'] = $menu;
		$logA['logAct_emp']  = getSession()->user_nopeg;
		$logA['logAct_note'] = $note;
		$logA['logAct_bo']   = (empty(getSession()->user_bo) ? null : getSession()->user_bo );
		$logA['logAct_role'] = $role[getSession()->user_role];
		$logA['logAct_ip']   = getUserIP();
		$logA['logAct_date'] = date('Y-m-d H:i:s');
		$query 				 = $CI->m_global->insert('has_log_activity', $logA);

		return true;
	}

	function salt(){
		$CI 		     =& get_instance();
		$CI->salt_length = $CI->config->item('salt_length', 'ion_auth');
		$raw_salt_len    = 16;
		$buffer 	     = '';
		$buffer_valid    = FALSE;

		if (function_exists('random_bytes')){
			$buffer = random_bytes($raw_salt_len);
			if ($buffer){
				$buffer_valid = TRUE;
			}
		}

		if (!$buffer_valid && function_exists('mcrypt_create_iv') && !defined('PHALANGER')){
			$buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
			if ($buffer){
				$buffer_valid = TRUE;
			}
		}

		if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes')){
			$buffer = openssl_random_pseudo_bytes($raw_salt_len);
			if ($buffer){
				$buffer_valid = TRUE;
			}
		}

		if (!$buffer_valid && @is_readable('/dev/urandom')){
			$f    = fopen('/dev/urandom', 'r');
			$read = strlen($buffer);
			
			while ($read < $raw_salt_len){
				$buffer .= fread($f, $raw_salt_len - $read);
				$read    = strlen($buffer);
			}
			
			fclose($f);
			
			if ($read >= $raw_salt_len){
				$buffer_valid = TRUE;
			}
		}

		if (!$buffer_valid || strlen($buffer) < $raw_salt_len){
			$bl = strlen($buffer);
			for ($i = 0; $i < $raw_salt_len; $i++){
				if ($i < $bl){
					$buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
				}else{
					$buffer .= chr(mt_rand(0, 255));
				}
			}
		}

		$salt = $buffer;

		// encode string with the Base64 variant used by crypt
		$base64_digits   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
		$bcrypt64_digits = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$base64_string 	 = base64_encode($salt);
		$salt 			 = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);
		$salt 		     = substr($salt, 0, $CI->salt_length);

		return $salt;
	}
	
	function hash_password($password, $salt = FALSE, $use_sha1_override = FALSE){
		$CI =& get_instance();
		$CI->salt_length = $CI->config->item('salt_length', 'ion_auth');
		$CI->hash_method = $CI->config->item('hash_method', 'ion_auth');
		
		if (empty($password)){
			return FALSE;
		}

		// bcrypt
		if ($use_sha1_override === FALSE && $CI->hash_method == 'bcrypt'){
			return $CI->bcrypt->hash($password);
		}

		if ($CI->store_salt && $salt){
			return sha1($password . $salt);
		}else{
			$salt = $CI->salt();
			return $salt . substr(sha1($salt . $password), 0, -$CI->salt_length);
		}
	}

	function md56($param,$tipe = null,$jml = null){
		if(empty($tipe)){
			return substr(md5($param),0, ( empty($jml) ? 6 : $jml  ) );

			substr(md5($param),0, 6 );
		}else{
			return 'SUBSTRING(md5('.$param.'),true,6)';
		}
	}

	function sidebar_menu( $menu, $url ){
		foreach ( $menu as $key => $value ){
			$mexpand = '';
			if (is_array($value['link'])) {
				foreach ($value['link'] as $Fkey => $Fvalue) {
					if (is_array($Fvalue['link'])) {
						if (in_array($url, array_column($Fvalue['link'], 'path'))) {
							$mexpand='m-menu__item--submenu m-menu__item--open m-menu__item--expanded';
						} else {
							$mexpand = '';
						}
					}
				}
			}

			echo '<li class="m-menu__item '.
			(is_array($value['link']) ? 
			(in_array($url, array_column($value['link'], 'path')) ? 
				'm-menu__item--submenu m-menu__item--open m-menu__item--expanded' 
				: 
				$mexpand
			)
			: 
			($value['path'] == $url ? 'm-menu__item--active' : '')).
				'" aria-haspopup="true">

				<a '.(is_array($value['link']) ? 'href="javascript:void(0)" class="m-menu__link m-menu__toggle parent"' : 'href="'.base_url($value['link']).'" class="m-menu__link ajaxify"').'>
				<span class="m-menu__item-here"></span>
				<i class="m-menu__link-icon flaticon-'.$value['icon'].'"></i>
				<span class="m-menu__link-text">'.$value['name'].'</span>'.

				(is_array($value['link']) ? '<i class="m-menu__ver-arrow la la-angle-right"></i>' : '').
				'</a>';
				sub_menu( $value, $url, '2' );
			echo '</li>';
		}
	}

	function sub_menu( $value, $url, $segment ){
		/*
			Mempunyai sub menu atau tidak
			untuk menampilkan sub link
		*/
	
		if ( is_array($value['link']) )
		{
			echo '<div class="m-menu__submenu">
					<span class="m-menu__arrow"></span>
						<ul class="m-menu__subnav">';
	
			$CI =& get_instance();
			// pre($url);
			// $sub_url = $CI->uri->segment(2);
			// pre($sub_url,1);
	
			/*
				Menampilkan sub menu
			*/
			// foreach ( $value['link'] as $kSub => $kValue ){
				
			// }
			// exit;
			foreach ( $value['link'] as $kSub => $kValue )
			{
				if (is_array($kValue['link'])) {
					if (in_array($url, array_column($kValue['link'], 'path'))) {
						$mopen = 'm-menu__item--open';                    
					}else{
						$mopen = '';
					}
				}
				$sub_url = $CI->uri->segment($segment);
				echo '<li class="m-menu__item '.(
					(
						is_array($kValue['link']) ?
						'm-menu__item--submenu '.$mopen
						:
						(
							$kValue['link'] == $url.'/'.$sub_url ?
								'm-menu__item--active'
							:
							($kValue['link'] == $url ? 'm-menu__item--active' : '')
						)
					)
				).'" aria-haspopup="true">';
				/*
					Jika path parent sama dengan uri sebelumnya
					dan path sekarang sama dengan uri sekarang
				*/
			   
			   echo '<a href="'.(is_array($kValue['link']) ? 'javascript:void(0)' : base_url($kValue['link'])).'" class="'.(is_array($kValue['link']) ? 'm-menu__link m-menu__toggle' : 'm-menu__link ajaxify submenu').'">
					<i class="m-menu__link-icon flaticon-'.$kValue['icon'].'">
						<span></span>
					</i>
					<span class="m-menu__link-text">'.$kValue['name'].'</span>'.
					(is_array($kValue['link']) ? '<i class="m-menu__ver-arrow la la-angle-right"></i>' : '').
				'</a>';
			   if (is_array($kValue['link'])) {
				   sub_menu_child($kValue, $url, '2');
			   }
				echo "</li>";
			}
	
			echo '</ul></div>';
		}
	}
	
	function sub_menu_child( $value, $url, $segment ){
		$CI =& get_instance();
		$sub_url = $CI->uri->segment($segment);
		if (is_array($value['link'])) {
			echo '<div class="m-menu__submenu">
					<span class="m-menu__arrow"></span>
					<ul class="m-menu__subnav">';
	
			foreach ($value['link'] as $Mvalue) {
				echo '<li class="m-menu__item '.(
					$Mvalue['link'] == $url.'/'.$sub_url ?
					'm-menu__item--active'
					:
					($Mvalue['link'] == $url ? 
					'm-menu__item--active' 
					: 
					'')
					).'" aria-haspopup="true" m-menu-link-redirect="1">
						<a href="'.base_url($Mvalue['link']).'" class="m-menu__link ajaxify submenu subparent1">
							<i class="m-menu__link-icon flaticon-'.$Mvalue['icon'].'">
								<span></span>
							</i>
							<span class="m-menu__link-text">'.$Mvalue['name'].'</span>
						</a>
					</li>';
			}
	
			echo '</ul>
				</div>';
		}
	}

?>