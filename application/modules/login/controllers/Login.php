<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {

	
	public function __construct(){
		parent::__construct();
		$this->config->load('ion_auth', TRUE);
		$this->load->model('auth/ion_auth_model');
		$this->load->model('m_login');
		
		$this->store_salt  = $this->config->item('store_salt', 'ion_auth');
		$this->salt_length = $this->config->item('salt_length', 'ion_auth');
		$this->hash_method = $this->config->item('hash_method', 'ion_auth');

		if (file_exists(FCPATH . "/assets/captcha/" . $this->session->userdata('hasCaptcha')['capTime'] . '.jpg')){
			unlink(FCPATH . "/assets/captcha/" . $this->session->userdata('hasCaptcha')['capTime'] . '.jpg');
		}
	}
	

	public function index(){
		
		$data['captcha'] = captcha();
		$this->session->set_userdata('hasCaptcha', $data['captcha']);

		$this->load->view('login/login',$data);
	}

	public function cek_login(){
		$post = $this->input->post();
		$auth = $this->m_login->login($post);
		
		echo json_encode($auth);
	}

	public function out(){
		$this->session->sess_destroy(); 
		
		$this->ion_auth_model->trigger_events('logout');

		// delete the remember me cookies if they exist
		if (get_cookie($this->config->item('identity_cookie_name', 'ion_auth'))){
			delete_cookie($this->config->item('identity_cookie_name', 'ion_auth'));
		}
		
		if (get_cookie($this->config->item('remember_cookie_name', 'ion_auth'))){
			delete_cookie($this->config->item('remember_cookie_name', 'ion_auth'));
		}

        redirect(base_url().'login');
	}

	function refresh(){
		$data['captcha'] = captcha();
		
		if (file_exists(FCPATH . "/assets/captcha/" . $this->session->userdata('hasCaptcha')['capTime'] . '.jpg')){
			unlink(FCPATH . "/assets/captcha/" . $this->session->userdata('hasCaptcha')['capTime'] . '.jpg');
		}

		$this->session->unset_userdata('hasCaptcha');
		$this->session->set_userdata('hasCaptcha', $data['captcha']);
		
		echo $data['captcha']['capImage'];
	}

	function register(){
		$post     = $this->input->post();
		$email    = strtolower($this->input->post('email'));
		$password = $this->input->post('password');
		$aktivasi = $this->config->item('email_activation', 'ion_auth');
		$salt     = $this->store_salt ? salt() : FALSE;
		$password = hash_password($password, $salt);
	
		// data post dari inputan
		$data['user_nama']     = $post['nama'];
		$data['user_email']    = $post['email'];
		$data['user_salt']     = $salt;
		$data['user_password'] = $password;
		$data['user_tglbuat']  = date('Y-m-d H:i:s');
		$data['user_ip']	   = getUserIP();
		
		$register = $this->m_global->insert('user', $data);
		$last_id  = md56($this->db->insert_id());
		
		if($aktivasi){
			if(!$register){
				$msg['status']  = '2';
				$msg['message'] = 'Mohon maaf ada kesalahan terjadi.';

				echo json_encode($msg);
			}else{
				$activation_code 	  	 	 = sha1(md5(microtime()));
				$this->activation_code 		 = $activation_code;
				$actvd['user_kode_aktivasi'] = $activation_code;
				$actvd['user_status']	     = '0';
				$updt 						 = $this->m_global->update('user',$actvd,[md56('user_id',1) => $last_id]);
				$getData 					 = $this->m_global->get('user',null,[md56('user_id',1) => $last_id], 'user_id,user_nama,user_email,user_kode_aktivasi')[0];
				
				if($updt){
					if(!$this->config->item('use_ci_email', 'ion_auth')){
						$this->m_login->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
						$this->set_message('activation_email_successful');
						return $data;
					}else{
						
						$tempEmail['identity']   = $this->config->item('identity', 'ion_auth');
						$tempEmail['id']       	 = $getData->user_id;
						$tempEmail['activation'] = $getData->user_kode_aktivasi;
						$message 				 = $this->load->view('email/email_aktivasi', $tempEmail, true);
						
						$send['dari']  		= $this->config->item('user_email', 'ion_auth');
						$send['perusahaan'] = $this->config->item('site_title', 'ion_auth');
						$send['ke']			= $email;
						$send['nama']       = $getData->user_nama;
						$send['subjek']     = 'Aktivasi Email';
						$send['deskripsi']	= $message;
						$kirimEmail         = templateEmail($send,1);

						if ($kirimEmail == '1'){
							$msg['status']  = '1';
							$msg['message'] = 'Registrasi berhasil silahkan cek email anda';

							echo json_encode($msg);
						}else{
							echo 'kirim email gagal';
						}
					}
				}else{
					$msg['status']  = '2';
					$msg['message'] = 'Ada beberapa kendala sehingga tidak dapat registrasi, silahkan registrasi ulang.';

					echo json_encode($msg);
				}
				
			}
		}else{
			
		}
	}

	function aktivasi($id,$kode = null){
		if ($kode !== FALSE){
			$activation = $this->m_login->activate($id, $kode);
		}else if ($this->ion_auth->is_admin()){
			$activation = $this->m_login->activate($id);
		}

		if ($activation == true){
			// redirect them to the auth page
			$this->session->set_userdata('message', 'Selamat akun anda berhasil di aktivasi, silahkan login');
			redirect("login", 'refresh');
		}else{
			// redirect them to the forgot password page
			$this->session->set_userdata('message', 'Mohon maaf akun anda belum berhasil di aktivasi');
			redirect("login/forgot_password", 'refresh');
		}
	}

}

/* End of file Login.php */
/* Location: ./application/modules/login/controllers/Login.php */