<?php



class Data extends CI_Model {

	
    function __construct()
    {
        parent::__construct();
    }
    
  	function insert_data($user_hash,$data_name,$data_value) 
	{
		$q = $this->db->query("insert into data values(NULL," .
								$this->db->escape($user_hash) . "," .
								$this->db->escape($data_name) . "," .
								$this->db->escape($data_value) . ",unix_timestamp(),now())");
	}
	
	function get_data_last($user_hash,$data_name)
	{
		$q = $this->db->query("select * from data where data_name=" .
								$this->db->escape($data_name) . " and user_hash=" .
								$this->db->escape($user_hash) . " " .
								"order by ts desc");
		if ($q->num_rows() == 0)
			return('none');
		$row = $q->row_array();
		return($row['data_value'] . "," . $row['ts']);
	}
	
	function get_data_all($user_hash,$data_name)
	{
		$sql = "select * from data where data_name=" . $this->db->escape($data_name) . 
							" and user_hash=" . $this->db->escape($user_hash) . "order by ts asc";
		$q = $this->db->query($sql);
		if ($q->num_rows() == 0)
			return('r');
		$ret = "";
		foreach($q->result_array() as $row)
			{
				$ret = $ret . $row['data_value'] . "," . $row['ts'] . ",";
			}
		rtrim($ret,",");
		return($ret);
	}
	
	
	function cloud_save($user_hash,$name,$value)
	{
		$public_key = $this->Auth->get_public_key_from_user_hash($user_hash);
		$this->db->query("delete from output where name=" . $this->db->escape($name) . " and public_key=" . $this->db->escape($public_key));
		$sql = "insert into output values(NULL," . 
								$this->db->escape($public_key) . "," . 
								$this->db->escape($name) . "," . 
								$this->db->escape($value) . ")";
		$this->db->query($sql);
	}
	
	function cloud_load($public_key,$name)
	{
		$q = $this->db->query("select * from output where public_key=" . $this->db->escape($public_key) . " and name=" . $this->db->escape($name));
		if ($q->num_rows())
		{
			$row = $q->row_array();
			return($row['value']);
		}
	}

}
?>
