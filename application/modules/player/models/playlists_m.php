<?php
class Playlists_m extends MY_Model
{
	protected $_table = 'playlists';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function save($user_id, $data)
	{
		if(is_numeric($user_id))
		{
			parent::delete_by(array('user_id' => $user_id));
			return parent::insert(array('user_id' => $user_id, 'list' => serialize($data)));
		}
		return false;
	}
}