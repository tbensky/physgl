<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('r.php');

class Welcome extends CI_Controller {

 public function __construct()
       {
            parent::__construct();
           $this->load->model('Files');
        	$this->load->model('Auth');
        	$this->load->model('Data');
           $this->load->helper('form');
           $this->load->model('Code');
       }
       
    public function index()
    {
    	$user_name = $this->session->userdata('username');
    	$user_hash = $this->Files->get_user_hash($user_name);
    	$share_hash = 'none';
    	$login_form = 'yes';
    	if (!empty($user_name))
    		{
    			if ($this->Auth->authenticate_userhash($user_hash))
					$login_form = 'no';
				$share_hash = md5($user_hash . "physgl" . time());
			}
				
    	$this->load->view('header',Array('login_form' => $login_form,'username' => $user_name));
		//$this->load->view('login');
		if ($login_form == 'yes')
			$this->load->view('physgl_intro');
		$this->load->view('codepage',Array(	"user_name" => $user_name,
											"user_hash" => $user_hash,
											"filename" => "untitled",
											"code" => "",
											"narrative" => "",
											"project_type" => "new",
											"share_hash" => $share_hash,
											"share" => false));
		$this->load->view('footer');
    }
    
    public function logout()
    {
    	$this->session->set_userdata(Array('username' => ''));
    	$this->session->sess_destroy();
    	$this->index();
    }
    
    public function authenticate()
    {
    	$username = $this->input->post("username");
    	$password = $this->input->post("password");
    	if ($this->Auth->authenticate_reset($username,$password))
    		{
    			$this->load->view('header',Array('username'=> '','login_form' => 'no'));
				$this->load->view('reset_authenticate',Array('username' => $username));
				$this->load->view('footer');
    			return;
    		}
    	if ($this->Auth->authenticate_user($username,$password))
    		$this->start($username);
    	else
    		{
    			$this->load->view('header',Array('username'=> $username,'login_form' => 'yes'));
				$this->load->view('cant_authenticate');
				$this->load->view('footer');
    		}
    		
    }

	public function start($username)
	{
		$this->load->view('header',Array('login_form' => 'no','username' => $username));
		$this->session->set_userdata(Array('username' => $username));
		$this->load->view('file_manager');
		$this->load->view('footer');
	}
	
	public function filemanager($folder_hash)
	{
		$user_name = $this->session->userdata('username');
		$user_hash = $this->Files->get_user_hash($user_name);
		
		if ($folder_hash != 'folder___top__')
			{
				$a = explode("_",$folder_hash);
				if (count($a) == 2)
					$folder_hash = $a[1];
			}
		else $folder_hash = "__top__";
		
		if ($this->Auth->authenticate_userhash($user_hash))
			$this->load->view('header',Array('login_form' => 'no','username' => $user_name));
		else $this->load->view('header',Array('login_form' => 'yes','username' => ''));
		$this->load->view('file_manager',Array('folder_hash' => $folder_hash));
		$this->load->view('footer');
	}
	
	public function save_code()
	{
		$file_name = trim(urldecode($this->input->post('filename')));
		$user_hash = $this->input->post('user_hash');
		$share = $this->input->post('share');
		$folder_hash = $this->input->post('folder_hash');
		$code = $this->input->post('code');
		$layout = $this->input->post('layout');
		if (strlen($code) > 10000)
			$code = substr($code,0,10000);
		$narrative = $this->input->post('narrative');
		if (strlen($narrative) > 10000)
			$narrative = substr($narrative,0,10000);
		$run_count = $this->input->post('run_count');
		
		
		if ($this->Auth->authenticate_userhash($user_hash) == false)
			return;
			
		if ($share === 'true')
			return;
		
		if ($this->Files->check_exists($user_hash,$file_name))
			$code_hash = $this->Files->update_code($user_hash,$file_name,$code,$narrative,$layout);
		else $code_hash = $this->Files->save_code($user_hash,$folder_hash,$file_name,$code,$narrative,$layout);
			
	}
	
