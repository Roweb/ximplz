<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Player extends CI_Controller
{
	private $data;
	
	public function __construct()
	{
		parent::__construct();
		$this->template->set_layout('player');
	}
	
	function index($path = '/Public')
	{
		$dirs = $mp3s = array();
		
	 	$params['key'] 		= CONSUMER_KEY;
		$params['secret'] 	= CONSUMER_SECRET;
		$params['access'] 	= array(
			'oauth_token'		=>urlencode( $this->session->userdata('oauth_token') ),
			'oauth_token_secret'=>urlencode( $this->session->userdata('oauth_token_secret') )
		);
		
		$this->load->library('dropbox', $params);
		$this->load->model('playlists_m');
		
        $account	= $this->dropbox->account();
        $everything = $this->_get($path);
        foreach($everything as $value)
        {
        	if($value['type'] == 'dir')
        	{
        		$dirs[] = array(
        			'path' => $value['path'],
        			'name' => end(explode('/', $value['path']))
        		);
        	}
        	elseif($value['type'] == 'mp3')
        	{
        		$mp3s[] = array(
        			'path' => 'http://dl.dropbox.com/u/'.$account->uid.'/'.rawurlencode(str_replace('/Public/', '', $value['path'])),
        			'name' => end(explode('/', $value['path']))
        		);
        	}
        }

        $play = $this->playlists_m->get_by('user_id', $this->session->userdata('user_id'));
        if(!$play || !$play->list)
        {
        	$play = array();
        }
        else
        {
        	$play = unserialize($play->list);
			if(!$play)
				$play = array();
        }
        
		$parent = explode('/', $path);
		array_pop($parent);
		$parent	= implode('/', $parent);
		
		if($parent != '')
		{
			$this->data->parent	= array('path' => $parent, 'name' => '..');
		}
		else 
		{
			$this->data->parent = array();
		}
		
        $this->data->dirs	= $dirs;
        $this->data->mp3s	= $mp3s;
        $this->data->play	= $play;
        
        $this->template->build('player', $this->data);
	}
	
	public function ajax_get($path = '/Public')
	{
		if($this->input->post('path'))
		{
			$path = $this->input->post('path');
		}
		
		$params['key'] 		= CONSUMER_KEY;
		$params['secret'] 	= CONSUMER_SECRET;
		$params['access'] 	= array(
				'oauth_token'=>urlencode($this->session->userdata('oauth_token')),
				'oauth_token_secret'=>urlencode($this->session->userdata('oauth_token_secret'))
		);
		$this->load->library('dropbox', $params);
		$account = $this->dropbox->account();
	
		$dirs = $mp3s = array();
		
		$everything = $this->_get($path);
		foreach($everything as $value)
		{
			if($value['type'] == 'dir')
			{
				$dirs[] = array(
					'path' => $value['path'],
					'name' => end(explode('/', $value['path']))
				);
			}
			elseif($value['type'] == 'mp3')
			{
				$mp3s[] = array(
					'path' => 'http://dl.dropbox.com/u/'.$account->uid.'/'.rawurlencode(str_replace('/Public/', '', $value['path'])),
					'name' => end(explode('/', $value['path']))
				);
			}
		}
	
		$parent = explode('/', $path);
		array_pop($parent);
		$parent	= implode('/', $parent);
		
		if($parent != '')
		{
			$this->data['parent']	= array('path' => $parent, 'name' => '..');
		}
		
		$this->data['dirs']		= $dirs;
		$this->data['mp3s']		= $mp3s;

		echo json_encode($this->data);
	}	
	
	public function ajax_save()
	{
		$data = $this->input->post('data');
		foreach($data as &$song)
		{
			$song['name'] = preg_replace("#<span[^>]*>(.*)</span>#isU", "", $song['name']);
		}
		
		$this->load->model('playlists_m');
		$save = $this->playlists_m->save($this->session->userdata('user_id'), $data);
		
		echo $save; exit;
	}
		
	public function _get($path, $recursive = false)
	{
		$data = array();
		
		$files = $this->dropbox->metadata($path);
		if(!empty($files->contents))
		{
			foreach($files->contents as $key => $value) if($value->is_dir || @$value->mime_type=='audio/mpeg')
			{
				$data[] = array(
					'type'	=> $value->is_dir ? 'dir' : 'mp3',
					'path'	=> $value->path,
					'childs'=> $recursive ? $this->_get($value->path, true) : array()
				);
			}
		}
		
		return $data;
	}
}