<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dbx extends CI_Controller
{
	private $data;
	
    public function index()
	{
		$dropbox = unserialize($this->session->userdata('dropbox'));
		if($dropbox)
		{
			$this->session->set_userdata('oauth_token', $dropbox['oauth_token']);
			$this->session->set_userdata('oauth_token_secret', $dropbox['oauth_token_secret']);
			
			redirect('player');
		}
		else 
		{			
			redirect('/dbx/access_dropbox');
		}
	}
	
	// This method should not be called directly, it will be called after 
    // the user approves your application and dropbox redirects to it
	public function access_dropbox()
	{
		if(!$this->session->userdata('user_id'))
		{
			redirect('/login');
		}
		
		$dropbox = unserialize($this->session->userdata('dropbox'));
		if($dropbox)
		{
			redirect('/dbx');
		}
		else 
		{
			$params['key']		= CONSUMER_KEY;
			$params['secret']	= CONSUMER_SECRET;
			
			$this->load->library('dropbox', $params);
			
			if(!$this->input->get('uid'))
			{
				$data = $this->dropbox->get_request_token( site_url("/dbx/access_dropbox") );
				$this->session->set_flashdata('token_secret', $data['token_secret']);
				
				redirect($data['redirect']);
			}
			
			$token = $this->session->flashdata('token_secret');
			if($token)
			{
				$oauth = $this->dropbox->get_access_token( $token );			
				if($oauth)
				{
					$this->load->model('login/login_m');
					
					$dropbox = serialize(array(
						'oauth_token'			=> $oauth['oauth_token'],
						'oauth_token_secret'	=> $oauth['oauth_token_secret']	
					));
					
					$this->session->set_userdata('dropbox', $dropbox);					
					$this->login_m->update($this->session->userdata('user_id'), array('dropbox' => $dropbox));
				}
			}
		}
		
        redirect('/dbx');
	}	
}