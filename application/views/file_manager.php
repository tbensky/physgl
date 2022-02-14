<?php

//$folder_hash is defined

$user_name = $this->session->userdata('username');
$user_hash = $this->Files->get_user_hash($user_name);
if ($user_hash == 'none')
	{
		echo "You are not a registered user.  ";
		anchor("welcome/","Return");
		return;
	}
	
if (!empty($msg))
	echo "<div id=\"message\">$msg</div><p/>";
	
if (empty($folder_hash))
	$folder_hash = "__top__";
echo $this->Files->folder_trail($user_hash,$folder_hash);

echo "<div id=\"filemanager\">";
echo anchor("welcome/new_project/$folder_hash","New file",Array("class" => "btn btn-success mr-1"));
echo "<button class='btn btn-info' onclick=\"$('#new_folder_dialog').dialog('open');\">New folder</button>";
echo "<p/>";
echo "<div id=\"file_list\">";

echo '<table id="file_table" class="display">';
echo '<thead>';
echo "<tr>";
echo "<tr><th>File</th><th>Date</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
echo $this->Files->get_folder_list($user_hash,$folder_hash);
echo $this->Files->get_file_list($user_hash,$folder_hash);
echo "</tbody>";
echo '</table>';

echo "<div id=\"buttons\"></div>";

echo "</div>";

?>

<div id="new_folder_dialog">
Type name of new folder:
<p/>
<input type=text size=30 id=folder_name>
<p/>
<button class='btn btn-success' onclick="new_folder()">Create</button>
<button class='btn btn-info' onclick="$('#new_folder_dialog').dialog('close');">Cancel</button>
</div>

<div id="move_file_dialog">
Select destination folder: 
<p/>
<div id="file_destinations"></div>
<p/>
<button onclick="$('#move_file_dialog').dialog('close');">Cancel</button>
</div>



<script>

var delete_list = [];


$(function() {
	$('#new_folder_dialog').dialog({autoOpen: false,width: 500, title: 'New folder'});
	$('#move_file_dialog').dialog({autoOpen: false,width: 400,height: 300, title: 'Move destination'});

	
	$('#file_table').DataTable(
								{ pageLength: 50});
	
});


function add_to_delete(code_hash)
{
	var pos;
	var bid = '#button_' + code_hash;
	var state = $(bid).is(':checked');
	
	pos = delete_list.indexOf(code_hash);
	if ( pos == -1 && state == true)
		delete_list.push(code_hash);
	else if (state == false && pos != -1)
			delete_list.splice(pos,1);
	
	if (delete_list.length)
		{
			$('#buttons').html(
								'<p/><button onclick=\'delete_code();\'>Delete</button>' +
								'<button onclick=\'move_code();\'>Move</button>' + 
								'<button onclick=\'copy_code();\'>Copy</button>'
								);
		}
	else $('#buttons').html('');
}

function delete_code()
{
	list = delete_list.join(',');
	delete_list = [];
	$.post('<?php echo base_url() . "index.php/welcome/delete_files/$user_hash/$folder_hash"; ?>',
					{list: list},
					function(data) {$('#file_list').html(data); }
			);
}

function copy_code()
{
	list = delete_list.join(',');
	delete_list = [];

	$.post('<?php echo base_url() . "index.php/welcome/copy_files/$user_hash/$folder_hash"; ?>',
					{list: list},
					function(data) {$('#file_list').html(data); console.log(list);}
			);
}

function new_folder()
{
	var name = $('#folder_name').val();
	
	$.ajax({
		  type: "POST",
		  url: '<?php echo site_url() . "/welcome/new_folder/$user_hash";?>',
		  data: {name: name, parent: '<?php echo $folder_hash; ?>'},
		  success: function (msg) 
		  					{
		  						$('#new_folder_dialog').dialog('close');
		  						window.location = '<?php echo site_url() . "/welcome/filemanager/" ?>' + msg;
		  						console.log(msg); 
		  					}
		});
}

function move_code()
{
	$('#move_file_dialog').dialog('open');
	
	$.ajax({
		  type: "POST",
		  url: '<?php echo site_url() . "/welcome/get_all_folders/$user_hash";?>',
		  data: {list: delete_list.join(",")},
		  success: function (msg) 
		  					{
		  						$('#file_destinations').html(msg);
		  						delete_list = [];
		  						//window.location = '<?php echo site_url() . "/welcome/filemanager/folder_$folder_hash" ?>';
		  					}
		});
}


</script>