<?php



class Auth extends CI_Model {

	
    function __construct()
    {
        parent::__construct();
    }
    
    function get_salt()
    {
    	return("do_graphics_555!!!091_with_physgl");
    }
    
    function authenticate_user($username,$password)
    {
    	$pw_hash = md5($this->Auth->get_salt() . $username . $password);
    	
    	$q = $this->db->query("select * from user where user_name='$username' and auth_hash='$pw_hash'");
    	if ($q->num_rows() == 0)
    		return(false);
    	return(true);
    }
    
     function authenticate_reset($username,$password)
    {
    	$pw_hash = md5($this->Auth->get_salt() . $username . $password);
    	
    	$q = $this->db->query("select * from user where user_name='$username' and auth_hash='reset'");
    	if ($q->num_rows() == 0)
    		return(false);
    	return(true);
    }
    
    function authenticate_userhash($user_hash)
    {
    	$q = $this->db->query("select * from user where user_hash='$user_hash'");
    	if ($q->num_rows() == 0)
    		return(false);
    	return(true);
    }
    
    function get_username_from_userhash($user_hash)
    {
    	$q = $this->db->query("select * from user where user_hash='$user_hash'");
    	if ($q->num_rows() == 0)
    		return('unknown');
    	$row = $q->row_array();
    	return($row['user_name']);
    }
    
    
    function create_new_account($username,$password)
    {
    	$hash = md5($this->Auth->get_salt() . $username . $password);
    	$user_hash = md5(time() . $username . $this->Auth->get_salt());
    	
    	$q = $this->db->query("insert into user values(NULL," .
    				$this->db->escape($username) . "," .
    				$this->db->escape($hash) . "," .
    				$this->db->escape($user_hash) . ")");
    }
    
     function reset_account($username,$password)
    {
    	$hash = md5($this->Auth->get_salt() . $username . $password);
    	$user_hash = md5(time() . $username . $this->Auth->get_salt());
    	$this->db->query("update user set auth_hash='$hash' where user_name='$username'");
    }
    
    function get_data_keys($user_hash)
    {
    	if (strlen($user_hash) != 32)
    		return(Array('private_key' => 'You must create an account and be logged on.','public_key' => 'You must be logged on'));
    	$q = $this->db->query("select * from io where user_hash='$user_hash'");
    	if ($q->num_rows() == 0)
    		{
    			$private_key = md5($user_hash . "physgl@VoLtAgEs!" . time());
    			$public_key = md5(rand() . $user_hash . "physgl@private_key@VoLtAgEs!");
    			$this->db->query("insert into io values(NULL," . $this->db->escape($user_hash) . "," . 
    															$this->db->escape($public_key) . "," .
    															$this->db->escape($private_key) . ")");
    			return(Array('private_key' => $private_key,'public_key' => $public_key));
    		}
    	$row = $q->row_array();
    	return(Array('private_key' => $row['private_key'],'public_key' => $row['public_key']));
    }
    
    function get_public_key_from_user_hash($user_hash)
    {
    	$q = $this->db->query("select public_key from io where user_hash='$user_hash'");
    	if ($q->num_rows() == 0)
    		return('none');
    	$row = $q->row_array();
    	return($row['public_key']);
    }
    
    function get_user_hash_from_private_key($private_key)
    {
    	$q = $this->db->query("select user_hash from io where private_key='$private_key'");
    	if ($q->num_rows() == 0)
    		return('none');
    	$row = $q->row_array();
    	return($row['user_hash']);
    }
    
    function get_user_hash_from_public_key($public_key)
    {
    	$q = $this->db->query("select user_hash from io where public_key='$public_key'");
    	if ($q->num_rows() == 0)
    		return('none');
    	$row = $q->row_array();
    	return($row['user_hash']);
    }
    
    function user_owns_share($user_hash,$share_hash)
    {
    	$q = $this->db->query("select * from code where user_hash=" . 
    								$this->db->escape($user_hash) . " and share_hash=" .
    								$this->db->escape($share_hash));
    	return($q->num_rows() == 1);
    }	
							
   
}
?>