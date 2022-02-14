<?php

class Code extends CI_Model {

	
    function __construct()
    {
        parent::__construct();
    }
    
    function handle_get_all_data($code)
	{
		//#get_all_data(js-time-var,js-value-var,var-name,public-key)
		$key = "//#get_all_data";
		$lines = explode("\n",urldecode($code));
		$prefix = "";
		foreach($lines as $line)
			{
				if (substr($line,0,strlen($key)) == $key)
					{
						$pos = strpos($line,"(");
						$pos1 = strpos($line,")");
						$args = substr($line,$pos+1,$pos1-$pos-1);
						$a = explode(",",$args);
						$a[0] = trim($a[0],"'");//time-var
						$a[1] = trim($a[1],"'");//value-var
						$a[2] = trim($a[2],"'");//variable-name
						$a[3] = trim($a[3],"'");//public-key
						$user_hash = $xthis->Auth->get_user_hash_from_public_key($a[3]);
						$data = $this->Data->get_data_all($user_hash,$a[2]);
						
						$prefix .= "var " . $a[0] . "= [";
						$items = explode(",",$data);
						for($i=1;$i<count($items);$i += 2)
							$prefix .= $items[$i] . ",";
						$prefix = rtrim($prefix,",");
						$prefix .= "]; ";
						
						$prefix .= "var " . $a[1] . "= [";
						$items = explode(",",$data);
						for($i=0;$i<count($items);$i += 2)
							$prefix .= $items[$i] . ",";
						$prefix = rtrim($prefix,",");
						$prefix .= "]; ";
					}
			}
			
		return($prefix);
	}
    
    function get_code_hash_from_share_hash($share_hash)
    {
    	$q = $this->db->query("select * from code where share_hash=" . $this->db->escape($share_hash));
    	if ($q->num_rows() == 0)
    		return("");
    	$row = $q->row_array();
    	return($row['code_hash']);
    }
    
    function set_share($share_hash,$name,$value)
    {
    	$this->db->query("delete from share_settings where share_hash=" .
    						$this->db->escape($share_hash) . " and name=" . $this->db->escape($name));
    	$this->db->query("insert into share_settings values(NULL," . $this->db->escape($share_hash) . "," .
    																$this->db->escape($name) . "," .
    																$this->db->escape($value) . ")");
    }
    
    function get_share($share_hash,$name)
    {
    	$q = $this->db->query("select * from share_settings where share_hash=" . $this->db->escape($share_hash) . " and name=" . $this->db->escape($name));
    	if ($q->num_rows() == 1)
    		{
    			$row = $q->row_array();
    			return($row['value']);
    		}
    	return("");
    }
  
}
?>
