<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
	private $web;
	
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('general');
		$this->load->model('cemail');
		$this->load->model('bo/model_admin');
		
		$this->setWeb ();  
	}
	
	private function setWeb() {
		
		set_error_handler(
				create_function(
						'$severity, $message, $file, $line',
						'throw new ErrorException($message, $severity, $severity, $file, $line);'
				)
		);
		
		$q=$this->model_admin->get_empresa_multinivel();
		$web = $q[0]->web;
		
		try {
			$s = file_get_contents($web);
			#log_message('ERROR',strlen($s));
			$this->web = $web;
		}
		catch (Exception $e) {
			$this->web = 'auth/login/';
			#log_message('ERROR','page not found : '.$web);
		}
		
		restore_error_handler();
	}

	function index()
	{
		if ($message = $this->session->flashdata('message'))
		{
			$this->load->view('auth/general_message', array('message' => $message));
		}
		else
		{
			redirect('auth/login/');
		}
	}

	function reset_view(){
	
		#$this->db->query('drop view if exists items');

        $db = $this->getSchema();

        $type = "VIEWS";
        $table = "items";
        $where = "and table_schema like '$db'";
        $view = $this->getInfoSchema($table,$type,$where);

        if($view)
            return true;
		
		$this->db->query("CREATE VIEW items AS
    SELECT 
        m.id AS id,
        m.sku AS sku,
        m.puntos_comisionables AS puntos_comisionables,
        (CASE
            WHEN
                (m.id_tipo_mercancia = 1)
            THEN
                (SELECT 
                        producto.nombre
                    FROM
                        producto
                    WHERE
                        (producto.id = m.sku))
            WHEN
                (m.id_tipo_mercancia = 2)
            THEN
                (SELECT 
                        servicio.nombre
                    FROM
                        servicio
                    WHERE
                        (servicio.id = m.sku))
            WHEN
                (m.id_tipo_mercancia = 3)
            THEN
                (SELECT 
                        combinado.nombre
                    FROM
                        combinado
                    WHERE
                        (combinado.id = m.sku))
            WHEN
                (m.id_tipo_mercancia = 4)
            THEN
                (SELECT 
                        paquete_inscripcion.nombre
                    FROM
                        paquete_inscripcion
                    WHERE
                        (paquete_inscripcion.id_paquete = m.sku))
            WHEN
                (m.id_tipo_mercancia = 5)
            THEN
                (SELECT 
                        membresia.nombre
                    FROM
                        membresia
                    WHERE
                        (membresia.id = m.sku))
            ELSE 'No define'
        END) AS item,
        (CASE
            WHEN
                (m.id_tipo_mercancia = 1)
            THEN
                (SELECT 
                        producto.id_grupo
                    FROM
                        producto
                    WHERE
                        (producto.id = m.sku))
            WHEN
                (m.id_tipo_mercancia = 2)
            THEN
                (SELECT 
                        servicio.id_red
                    FROM
                        servicio
                    WHERE
                        (servicio.id = m.sku))
            WHEN
                (m.id_tipo_mercancia = 3)
            THEN
                (SELECT 
                        combinado.id_red
                    FROM
                        combinado
                    WHERE
                        (combinado.id = m.sku))
            WHEN
                (m.id_tipo_mercancia = 4)
            THEN
                (SELECT 
                        paquete_inscripcion.id_red
                    FROM
                        paquete_inscripcion
                    WHERE
                        (paquete_inscripcion.id_paquete = m.sku))
            WHEN
                (m.id_tipo_mercancia = 5)
            THEN
                (SELECT 
                        membresia.id_red
                    FROM
                        membresia
                    WHERE
                        (membresia.id = m.sku))
            ELSE ''
        END) AS categoria,
        (CASE
            WHEN
                (m.id_tipo_mercancia = 1)
            THEN
                (SELECT 
                        a.id_red
                    FROM
                        (producto p
                        JOIN cat_grupo_producto a)
                    WHERE
                        ((a.id_grupo = p.id_grupo)
                            AND (p.id = m.sku)))
            WHEN
                (m.id_tipo_mercancia = 2)
            THEN
                (SELECT 
                        a.id_red
                    FROM
                        (servicio s
                        JOIN cat_grupo_producto a)
                    WHERE
                        ((a.id_grupo = s.id_red)
                            AND (s.id = m.sku)))
            WHEN
                (m.id_tipo_mercancia = 3)
            THEN
                (SELECT 
                        a.id_red
                    FROM
                        (combinado o
                        JOIN cat_grupo_producto a)
                    WHERE
                        ((a.id_grupo = o.id_red)
                            AND (o.id = m.sku)))
            WHEN
                (m.id_tipo_mercancia = 4)
            THEN
                (SELECT 
                        a.id_red
                    FROM
                        (paquete_inscripcion q
                        JOIN cat_grupo_producto a)
                    WHERE
                        ((a.id_grupo = q.id_red)
                            AND (q.id_paquete = m.sku)))
            WHEN
                (m.id_tipo_mercancia = 5)
            THEN
                (SELECT 
                        a.id_red
                    FROM
                        (membresia b
                        JOIN cat_grupo_producto a)
                    WHERE
                        ((a.id_grupo = b.id_red)
                            AND (b.id = m.sku)))
            ELSE ''
        END) AS red,
        m.id_tipo_mercancia AS id_tipo_mercancia
    FROM
        mercancia m");

	return true;
	

	}

	/**
	 * Login user on the site
	 *
	 * @return void
	 */
	function login()
	{
        $empresa=$this->model_admin->val_empresa_multinivel();
        $g_key = $this->general->issetVar($empresa,"g_captcha");

		$this->reset_view();
		if ($this->tank_auth->is_logged_in())
		{																		// logged in
			$id   = $this->tank_auth->get_user_id();
            $this->general->isFineRegistry($id, $g_key);

            $tipo = $this->general->get_tipo($id);
			$tipo = $this->general->issetVar($tipo,"id_tipo_usuario",2);
			
			$this->accesos ( $tipo );	

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/send_again/');

		} else {

			$data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND
					$this->config->item('use_username', 'tank_auth'));
			$data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');

			$this->form_validation->set_rules('login', 'Login', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			//$this->form_validation->set_rules('remember', 'Remember me', 'integer');

			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts', 'tank_auth') AND
					($login = $this->input->post('login'))) {
				$login = $this->security->xss_clean($login);
			} else {
				$login = '';
			}
			$data['errors'] = array();
			if(isset($_POST['login']))
			{
				if(is_numeric($_POST['login']))
				{
					$id_login = $this->general->get_user($_POST['login']);
                    if($id_login)	{
						$_POST['login']=$this->general->issetVar($id_login,"username");
					}
				}
			}

				
			if ($this->form_validation->run()) {	

				if($this->general->isBlocked()){
					$data['errors']['blocked'] = true;
					$this->template->set('data',$data);
					$this->template->build('auth/login');
				}else {
					$recovery = $this->getRecovery ($login);
					
					if ($this->tank_auth->login(
							$this->form_validation->set_value('login'),
							$this->form_validation->set_value('password'),
							$this->form_validation->set_value('remember'),
							$data['login_by_username'],
							$data['login_by_email'])) {								// success
					
						$id   = $this->tank_auth->get_user_id();
                        $this->general->isFineRegistry($id, $g_key);
						$tipo = $this->general->get_tipo($id);
						if(!$tipo) {
                            $this->template->build('auth/login');
                            return true;
                        }

						$tipo = $this->general->issetVar($tipo,"id_tipo_usuario",2);
							
						$estatus = $this->general->get_status($id);
						$estatus = $this->general->issetVar($estatus,"id_estatus",1);
							
						if($estatus == '1'){
							$this->general->unlocked();
							
							$this->accesos ( $tipo );		
							
						}else{
							$this->logout2();
					
						}
					
					} else if(!$recovery){
						//echo $recovery;exit();
						redirect('/auth/forgot_password');
						
					}else{
						
						$data['errors']['attempts']= $this->general->addAttempts();
						
						$errors = $this->tank_auth->get_error_message();
						if (isset($errors['banned'])) {								// banned user
							$this->_show_message($this->lang->line('auth_message_banned').' '.$errors['banned']);
					
						} elseif (isset($errors['not_activated'])) {				// not activated user
							redirect('/auth/send_again/');
					
						} else {													// fail
							foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
						}
					}

				}

			}
            $this->template->set('g_key',$g_key);
			$this->template->set('data',$data);
			$this->template->build('auth/login');
		}
	}
	
	
	private function accesos($tipo) {
		if($tipo==2){
			$this->cobrarRetenciones();
		}
		
		$accesos = array(
				1 => '/bo/dashboard',
				2 => '/ov/dashboard',
				3 => '/bos/dashboard',
				4 => '/boc/dashboard',
				5 => '/bol/dashboard',
				6 => '/boo/dashboard',
				7 => '/boa/dashboard',
				8 => '/CEDI/home',
				9 => '/Almacen/home',
		);
		
		if($accesos[$tipo]){
			redirect($accesos[$tipo]);
		}
	}

	
	private function getRecovery($login) {
		$query = "select recovery from users where id = '".$login."' or username = '".$login."' or email = '".$login."'";
		$q = $this->db->query($query);
		$q=$q->result();
		$recovery = $q ? $q[0]->recovery : false;
		return $recovery ;
	}


	/**
	 * Logout user
	 *
	 * @return void
	 */
	function logout()
	{
		
		$id   = $this->tank_auth->get_user_id();
		if($id==null){
			redirect($this->web);
		}
		$this->general->update_login($id);

		$this->tank_auth->logout(); // Destroys session
	    $this->session->sess_create();
	    //$this->_show_message($this->lang->line('auth_message_logged_out'));
		//$this->load->view('auth/login');
		redirect($this->web);
	}

	function logout2()
	{
		$id   = $this->tank_auth->get_user_id();
		if($id==null){
			redirect('/auth/login');
		}
		$this->general->update_login($id);

		$this->tank_auth->logout(); // Destroys session
	    $this->session->sess_create();
	    //$this->_show_message($this->lang->line('auth_message_logged_out'));
		$login = "Cuenta Bloqueada";
		$this->template->set('login',$login);
		$this->template->build('auth/login');
	}


	/**
	 * Register user on the site
	 *
	 * @return void
	 */
	function register()
	{
		/*if ($this->tank_auth->is_logged_in()) {									// logged in
			echo "hola";
			exit();
			redirect('');
		} else*/if ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
					//echo "falla en auth";	
					redirect('/auth/send_again/');

		} elseif (!$this->config->item('allow_registration', 'tank_auth')) {	// registration is off
			$this->_show_message($this->lang->line('auth_message_registration_disabled'));

		} else {
			$use_username = $this->config->item('use_username', 'tank_auth');
			if ($use_username) {
				$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
			}
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
			$email_activation = $this->config->item('email_activation', 'tank_auth');

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->create_user(
						$use_username ? $this->form_validation->set_value('username') : '',
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password'),
						$email_activation))) {									// success

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');
					$last_id=$this->general->get_last_id();
					$data['lst_id']=$last_id;
					if ($email_activation) {									// send "activate" email
						$data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;
						$id_nuevo_usr=$this->db->query("select id from users order by id desc limit 1");
						$data['id']=$id_nuevo_usr[0]->id;
						
						//$this->send_email_activate( $data['email'], $data);
						$this->cemail->send_email(2, $data['email'], $data);
						unset($data['password']); // Clear password (just for any case)

						$this->_show_message($this->lang->line('auth_message_registration_completed_1'));

					} else {

						if ($this->config->item('email_account_details', 'tank_auth')) {	// send "welcome" email
							
							//$this->_send_email('welcome', $data['email'], $data);
							$this->cemail->send_email(1, $data['email'], $data);
						}
						unset($data['password']); // Clear password (just for any case)

						$this->_show_message($this->lang->line('auth_message_registration_completed_2').' '.anchor('/auth/login/', 'Login'));
					}
				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$data['use_username'] = $use_username;
			$this->load->view('auth/register_form', $data);
			
		}
	}

	/**
	 * Send activation email again, to the same or new email address
	 *
	 * @return void
	 */
	function send_again()
	{
		
		if (!$this->tank_auth->is_logged_in(FALSE)) {							// not logged in or activated
			redirect('/auth/login/');

		} else {
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->change_email(
						$this->form_validation->set_value('email')))) {			// success

					$data['site_name']	= $this->config->item('website_name', 'tank_auth');
					$data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

					$this->_send_email('activate', $data['email'], $data);

					$this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			
			$this->load->view('auth/send_again_form', $data);
		}
	}

	/**
	 * Activate user account.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function activate()
	{
		$user_id		= $this->uri->segment(3);
		$new_email_key	= $this->uri->segment(4);

		// Activate user
		if ($this->tank_auth->activate_user($user_id, $new_email_key)) {		// success
			$this->tank_auth->logout();
			$this->_show_message($this->lang->line('auth_message_activation_completed').' '.anchor('/auth/login/', 'Login'));

		} else {																// fail
			$this->_show_message($this->lang->line('auth_message_activation_failed'));
		}
	}

	/**
	 * Generate reset code (to change password) and send it to user
	 *
	 * @return void
	 */
	function forgot_password()
	{
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('');

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/auth/send_again/');

		} else {
			$this->form_validation->set_rules('login', 'Email or login', 'trim|required|xss_clean');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->forgot_password(
						$this->form_validation->set_value('login')))) {

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with password activation link
					//$this->_send_email('forgot_password', $data['email'], $data);
					$this->cemail->send_email(6, $data['email'], $data);
					
					redirect('/auth/login/');

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->template->set("data",$data);
			$this->template->build('auth/forgot_password_form');
		}
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user).
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_password()
	{
		$user_id		= $this->uri->segment(3);
		$new_pass_key	= $this->uri->segment(4);

		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
		$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

		$data['errors'] = array();

		if ($this->form_validation->run()) {								// validation ok
			if (!is_null($data = $this->tank_auth->reset_password(
					$user_id, $new_pass_key,
					$this->form_validation->set_value('new_password')))) {	// success

				$data['site_name'] = $this->config->item('website_name', 'tank_auth');

				// Send email with new password
				//$this->_send_email('reset_password', $data['email'], $data);
				$this->cemail->send_email(7, $data['email'], $data); 
				
				$this->_show_message($this->lang->line('auth_message_password_changed').' '.anchor('/auth/login/', 'Login'));

			} else {														// fail
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		} else {
			// Try to activate user by password key (if not activated yet)
			if ($this->config->item('email_activation', 'tank_auth')) {
				$this->tank_auth->activate_user($user_id, $new_pass_key, FALSE);
			}

			if (!$this->tank_auth->can_reset_password($user_id, $new_pass_key)) {
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		}
		$this->load->view('auth/reset_password_form', $data);
	}

	/**
	 * Change user password
	 *
	 * @return void
	 */
	function change_password()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/auth/login/');

		} else {
			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->change_password(
						$this->form_validation->set_value('old_password'),
						$this->form_validation->set_value('new_password'))) {	// success
						//$this->_show_message($this->lang->line('auth_message_password_changed'));
						$this->session->set_flashdata('success', 'La nueva contraseña es : '.$this->form_validation->set_value('new_password'));
            redirect('/ov/perfil_red/perfil');

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
		$id = $this->tank_auth->get_user_id();
		$style = $this->general->get_style($id);
		$this->template->set("style",$style);
      	$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/ov/header');
        $this->template->set_partial('footer', 'website/ov/footer');
		    $this->template->build('auth/change_password',$data);
		}
	}

	/**
	 * Change user email
	 *
	 * @return void
	 */
	function change_email()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/auth/login/');

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->set_new_email(
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password')))) {			// success

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with new email address and its activation link
					//$this->_send_email('change_email', $data['new_email'], $data);
					$this->cemail->send_email(3, $data['new_email'], $data);
					$this->_show_message(sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/change_email_form', $data);
		}
	}

	/**
	 * Replace user email with a new one.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_email()
	{
		$user_id		= $this->uri->segment(3);
		$new_email_key	= $this->uri->segment(4);

		// Reset email
		if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) {	// success
			$this->tank_auth->logout();
			$this->_show_message($this->lang->line('auth_message_new_email_activated').' '.anchor('/auth/login/', 'Login'));

		} else {																// fail
			$this->_show_message($this->lang->line('auth_message_new_email_failed'));
		}
	}

	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @return void
	 */
	function unregister()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/auth/login/');

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->delete_user(
						$this->form_validation->set_value('password'))) {		// success
					$this->_show_message($this->lang->line('auth_message_unregistered'));

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/unregister_form', $data);
		}
	}

	/**
	 * Show info message
	 *
	 * @param	string
	 * @return	void
	 */
	function _show_message($message)
	{
		$this->session->set_flashdata('message', $message);
		redirect('/auth/');
	}
	
	/**
	 * Show info message
	 *
	 * @param	string
	 * @return	void
	 */
	function show_dialog()
	{
		echo $_POST['message'];
	}

	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	function _send_email($type, $email, &$data)
	{
		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->to($email);
		$this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name', 'tank_auth')));
		$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));
		$this->email->send();
	}
	function send_email_activate($email,&$data)
	{
		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->to($email);
		$this->email->subject('Te Damos la Bienvenida');
		$this->email->message($this->load->view('email/activate-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('email/activate-txt', $data, TRUE));
		$this->email->send();
	}
	/**
	 * Create CAPTCHA image to verify user as a human
	 *
	 * @return	string
	 */
	function _create_captcha()
	{
		$this->load->helper('captcha');

		$cap = create_captcha(array(
			'img_path'		=> './'.$this->config->item('captcha_path', 'tank_auth'),
			'img_url'		=> base_url().$this->config->item('captcha_path', 'tank_auth'),
			'font_path'		=> './'.$this->config->item('captcha_fonts_path', 'tank_auth'),
			'font_size'		=> $this->config->item('captcha_font_size', 'tank_auth'),
			'img_width'		=> $this->config->item('captcha_width', 'tank_auth'),
			'img_height'	=> $this->config->item('captcha_height', 'tank_auth'),
			'show_grid'		=> $this->config->item('captcha_grid', 'tank_auth'),
			'expiration'	=> $this->config->item('captcha_expire', 'tank_auth'),
		));

		// Save captcha params in session
		$this->session->set_flashdata(array(
				'captcha_word' => $cap['word'],
				'captcha_time' => $cap['time'],
		));

		return $cap['image'];
	}

	/**
	 * Callback function. Check if CAPTCHA test is passed.
	 *
	 * @param	string
	 * @return	bool
	 */
	function _check_captcha($code)
	{
		$time = $this->session->flashdata('captcha_time');
		$word = $this->session->flashdata('captcha_word');

		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		if ($now - $time > $this->config->item('captcha_expire', 'tank_auth')) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
			return FALSE;

		} elseif (($this->config->item('captcha_case_sensitive', 'tank_auth') AND
				$code != $word) OR
				strtolower($code) != strtolower($word)) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Create reCAPTCHA JS and non-JS HTML to verify user as a human
	 *
	 * @return	string
	 */
	function _create_recaptcha()
	{
		$this->load->helper('recaptcha');

		// Add custom theme so we can get only image
		$options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'tank_auth'));

		return $options.$html;
	}

	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return	bool
	 */
	function _check_recaptcha()
	{
		$this->load->helper('recaptcha');

		$resp = recaptcha_check_answer($this->config->item('recaptcha_private_key', 'tank_auth'),
				$_SERVER['REMOTE_ADDR'],
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']);

		if (!$resp->is_valid) {
			$this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}
	

	function cobrarRetenciones(){
		
		if($this->general->getRetencionesMes())
			return false;
		
		$retenciones=$this->general->getRetenciones();
		$now = new \DateTime('now');
		$valorRetencion=0;
		
		foreach ($retenciones as $retencion){

			if($retencion->duracion=='ANO'){
				$valorRetencion=$retencion->porcentaje/12;
			}
			else if($retencion->duracion=='SEM'){
				$valorRetencion=$retencion->porcentaje*4;
			}else if($retencion->duracion=='MES'){
				$valorRetencion=$retencion->porcentaje;
			}
			$datos = array(
					'descripcion' => $retencion->descripcion,
					'valor' => $valorRetencion,
					'mes' => $now->format('m'),
					'ano' => $now->format('Y'),
					'id_afiliado' => 0
			);
			$this->db->insert("cat_retenciones_historial",$datos);
		}
	}

    public function getSchema()
    {
        $q = $this->db->query("select database() db");
        $q = $q->result();
        $db = $q[0]->db;
        return $db;
    }

    public function getInfoSchema($table,$type = "TABLES",$where = "")
    {
        $query = "SELECT * FROM information_schema.$type
					WHERE table_name like '$table' $where";

        $q = $this->db->query($query);
        $q = $q->result();
        return $q;
    }

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