	public function save_layout()
	{
		$code_hash = $this->input->post('code_hash');
		
		$code_left = $this->input->post('code_left'); 
		$code_top = $this->input->post('code_top');
		$code_height = $this->input->post('code_height');
		$code_width = $this->input->post('code_width');
		
		$graphics_left = $this->input->post('graphics_left'); 
		$graphics_top = $this->input->post('graphics_top');
		$graphics_height = $this->input->post('graphics_height');
		$graphics_width = $this->input->post('graphics_width');
		
		$console_left = $this->input->post('console_left'); 
		$console_top = $this->input->post('console_top');
		$console_height = $this->input->post('console_height');
		$console_width = $this->input->post('console_width');
		
		$xy_left = $this->input->post('xy_left'); 
		$xy_top = $this->input->post('xy_top');
		$xy_height = $this->input->post('xy_height');
		$xy_width = $this->input->post('xy_width');
		
		$button_top = $this->input->post('button_top');
		$button_left = $this->input->post('button_left');
		
		$this->db->query("delete from layout where code_hash=" . $this->db->escape($code_hash));
		$this->db->query("insert into layout values(NULL," . $this->db->escape($code_hash) . "," .
								$this->db->escape("$code_left,$code_top,$code_width,$code_height") . "," .
								$this->db->escape("$graphics_left,$graphics_top,$graphics_width,$graphics_height") . "," .
								$this->db->escape("$xy_left,$xy_top,$xy_width,$xy_height") . "," .
								$this->db->escape("$console_left,$console_top,$console_width,$console_height") . "," .
								$this->db->escape("$button_left,$button_top") . ")");
	}
	
	public function delete_files($user_hash,$folder_hash)
	{
		$list= $this->input->post('list');
		$folders = "";
		$files = "";
		foreach(explode(",",$list) as $elem)
			{
				$a = explode("_",$elem);
				if ($a[0] == "folder")
					$folders .= "'$a[1]',";
				if ($a[0] == "file")
					$files .= "'$a[1]',";
			}
		
		$files = trim($files,",");
		$folders = trim($folders,",");
		
		if (!empty($files))
			$this->db->query("delete from code where code_hash in ($files)");
		if (!empty($folders))
			{
				$this->db->query("delete from folder where folder_hash in ($folders)");
				$this->db->query("delete from folder where appear_in_hash in ($folders)");
			}
		echo $this->Files->get_folder_list($user_hash,$folder_hash);
		echo $this->Files->get_file_list($user_hash,$folder_hash);
	}
	
	public function new_project($folder_hash)
	{
		$user_name = $this->session->userdata('username');
		$user_hash = $this->Files->get_user_hash($user_name);
		$share_hash = md5($user_hash . "physgl" . time());
		$this->load->view('header',Array('login_form' => 'no','username' => $user_name));
		$this->load->view('codepage',Array(	"user_name" => $user_name,
											"user_hash" => $user_hash,
											"filename" => "untitled",
											"code" => "",
											"narrative" => "",
											"folder_hash" => $folder_hash,
											"share" => false));
		$this->load->view('footer');
	}
	
	public function load_code($code_hash)
	{
		$user_name = $this->session->userdata('username');
		$user_hash = $this->Files->get_user_hash($user_name);
		
		$a = explode("_",$code_hash);
		$code_hash = $a[1];
		
		$stuff = $this->Files->get_file_name_and_code($code_hash);
		$this->load->view('header',Array('login_form' => 'no','username' => $user_name));
		$this->load->view('codepage',Array(	"user_name" => $user_name,
											"user_hash" => $user_hash,
											"filename" => urldecode($stuff['filename']),
											"code" => $stuff['code'],
											"narrative" => $stuff['narrative'],
											"code_hash" => $code_hash,
											"folder_hash" => $stuff['folder_hash'],
											"layout" => $stuff['layout'],
											"share" => false));
		$this->load->view('footer');
	}
	
	public function share($share_hash)
	{
		
		$user_name = $this->session->userdata('username');
		$user_hash = $this->Files->get_user_hash($user_name);
		
		$stuff = $this->Files->get_code_from_share_hash($share_hash);
		if ($stuff == false)
			{
				$this->load->view('header',Array('login_form' => 'none','username' => ''));
				$this->load->view('bad_share');	
				$this->load->view('footer');
				return;
			}
			
		
		$this->load->view('header',Array('login_form' => 'none','username' => ''));
		$this->load->view('codepage',Array(	"user_name" => $user_name,
											"user_hash" => $user_hash,
											"filename" => '',
											"code" => $stuff['code'],
											"narrative" => $stuff['narrative'],
											"layout" => $stuff['layout'],
											"share_hash" => $share_hash));
		$this->load->view('footer');
	}
	
	public function get_code_text($share_hash)
	{
		$code_hash = $this->Files->get_code_hash($share_hash);
		if ($code_hash == 'none')
			{
				echo "No code with that share link found.";
				return;
			}
		$stuff = $this->Files->get_file_name_and_code($code_hash);
		echo urldecode($stuff['code']);
	}
	
	
	public function create_account()
	{
		$this->load->view('header',Array('username'=>'','login_form' => 'none','home_link' => true));
		$this->load->view('create_account',Array('captcha_error' => ''));
		$this->load->view('footer');
	
	}
	
