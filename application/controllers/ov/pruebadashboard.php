<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class pruebadashboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		//$this->isViewBonos();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('web_personal');		
		
	}
	
	function index()
	{	
		if (!$this->tank_auth->is_logged_in())
		{																		// logged in
			redirect('/auth');
		}
        
        //$this->template->set("menu",$puntos_red_total);

		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        //$this->template->set_partial('header', 'website/ov/header');
        $this->template->set_layout('website/main');

        //$this->template->set_partial('header','website/ov/menu');
        $this->template->set("menu",'website/ov/menu');
        //$this->template->set("lista",'website/ov/lista');
        //$this->template->set_partial('header','website/ov/lista');
		//$this->template->build('website/ov/menu');
		$this->template->build('website/ov/pruebadashboard');
	}
}
