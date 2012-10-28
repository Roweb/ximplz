<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller
{
	private $user;
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('facebook', $this->config->item('facebook'));
		$this->load->model('login_m');
	}
	
	public function index()
	{
		if($this->session->userdata('user_id'))
		{
			//$this->user = $this->login_m->get_by('facebook', $logged);
			redirect('/player');
		}
		else
		{
			// not logged in
			try 
			{
				$me	= $this->facebook->api('/me');
				if($me) // if user accepted app
				{
					$this->user = $this->login_m->get_by('facebook', $me['id']);
					if($this->user) // if user exists
					{
						$this->session->set_userdata('user_id', $this->user->id);
						$this->session->set_userdata('dropbox', $this->user->dropbox);
						
						if($this->user->dropbox)
						{
							redirect('/player');
						}
						else
						{
							redirect('/dbx');
						}
					}
					else // user accepted our app, but for some reason it does not exist in db
					{
						redirect(base_url() . 'login/facebook'); // re-register user.
					}
				}
				else // user did not accept our app, redirect to request access
				{
					redirect(base_url() . 'login/facebook');
				}
			}
			catch(FacebookApiException $e)
			{
				redirect(base_url() . 'login/facebook');
			}
			
		}
	}
	
	public function facebook()
	{
		// check if user accepted our app
		try 
		{
			$me = $this->facebook->api('/me');
			if($me)
			{
				// we have user permission, check if user exists
				$user = $this->login_m->get_by('facebook', $me['id']);
				if(!$user) // register user
				{
					$user = array(
						'username'	=> $me['username'],
						'name'		=> $me['name'],
						'email'		=> $me['email'],
						'facebook'	=> $me['id']	
					);
					$insert = $this->login_m->insert($user);
					if($insert)
					{
						redirect(base_url() . '/login');
					}
				}
				else 
				{
					redirect(base_url() . '/login');
				}
			}
		}
		catch(FacebookApiException $e)
		{
			// user did not accept our app or is not logged in facebook
			$url = $this->facebook->getLoginUrl(array(
					'scope'			=> 'email',
					'redirect_uri'	=> base_url() . '/login'
			));
			
			redirect($url);
		}
	}
	
	private function get_session()
	{
		$fb = $this->config->item('facebook');
		return 'fb_' . $fb['appId'] . '_user_id';
	}	
}