	public function incoming_account()
	{
		 $this->load->library('form_validation');	
		$this->form_validation->set_error_delimiters('<div id="error_message">', '</div>');		 
		 $this->form_validation->set_rules('email','Email','required|valid_email|min_length[5]|is_unique[user.user_name]|trim');
		 $this->form_validation->set_rules('password', 'Password', 'required|matches[password_confirm]|min_length[4]|trim');
		$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
		

		 if ($this->form_validation->run() == TRUE)
			{
				$user_name = $this->input->post("email");
				$password = $this->input->post("password");
				$this->Auth->create_new_account($user_name,$password);
				$this->start($user_name);
			}
		else
		{
			$this->load->view('header',Array('username'=>'','login_form' => 'none'));
			$this->load->view('create_account',Array('captcha_error' => 'Please redo the captcha puzzle.'));
			$this->load->view('footer');
		}
	}
	
	public function about()
	{
		$this->load->view('header',Array('username'=>'','login_form' => 'none','home_link' => true));
		$this->load->view('about');
		$this->load->view('footer');
	}
	
	public function input($private_key,$data_name,$data_value)
	{
		$user_hash = $this->Auth->get_user_hash_from_private_key($private_key);
		if ($user_hash == 'none')
			return('Invalid private key.');
		if (!$this->Auth->authenticate_userhash($user_hash))
			{
				echo "Can't authenticate user.";
				return;
			}
		if (strlen($data_name) > 50)
			{
				echo "Data name is too long.";
				return;
			}
		if (strlen($data_value) > 50)
			{
				echo "Data value is too long.";
				return;
			}
		$this->Data->insert_data($user_hash,$data_name,$data_value);
		echo "ok";		
	}
	
	
	public function get_data($public_data_key,$how,$data_name)
	{
		$user_hash = $this->Auth->get_user_hash_from_public_key($public_data_key);
		if ($user_hash == 'none')
			return('Invalid public key');
	
		if (!$this->Auth->authenticate_userhash($user_hash))
			{
				echo "Can't authenticate user.";
				return;
			}
		if (strlen($data_name) > 50)
			{
				echo "Data name is too long.";
				return;
			}
		if ($how == "last")
			echo $this->Data->get_data_last($user_hash,$data_name);
		if ($how == "all")
			echo $this->Data->get_data_all($user_hash,$data_name);
	}
	
	public function reset_account()
	{
		 $this->load->library('form_validation');	
		$this->form_validation->set_error_delimiters('<div id="error_message">', '</div>');		 
		 $this->form_validation->set_rules('email','Email','required|valid_email|min_length[5]|!is_unique[user.user_name]|trim');
		 $this->form_validation->set_rules('password', 'Password', 'required|matches[password_confirm]|min_length[4]|trim');
		$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
		

		 if ($this->form_validation->run() == TRUE)
			{
				$user_name = $this->input->post("email");
				$password = $this->input->post("password");
				$this->Auth->reset_account($user_name,$password);
				$this->start($user_name);
			}
		else
		{
			$this->load->view('header',Array('username'=>'','login_form' => 'none'));
			$this->load->view('create_account',Array('captcha_error' => 'Please redo the captcha puzzle.'));
			$this->load->view('footer');
		}
	}
	
	public function cloud_save($name,$value)
	{
		$user_name = $this->session->userdata('username');
		$user_hash = $this->Files->get_user_hash($user_name);
		if ($this->Auth->authenticate_userhash($user_hash))
			{
				$this->Data->cloud_save($user_hash,$name,$value);
			}
	}
	
	public function cloud_load($public_key,$name)
	{
		echo $this->Data->cloud_load($public_key,$name);
	}
	
	public function share_prefs($share_hash)
	{
		$this->load->view('header',Array('username'=>'','login_form' => 'none'));
		$this->load->view('share_prefs_form',Array('share_hash' => $share_hash));
		$this->load->view('footer');
	}
	
	public function share_prefs_incoming($share_hash)
	{
		$user_name = $this->session->userdata('username');
		$user_hash = $this->Files->get_user_hash($user_name);
		if (!$this->Auth->user_owns_share($user_hash,$share_hash))
			{
				$this->load->view('header',Array('username'=>'','login_form' => 'none'));
				$this->load->view('file_manager',Array('msg' => 'You are not the owner of this project.'));
				$this->load->view('footer');
				return;
			}
		$show_code = trim($this->input->post('show_code'));
		$this->Code->set_share($share_hash,'show_code',$show_code);
		$code_hash = $this->Code->get_code_hash_from_share_hash($share_hash);
		if (!empty($code_hash))
			$this->load_code($code_hash);
		else
			{
				$this->load->view('header',Array('username'=>'','login_form' => 'none'));
				$this->load->view('file_manager',Array('msg' => 'There is no code to share yet.'));
				$this->load->view('footer');
			}
	}
	
