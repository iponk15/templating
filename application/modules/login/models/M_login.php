<?php defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_login extends CI_Model {
		
		// set protected
		protected $_ion_hooks;
		
		// set public
		public $_ion_limit = NULL;
		public $tables     = array();
		public $_ion_where = array();
		
        public function __construct(){
            parent::__construct();
			$this->config->load('ion_auth', TRUE);
            $this->load->model('auth/ion_auth_model');
			$this->load->library(array('ion_auth', 'form_validation'));
			
			$group_name = $this->config->item('database_group_name', 'ion_auth');
			if (empty($group_name)) {
				// By default, use CI's db that should be already loaded
				$CI =& get_instance();
				$this->db = $CI->db;
			}else{
				// For specific group name, open a new specific connection
				$this->db = $this->load->database($group_name, TRUE, TRUE);
			}
			
			$this->tables = $this->config->item('tables', 'ion_auth');
            $this->identity_column = $this->config->item('identity', 'ion_auth');
            
            $this->store_salt  = $this->config->item('store_salt', 'ion_auth');
            $this->salt_length = $this->config->item('salt_length', 'ion_auth');
            $this->hash_method = $this->config->item('hash_method', 'ion_auth');
        }
		
		public function deactivate($id = NULL){
			$this->trigger_events('deactivate');
			
			if (!isset($id)){
				return FALSE;
			}else if ($this->user()->row()->id == $id){
				return FALSE;
			}

			$activation_code       = sha1(md5(microtime()));
			$this->activation_code = $activation_code;

			$data = array(
				'user_kode_aktivasi' => $activation_code,
				'user_status'        => '0'
			);

			$this->trigger_events('extra_where');
			$this->db->update($this->tables['users'], $data, array(md56('user_id',1) => $id));
			
			$return = $this->db->affected_rows() == 1;
			
			if ($return){
				return TRUE;
			}else{
				return FALSE;
			}
		}
		
		public function trigger_events($events){
			if (is_array($events) && !empty($events)){
				foreach ($events as $event){
					$this->trigger_events($event);
				}
			}else{
				if (isset($this->_ion_hooks->$events) && !empty($this->_ion_hooks->$events)){
					foreach ($this->_ion_hooks->$events as $name => $hook){
						$this->_call_hook($events, $name);
					}
				}
			}
		}
		
		protected function _call_hook($event, $name){
			if (isset($this->_ion_hooks->{$event}[$name]) && method_exists($this->_ion_hooks->{$event}[$name]->class, $this->_ion_hooks->{$event}[$name]->method)){
				$hook = $this->_ion_hooks->{$event}[$name];

				return call_user_func_array(array($hook->class, $hook->method), $hook->arguments);
			}

			return FALSE;
		}
		
		public function user($id = NULL){
			$this->trigger_events('user');
			
			// if no id was passed use the current users id
			$id = isset($id) ? $id : $this->session->userdata('user_id');

			$this->limit('1');
			$this->order_by($this->tables['users'].'.user_id', 'desc');
			$this->where($this->tables['users'].'.user_id', $id);
			$this->users();
			
			return $this;
		}
		
		public function limit($limit){
			$this->trigger_events('limit');
			$this->_ion_limit = $limit;

			return $this;
		}
		
		public function order_by($by, $order='desc'){
			$this->trigger_events('order_by');

			$this->_ion_order_by = $by;
			$this->_ion_order    = $order;

			return $this;
		}
		
		public function where($where, $value = NULL){
			$this->trigger_events('where');

			if (!is_array($where)){
				$where = array($where => $value);
			}

			array_push($this->_ion_where, $where);

			return $this;
		}
		
		public function users($groups = NULL){
			$this->trigger_events('users');

			if (isset($this->_ion_select) && !empty($this->_ion_select)){
				foreach ($this->_ion_select as $select){
					$this->db->select($select);
				}

				$this->_ion_select = array();
			}else{
				// default selects
				$this->db->select(array(
					$this->tables['users'].'.*',
					$this->tables['users'].'.user_id as id',
					$this->tables['users'].'.user_id as user_id'
				));
			}
			
			// filter by group id(s) if passed
			if (isset($groups)){
				// build an array if only one group was passed
				if (!is_array($groups)){
					$groups = Array($groups);
				}

				// join and then run a where_in against the group ids
				if (isset($groups) && !empty($groups))
				{
					$this->db->distinct();
					$this->db->join(
						$this->tables['users_groups'],
						$this->tables['users_groups'].'.'.$this->join['users'].'='.$this->tables['users'].'.id',
						'inner'
					);
				}

				// verify if group name or group id was used and create and put elements in different arrays
				$group_ids = array();
				$group_names = array();
				foreach($groups as $group)
				{
					if(is_numeric($group)) $group_ids[] = $group;
					else $group_names[] = $group;
				}
				$or_where_in = (!empty($group_ids) && !empty($group_names)) ? 'or_where_in' : 'where_in';
				// if group name was used we do one more join with groups
				if(!empty($group_names))
				{
					$this->db->join($this->tables['groups'], $this->tables['users_groups'] . '.' . $this->join['groups'] . ' = ' . $this->tables['groups'] . '.id', 'inner');
					$this->db->where_in($this->tables['groups'] . '.name', $group_names);
				}
				if(!empty($group_ids))
				{
					$this->db->{$or_where_in}($this->tables['users_groups'].'.'.$this->join['groups'], $group_ids);
				}
			}
			
			$this->trigger_events('extra_where');
			
			// run each where that was passed
			if (isset($this->_ion_where) && !empty($this->_ion_where)){
				foreach ($this->_ion_where as $where)
				{
					$this->db->where($where);
				}

				$this->_ion_where = array();
			}

			if (isset($this->_ion_like) && !empty($this->_ion_like))
			{
				foreach ($this->_ion_like as $like)
				{
					$this->db->or_like($like['like'], $like['value'], $like['position']);
				}

				$this->_ion_like = array();
			}

			if (isset($this->_ion_limit) && isset($this->_ion_offset))
			{
				$this->db->limit($this->_ion_limit, $this->_ion_offset);

				$this->_ion_limit  = NULL;
				$this->_ion_offset = NULL;
			}
			else if (isset($this->_ion_limit))
			{
				$this->db->limit($this->_ion_limit);

				$this->_ion_limit  = NULL;
			}

			// set the order
			if (isset($this->_ion_order_by) && isset($this->_ion_order))
			{
				$this->db->order_by($this->_ion_order_by, $this->_ion_order);

				$this->_ion_order    = NULL;
				$this->_ion_order_by = NULL;
			}
			
			$this->response = $this->db->get($this->tables['users']);
			
			return $this;
		}
		
		public function row(){
			$this->trigger_events('row');

			$row = $this->response->row();

			return $row;
		}
		
		public function clear_messages(){
			$this->messages = array();

			return TRUE;
		}
		
		public function activate($id, $code = FALSE){
			$this->trigger_events('pre_activate');
			

			if ($code !== FALSE){		
				$where   = [md56('user_id',1) => $id, 'user_kode_aktivasi' => $code];
				$select  = 'user_email';
				$getData = $this->m_global->get('user',null,$where,$select);
				
				if(!empty($getData)){
					$actvd['user_kode_aktivasi'] = null;
					$actvd['user_status']	 	  = '1';
					
					$this->trigger_events('extra_where');
					$update = $this->m_global->update('user',$actvd,[md56('user_id',1) => $id]);
				}else{
					$update = FALSE;
				}
			}else{
				$actvd['user_kode_aktivasi'] = null;
				$actvd['user_status']	 	  = '1';
				
				$this->trigger_events('extra_where');				
				$update = $this->m_global->update('user',$actvd,[md56('user_id',1) => $id]);
			}
			
			if ($update !== FALSE){
				return TRUE;
			}else{
				return FALSE;
			}
		}	
		
		public function login($post, $use_sha1_override = FALSE){
			$this->trigger_events('pre_login');
		
			if(empty($post['user_email']) || empty($post['user_password'])){
				$data['status']   = '0';
				$data['messages'] = 'Email atau email harus di isi';
			}else{
				
				if($post['catpcha'] != $this->session->userdata('hasCaptcha')['capWord']){
					if (file_exists(FCPATH . "/assets/captcha/" . $this->session->userdata('hasCaptcha')['capTime'] . '.jpg')){
						unlink(FCPATH . "/assets/captcha/" . $this->session->userdata('hasCaptcha')['capTime'] . '.jpg');
					}
					
					$this->session->unset_userdata('hasCaptcha');
				
					$data['status']  = '2';
					$data['message'] = 'Capthca tidak sesuain dengan gambar';
				}else{
					if (file_exists(FCPATH . "/assets/captcha/" . $this->session->userdata('hasCaptcha')['capTime'] . '.jpg')){
						unlink(FCPATH . "/assets/captcha/" . $this->session->userdata('hasCaptcha')['capTime'] . '.jpg');
					}
					
					$this->session->unset_userdata('hasCaptcha');
				
					$this->trigger_events('extra_where');
					
					$where   = ['user_email' => $post['user_email']];
					$select  = 'user_id,user_nama,user_email,user_password,user_status,user_terakhir_login,user_salt,user_status,user_ip';
					$getData = $this->m_global->get('user',null,$where,$select);
					
					if(!empty($getData)){
						$getData  = $getData[0];
						$id       = md56($getData->user_id);
						// validasi jika user sudah melakukan aktivasi
						if($getData->user_status == '1'){
							// validasi library encrypt mana yang di pakai
							if($use_sha1_override === FALSE && $this->hash_method == 'bcrypt'){
								$bcrypt = $this->bcrypt->verify($post['user_password'],$getData->user_password);
								
								if(!empty($bcrypt)){
									// set session
									$this->trigger_events('pre_set_session');

									$session_data = array(
										'user_id'        	  => md56($getData->user_id),
										'user_nama'        	  => $getData->user_nama,
										'user_email'          => $getData->user_email,
										'user_terakhir_login' => $getData->user_terakhir_login,
									);

									$this->session->set_userdata($this->config->item('nama_session'), $session_data);
									$this->trigger_events('post_set_session');
									$this->trigger_events('extra_where');
									
									// set update terakhlogin
									$updt['user_terakhir_login'] = time();
									$this->m_global->update('user', $updt, [md56('user_id',1) => $id] );
									
									// set hapus login attempt
									if($this->config->item('track_login_attempts', 'ion_auth')){
										$old_attempts_expire_period = 86400;
										$old_attempts_expire_period = max($old_attempts_expire_period, $this->config->item('lockout_time', 'ion_auth'));
										
										if ($this->config->item('track_login_ip_address', 'ion_auth')){
											$count = time() - $old_attempts_expire_period;
											$this->m_global->delete('login_attempts',['logatt_login' => $getData->user_email, 'logatt_ip' => $getData->user_ip],'logatt_waktu < '.$count);
										}
									}
									
									// update kode pengingat (Remember Me)
									if (@$post['remember'] == 'on' && $this->config->item('remember_users', 'ion_auth')){
										$this->trigger_events('pre_remember_user');
										
										$ingat['user_kode_ingat'] = salt();
										$pengingat 				   = $this->m_global->update('user',$ingat,[md56('user_id',1) => $id]);
										
										if($pengingat){
											if($this->config->item('user_expire', 'ion_auth') === 0){
												$expire = (60*60*24*365*2);
											}else{
												$expire = $this->config->item('user_expire', 'ion_auth');
											}

											set_cookie(array(
												'name'   => $this->config->item('identity_cookie_name', 'ion_auth'),
												'value'  => $getData->user_email,
												'expire' => $expire
											));

											set_cookie(array(
												'name'   => $this->config->item('remember_cookie_name', 'ion_auth'),
												'value'  => salt(),
												'expire' => $expire
											));
										}
									}
									
									$data['status']   = '1';
									$data['messages'] = 'Berhasil Login';
								}else{
									$data['status']   = '0';
									$data['messages'] = 'Akun yang anda masukan keliru';
								}
							}else{
								// logic another magic encrypt library
								$data['status']   = '0';
								$data['messages'] = 'Belum ada library lain';
							}
						}else{
							$data['status']   = '0';
							$data['messages'] = 'Akun anda belum di aktivasi, silahkan cek email untuk aktivasi.';
						}
					}else{
						$data['status']   = '0';
						$data['messages'] = 'Data akun belum terdaftar di sistem';
					}
				}
			}
			
			return $data;
		}
		
		function lupa_password($email){
			$aktivasi_kode = '';
		
			if (function_exists("openssl_random_pseudo_bytes")){
				$aktivasi_kode = openssl_random_pseudo_bytes(128);
			}
			
			for ($i = 0; $i < 1024; $i++){
				$aktivasi_kode = sha1($aktivasi_kode . mt_rand() . microtime());
			}
			
			$key = hash_password($aktivasi_kode . $email);
			$this->forgotten_password_code = substr($key, 0, 40);
			$this->trigger_events('extra_where');
			
			$lupas['user_kode_lupapas']  = $key;
			$lupas['user_waktu_lupapas'] = time();
			
			$update = $this->m_global->update('user',$lupas,['user_email' => $email]);
			$return = $this->db->affected_rows() == 1;

			if($return){
				$where      = ['user_email' => $email];
				$select     = 'user_id,user_email,user_kode_lupapas,user_nama';
				$getData    = $this->m_global->get('user',null,$where,$select)[0];
				
				$tempEmail['identity'] 		     = 'Lupa Password';
				$tempEmail['user_kode_lupapas'] = $getData->user_kode_lupapas;
				$message    					 = $this->load->view('email/email_lupas', $tempEmail, true);
				
				$send['dari']  		= $this->config->item('user_email', 'ion_auth');
				$send['perusahaan'] = $this->config->item('site_title', 'ion_auth');
				$send['ke']			= $email;
				$send['nama']       = $getData->user_nama;
				$send['subjek']     = 'Lupa Password';
				$send['deskripsi']	= $message;
				$kirimEmail 	    = templateEmail($send);
				
				if ($kirimEmail == '1'){
					$data['status']   = '1';
					$data['flag']     = 'success';
					$data['messages'] = 'Password berhasil di rubah silahkan cek email untuk melakukan aktivasi ulang.';
				}else{
					$data['status']   = '0';
					$data['flag']     = 'danger';
					$data['messages'] = 'Aktivasi email gagal dilakukan.';
				}
			}else{
				$data['status']   = '0';
				$data['flag']     = 'danger';
				$data['messages'] = 'Email yang anda masukan belum terdaftar di sistem ini.';
			}
			
			return $data;
		}
    }
    
    /* End of file ModelName.php */
    
?>