	public function generate_share_link($user_hash)
	{
		if (!$this->Auth->authenticate_userhash($user_hash))
			{
				echo "Can't authenticate user.";
				return;
			}
			
		$code = $this->input->post('code');
		$narrative = $this->input->post('narrative');
		$layout = $this->input->post('layout');
		$share_hash = md5($user_hash . $code . $layout . $narrative);
		$this->db->query("insert into share values(NULL," . $this->db->escape($user_hash) . "," .
															$this->db->escape($share_hash) . "," .
															$this->db->escape($code) . "," .
															$this->db->escape($narrative) . "," .
															$this->db->escape($layout) . "," .
															"now())");
		echo site_url("welcome/share/$share_hash");
	}
	
	public function new_folder($user_hash)
	{
		if (!$this->Auth->authenticate_userhash($user_hash))
			{
				echo "Can't authenticate user.";
				return;
			}
		$name = $this->input->post('name');
		$parent_hash = $this->input->post('parent');
		$folder_hash = md5($user_hash . $name . time());
		$this->db->query("insert into folder values(NULL," . $this->db->escape($user_hash) . "," .
															$this->db->escape($folder_hash) . "," .
															$this->db->escape($parent_hash) . "," .
															$this->db->escape($name) . "," . 
															"now())");
		echo $parent_hash;
	}
	
	public function get_all_folders($user_hash)
	{
		if (!$this->Auth->authenticate_userhash($user_hash))
			{
				echo "Can't authenticate user.";
				return;
			}
		
		$folder_list = $this->input->post("list");
		$folder_list = str_replace("'","",$folder_list);
		$folder_list = str_replace(",",":",$folder_list);
		echo $this->Files->get_all_folders($user_hash,$folder_list);
	}
	
	public function move_file($source,$dest)
	{
		$a = explode(":",$source);
		
		foreach($a as $elem)
		{
			$parts = explode("_",$elem);
			if ($parts[0] == 'file')
				{
					$q = $this->db->query("update code set folder_hash=" . $this->db->escape($dest) . " where code_hash=" . $this->db->escape($parts[1]));
				}
			if ($parts[0] == 'folder')
				{
					$q = $this->db->query("update folder set appear_in_hash=" . $this->db->escape($dest) . " where folder_hash=" . $this->db->escape($parts[1]));
				}
		}
		$this->filemanager($dest);
	}
	
	public function copy_files($user_hash,$folder_hash)
	{
		$list= $this->input->post('list');
		$folders = "";
		$files = "";
		foreach(explode(",",$list) as $elem)
			{
				$a = explode("_",$elem);
				if ($a[0] == "folder")
					$folders .= "'$a[1]',";
				if ($a[0] == "file")
					$files .= "'$a[1]',";
			}
		
		$files = trim($files,",");
		$folders = trim($folders,",");
		
		if (!empty($files))
			{
				$this->db->query("DROP table if exists tmp");
				$this->db->query("CREATE TEMPORARY TABLE tmp SELECT * FROM code WHERE code_hash in ($files)");
				$this->db->query("UPDATE tmp SET code_id=NULL,file_name=concat(file_name,'(copy)'),code_hash=md5(rand())");
				$this->db->query("INSERT INTO code SELECT * FROM tmp");
				$this->db->query("DROP table tmp");
			}
		if (!empty($folders))
			echo "Folders cannot be copied.<p/>";
		echo $this->Files->get_folder_list($user_hash,$folder_hash);
		echo $this->Files->get_file_list($user_hash,$folder_hash);
	}

	function update_narrative($code_hash)
	{
		$narrative = trim($this->input->post("narrative"));
		$this->db->query("update code set narrative=? where code_hash=?",Array($narrative,$code_hash));
	}

	function get_narrative($code_hash)
	{
		$q = $this->db->query("select * from code where code_hash=?",Array($code_hash));
		$row = $q->row_array();
		echo $row['narrative'];
	}

	function get_narrative_from_share($share_hash)
	{
		$q = $this->db->query("select * from share where share_hash=?",Array($share_hash));
		$row = $q->row_array();
		echo $row['narrative'];
	}
	
}