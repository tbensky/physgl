<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Narrative</h4>
      </div>
      <div class="modal-body">
      	<textarea id="narrative_text" class="form-control" rows=10></textarea>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-success" onclick="update_narrative_text()">Update</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<div id="share_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Share your project</h4>
      </div>
      <div class="modal-body">
      	<input id="share_link" class="form-control" rows=10></textarea>
      	<p class="small text-muted">This share link is for your code, narrative and window layout as they exist at this instant.  Capturing any future
	changes will require a new share link.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<?php
//$user_name, $user_hash, $filename, $code, $narrative, $code_hash, $share_hash, $share and are defined
include('config_paths.php');

if (empty($code_hash)) 
	$code_hash=""; 

if (empty($layout)) 
	$layout=""; 
	
if (empty($narrative))
	$narrative = "";
	
if (empty($share_hash))
	{
		$share_hash = "";
		$share = "false";
	}
else $share = "true";

if (empty($layout))
{
	$layout = <<<EOT
	[{id: "code_dialog", x: 100, y: 100, height: 450, width: 300},
	{id: "graphics_dialog", x: 500, y: 191.25, height: 488, width: 504},
	{id: "xy_dialog", x: 0, y: 0, height: 0, width: 400},
	{id: "console_dialog", x: 0, y: 0, height: 0, width: 0},
	{id: "narrative_dialog", x: 0, y: 0, height: 0, width: 0}];
EOT;
}

if (empty($folder_hash))
	$folder_hash = "__top__";

?>


<?php
	$guest = true;
	
	if (!empty($user_hash) && $this->Auth->authenticate_userhash($user_hash) == true)
		$guest = false;
	
		
	if ($guest == true && $share == false)
		{
			$filename="";
			$code="";
			$code_hash="";
			$share = true;
			$user_hash="";
		}
?>

<div id="status"></div>

<p/>
<?php
	if ($share === "false")
		{
			$url_files = site_url("welcome/filemanager/$folder_hash");
			echo<<<EOT



	<div class="row">
		<div class="col-md-3">
		    <label for="filename" class="sr-only">Project name</label>
		    <input type="text" class="form-control" id="filename" name=filename placeholder="Project name">
	    </div>
	  
	  	<button class="btn btn-success btn-sm mr-1" onclick="save_code()">Save</button>

		 <span class="dropdown">
		 	<button class="btn btn-secondary btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">More<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<li><a class="dropdown-item" href="#" onclick="_PHYSGL_go_to_url('$url_files')">Files</a></li>
			<li><a class="dropdown-item" href="#" onclick="_PHYSGL_share_link()">Share</a></li>
			<li><a class="dropdown-item" href="#" onclick="edit_narrative('dialog')">Edit narrative</a></li>
			</ul>
		</span>

		<span id="save_update"></span>
	</div>


EOT;		
		}	
?>

	
		<div id="code_dialog" title='Code'>
				<div id="error_message"></div>
			<textarea id="code_editor"></textarea>
		</div>

		<div id="xy_dialog" title='XY-graph'>
			<div id="graph"></div>
		</div>
		
		<div id="graphics_dialog" title='Graphics' style="background: #000000">
			<div id="xrun_button"></div>
			<div id="interact_small"></div>
			<div id="pmode_small"></div>
		</div>
		
		
		
		<div id="console_dialog" title='Console'>
			<div id="console">
			</div>
		</div>

		<div id="narrative_dialog" title='Narrative'>
			<div id="narrative">
			</div>
		</div>
				

		


	
	<div id="share_dialog">
	Share link:
	<input type=text id=share_link size=60>
	<br/><br/>
	<span id="small text-muted">This share link is for your code as it exists at this instant.  Future
	changes to your code will not be included, and will require you to generate a 
	new share link.</span>
	</div>
	
	

	

	<div id="dump"></div>
	<div id="taskbar"></div>


<script>


var renderer3d, scene3d, camera3d, light3d, controls3d;
var o3d = [];
var width=800, height=600;
var fps_rate = 25;
var myCodeMirror;
var start_time;
var _PHYSGL_stop_request = false;
var _PHYSGL_in_animation_loop = false;
var _PHYSGL_pause = false;
var run_count = 0;
var _PHYSGL_vector_scale = 3.0, _PHYSGL_arrow_thickness = 1.0, _PHYSGL_vector_label_scale = 1.0;
var _PHYSGL_graphs_loaded = false;
var _PHYSGL_graph_data;
var _PHYSGL_chart;
var _PHYSGL_axes_labels;
var _PHYSGL_console_border = false;
var _PHYSGL_orbit = {x: 0, y: 0, down: false, theta: 0.0, phi:0.0, r: 100, control_key: false, reset_y: false, cur_z: 0.0, down_count: 0, theta_offset: 0, phi_offset: 0};
var _PHYSGL_spine_data = [];
var _PHYSGL_mouse = {x:0, y:0};
var _PHYSGL_data = {value: 0,time_stamp: 0, status: 'idle', access: 0, last_returned_time_stamp: 0};
var _PHYSGL_cloud = {last_access: 0};
var _PHYSGL_interact = '#interact_small';
var _PHYSGL_axes_range = {xmax: 'auto', xmin: 'auto', ymin: 'auto', ymax: 'auto'};
var _PHYSGL_single_step = false;
var _PHYSGL_textures; // initialized in init_webgl()
var _PHYSGL_rotate = {x:0, y:0, z:0};
var _PHYSGL_clear_skip;
var _PHYSGL_frame_count;
var _PHYSGL_kept_objects = [];
var _PHYSGL_loop = false;
var _PHYSGL_title_bar_height = 70; //see .css file

$(function() {


//$("#narrative_dialog").prependTo("body");


 var layout = <?php echo $layout; ?>;
   
	myCodeMirror = CodeMirror.fromTextArea(document.getElementById("code_editor"),
											{lineNumbers: true,matchBrackets: true, theme: "default", height: 'auto', viewportMargin: Infinity});
	
  
	
	var i;




	for(i=0;i<layout.length;i++)
	{
		var L = layout[i];
		var id = L['id'];
		var htmlid = '#' + id;
		var offsetx = 0;
		var offsety = 0;

		
		offsety =  _PHYSGL_title_bar_height;
		offsetx = 30;
		console.log(htmlid);

		$(htmlid).dialog({
								autoOpen: true,
								position: {my: 'left+'+L['x'] + ' top', at: 'left top+' + L['y']},
								width: L['width'] + parseInt(offsetx),
								height: parseInt(L['height']) + parseInt(offsety),
								beforeClose: function(){return false;}
								});
		$(htmlid).dialogExtend({"minimizable": true,"collapsable": true});	
		if (L['x'] == 0 && L['y'] == 0)
			$(htmlid).dialogExtend("minimize");

		if (id == "graphics_dialog")
		{
			$('#pmode_small').height($('#graphics_dialog').height()); 
			$('#pmode_small').width($('#graphics_dialog').width());
		}

	}


	$('#graphics_dialog').on("dialogresize",function(event,ui)
												{
													$('#pmode_small').height(ui.size.height); 
													$('#pmode_small').width(ui.size.width);
												}
											);
	$('#graphics_dialog').on("dialogopen",function(event,ui)
												{
													$('#pmode_small').height($('#graphics_dialog').height()); 
													$('#pmode_small').width($('#graphics_dialog').width());
													
												}
											);
	//$('#graphics_dialog').dialog('open');
	programming_buttons('stopped');
	
	
						
	// $('#run_button').on("dialogopen",function(event,ui)
	// 											{
	// 												$('#run_button').height($('#run_button').height()); 
	// 												$('#run_button').width($('#run_button').width());
	// 											}
	// 										);
						
	
   
    						
    $('#xy_dialog').on("dialogresize",function(event,ui)
												{
													$('#graph').height($('#xy_dialog').height()); 
													$('#graph').width($('#xy_dialog').width());
												}
											);
											
	 
    $('#console_dialog').on("dialogresize",function(event,ui)
												{
													$('#console').height($('#console_dialog').height()); 
													$('#console').width($('#console_dialog').width());
												}
											);

	
	
							
	$('#code_dialog').on("dialogresize",function(event,ui)
												{
													myCodeMirror.setSize(ui.size.width,ui.size.height);
													myCodeMirror.refresh();
												}
											);
											
	$('#code_dialog').on("dialogopen",function(event,ui)
												{
													myCodeMirror.setSize($('#code_dialog').width(),$('#code_dialog').height());
													myCodeMirror.refresh();
												}
											);
	//$('#code_dialog').dialog('open');
						
	
	initial_code = '<?php echo addslashes($code); ?>';
	myCodeMirror.setValue(unescape(decodeURIComponent(initial_code)));
    myCodeMirror.refresh();   
	$('#filename').val('<?php echo $filename; ?>');
						
	
	
	
	// $('#console_dialog').dialogExtend({"minimizable": true,"collapsable": true});
	// $('#xy_dialog').dialogExtend({"minimizable": true,"collapsable": true});
	
	// $('#code_dialog').dialogExtend({"minimizable": true,"collapsable": true});
	// $('#graphics_dialog').dialogExtend({"minimizable": true,"collapsable": true});

	// $('#console_dialog').dialogExtend("minimize");
	// $('#xy_dialog').dialogExtend("minimize");
	
	$('#share_dialog').dialog({autoOpen: false,width: 500, title: 'Share'});

   	render_narrative('<?php echo $share; ?>','<?php echo $share_hash; ?>','<?php echo $code_hash; ?>');	

});//end of onLoad

  
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(_PHYSGL_graphs_loaded_callback);





$('#filename').keydown(function() {run_count = 0;});

function render_narrative(share_tf,share_hash,code_hash)
{
	console.log(share_tf,share_hash,code_hash);

	if (share_tf == "true")
		get_narrative_from_share(share_hash);
	else edit_narrative('headless');
}

function edit_narrative(mode)
{
	if (mode == 'dialog')
		$('#myModal').modal("show");

	var url = '<?php echo site_url() . "/welcome/get_narrative/$code_hash"; ?>';
	$.ajax({
				method: 'POST',
				url: url,
				success: function(e) 
								{
									console.log(e);
									$('#narrative_text').val(e);
									$('#narrative').html(e);
									MathJax.typeset();
								}
  	});
}

function get_narrative_from_share(share_hash)
{
	var url = '<?php echo site_url() . "/welcome/get_narrative_from_share/"; ?>' + share_hash;
	$.ajax({
				method: 'POST',
				url: url,
				success: function(e) 
								{
									console.log(e);
									$('#narrative').html(e);
									MathJax.typeset();
								}
  	});
}

function update_narrative_text()
{
	var narr = $('#narrative_text').val();
	$('#narrative').html(narr);
	var url = '<?php echo site_url() . "/welcome/update_narrative/$code_hash"; ?>';
	$.ajax({
				method: 'POST',
				url: url,
				data: {narrative: narr}
  	});
	MathJax.typeset();


}
function run_button_to_top()
{
	var offset = 7*($('#top_matter').height() + $('#top-bar').height())/2;
	$('#run_button').offset({top: offset,left: 10});
}


function _PHYSGL_graphs_loaded_callback()
{
	_PHYSGL_graphs_loaded = true; 
}


function run_success(data)
{
	var pos;
	var a = data.split('___');
	
	for(i=0;i<a.length-1;i++)
		{
			if (a[i] == '_PHYSGL_new_name')
				$('#filename').val(decodeURIComponent(a[i+1]));
			if (a[i] == '_PHYSGL_new_share_hash')
				window.location=a[i+1];
		}
}

function get_layout_array()
{
	var dlgs = ["code_dialog","graphics_dialog","xy_dialog","console_dialog","narrative_dialog"];
	var i;
	var layout = [];
	
	for(i=0;i<dlgs.length;i++)
	{
		z = [];
		var id = "#" + dlgs[i];
		z = {id: dlgs[i],'x': $(id).offset().left, 'y': $(id).offset().top, 'height': $(id).height(), 'width': $(id).width()};
		layout.push(z);
	}
	return(layout);

}

function save_code()
{
	var code= myCodeMirror.getValue();
	var filename = encodeURI($('#filename').val());
	var narrative = $('#narrative_text').val();
	var layout = get_layout_array();
	

	$.post('<?php echo base_url(); ?>index.php/welcome/save_code',
					{	
						user_hash: '<?php echo $user_hash; ?>',
						filename: filename,
						folder_hash: '<?php echo $folder_hash; ?>',
						narrative: narrative, 
						code: encodeURI(code),
						run_count: run_count,
						layout: JSON.stringify(layout)
						});
	
	$('#save_update').html('Saved.').fadeIn(1000).fadeOut(2000);

	
}

function key_pressed()
{
	$('#save_update').html('Not saved.');
	_PHYSGL_orbit.down = false;
}

function run(where,single_step)
{
	init_webgl(where);
	o3d = [];
	code = myCodeMirror.getValue();
	var dest, width, height;
	var i;
	var tcode;
	
	_PHYSGL_pause = false;
	_PHYSGL_single_step = false;
	if (single_step == true)
		{
			_PHYSGL_single_step == true;
			_PHYSGL_pause = true;
		}
			
	_PHYSGL_stop_request = false;
	if (_PHYSGL_in_animation_loop == true)
		_PHYSGL_stop_request = true;
	_PHYSGL_orbit.down_count = 0;
	_PHYSGL_spline_data = [];
	_PHYSGL_data.access = 0;
    _PHYSGL_data.status = 'idle';
    _PHYSGL_vector_scale = 3.0, 
    _PHYSGL_arrow_thickness = 1.0, 
    _PHYSGL_vector_label_scale = 1.0;
    _PHYSGL_frame_count = 0;
    _PHYSGL_kept_objects = [];
    _PHYSGL_clear_skip = 0;
    _PHYSGL_rotate = {origin: [0,0,0], axis: new THREE.Vector3(), angle: 0};
	console.log(escape(code));
	
	var filename = encodeURI($('#filename').val());
	var narrative = encodeURI($('#edit_narrative').val());
	
	save_code();
	run_count++;

	clear_graph();
	clear_console();
	clear_sliders();
	programming_buttons('running');
	//console.error = function(msg) { $('#error_message').html(msg);};
	
	$('#console').css('border','0px');
	$('#save_update').html('Saved.');
	
	$('#error_message').html('');
			
	tcode = code;
	for(i=0;i<tcode.length;i++)
		{
			if (tcode.charCodeAt(i) > 128)
				tcode = tcode.substr(0,i) + '$' + tcode.substr(i+1);
		}
	if (tcode != code)
		{
			myCodeMirror.setValue(tcode);
			$('#error_message').html('You have unicode character(s) in your code. They have been replaced by $-signs. Please fix them');
			return;
		}
	
	dest = '#pmode_small';
	width = 500;
	height = 400;
	ret = _PHYSGL_error_check(code);
	if (ret == 'none')
		{
			if (where == 'small')
				_PHYSGL_interact = '#interact_small';
			else _PHYSGL_interact = '#interact_large';
			
			code = js_preprocess(code);
			console.log(code);
			//return;
			
			//$('#code_out').val(code);
 			code = '<script>' + 'try { ' + code + '} catch(err) { _PHYSGL_runtime_error(err); }'+'</sc' +'ript>';			
 			$('#dump').html(code);
			
			if (where == 'large')
				{
					dest = '#pmode_large';
					$('#tabs-min').tabs({selected: 'graphics'});
					width = 800;
					height = 600;
				}
				
			renderer3d.render( scene3d, camera3d );
		}
	else $('#error_message').html(ret);
	
	$(dest).mousedown(orbit_init);
	$(dest).mouseup(function() { _PHYSGL_orbit.down = false;});
	$(dest).mousemove(orbit);
	$(document).bind('keyup keydown', function(e){_PHYSGL_orbit.control_key = e.shiftKey; _PHYSGL_orbit.reset_y = true;} );
	$(dest).mousemove(function (e) { _PHYSGL_mouse.x=-(width/2-(e.pageX-$(this).offset().left)); _PHYSGL_mouse.y=(height/2-(e.pageY-$(this).offset().top));});
	
	_PHYSGL_orbit.r = Math.sqrt(camera3d.position.x*camera3d.position.x + camera3d.position.y*camera3d.position.y + camera3d.position.z*camera3d.position.z);
}

function save_layout(share_hash)
{
	var code_left, code_top, code_width, code_height;
	var graphics_left, graphics_top, graphics_width, graphics_height;
	var console_left, console_top, console_width, console_height;
	var xy_left, xy_top, xy_width, xy_height;
	var button_left, button_top;
	
	code_left = $('#code_dialog').offset().left;
	code_top = $('#code_dialog').offset().top;
	code_width = $('#code_dialog').width();
	code_height = $('#code_dialog').height();
	
	graphics_left = $('#graphics_dialog').offset().left;
	graphics_top = $('#graphics_dialog').offset().top;
	graphics_width = $('#graphics_dialog').width();
	graphics_height = $('#graphics_dialog').height();
	
	console_left = $('#console_dialog').offset().left;
	console_top = $('#console_dialog').offset().top;
	console_width = $('#console_dialog').width();
	console_height = $('#console_dialog').height();
	
	xy_left = $('#xy_dialog').offset().left;
	xy_top = $('#xy_dialog').offset().top;
	xy_width = $('#xy_dialog').width();
	xy_height = $('#xy_dialog').height();
	
	button_left = $('#run_button').offset().left;
	button_top = $('#run_button').offset().top;
	
	$.post('<?php echo base_url(); ?>index.php/welcome/save_layout',
					{
						code_hash: '<?php echo $code_hash;?>',
						code_left: code_left, code_top: code_top, code_width: code_width,code_height: code_height,
						graphics_left: graphics_left, graphics_top: graphics_top, graphics_width: graphics_width,graphics_height: graphics_height,
						console_left: console_left, console_top: console_top, console_width: console_width,console_height: console_height,
						xy_left: xy_left, xy_top: xy_top, xy_width: xy_width,xy_height: xy_height,
						button_left: button_left, button_top: button_top 
					}
			);	
}

function orbit_init(e)
{
	_PHYSGL_orbit.x = e.pageX;
	_PHYSGL_orbit.y = e.pageY;
	_PHYSGL_orbit.down = true;
	
	if (_PHYSGL_orbit.down_count == 0)
		{
			_PHYSGL_orbit.theta = 0.0;
			_PHYSGL_orbit.reset_y = true;
			_PHYSGL_orbit.theta_offset = 0;
			_PHYSGL_orbit.phi_offset = 0;
			_PHYSGL_orbit.lx = 0;
			_PHYSGL_orbit.ly = 0;
			_PHYSGL_orbit.lz = 0;
		}
		
	if (_PHYSGL_orbit.down_count)
		{
			_PHYSGL_orbit.theta_offset = _PHYSGL_orbit.theta;
			_PHYSGL_orbit.phi_offset = _PHYSGL_orbit.phi;
		}
	_PHYSGL_orbit.down_count++;
}

function orbit(e)
{
	var dx,dy;
	
	if (_PHYSGL_orbit.down == false)
		return;
	
	dx = e.pageX - _PHYSGL_orbit.x;
	dy = e.pageY - _PHYSGL_orbit.y;
	
	if (_PHYSGL_orbit.reset_y)
		{
			_PHYSGL_orbit.cur_z = camera3d.position.z;
			_PHYSGL_orbit.reset_y = false;
			_PHYSGL_orbit.r = Math.sqrt(camera3d.position.x*camera3d.position.x + camera3d.position.y*camera3d.position.y + camera3d.position.z*camera3d.position.z);
		}

	if (_PHYSGL_orbit.control_key)
		{
			var normal = new THREE.Vector3(camera3d.position.x,camera3d.position.y,camera3d.position.z);
			normal.normalize();
			var movex = dy*normal.dot(new THREE.Vector3(1,0,0));
			var movey = dy*normal.dot(new THREE.Vector3(0,1,0));
			var movez = dy*normal.dot(new THREE.Vector3(0,0,1));
			
			camera3d.position.x += movex;
			camera3d.position.y += movey; 
			camera3d.position.z += movez;
			
			_PHYSGL_orbit.lx += movex;
			_PHYSGL_orbit.ly += movey;
			_PHYSGL_orbit.lz += movez;
	
			
			_PHYSGL_orbit.x = e.pageX;
			_PHYSGL_orbit.y = e.pageY;
			
			renderer3d.render( scene3d, camera3d );
			return;
		}
			
	_PHYSGL_orbit.theta = -dx/200.0 + _PHYSGL_orbit.theta_offset;
	_PHYSGL_orbit.phi = dy/200.0 + _PHYSGL_orbit.phi_offset;
	camera3d.position.x = _PHYSGL_orbit.r * Math.sin(_PHYSGL_orbit.theta);
	camera3d.position.y = _PHYSGL_orbit.r * Math.sin(_PHYSGL_orbit.phi);
	camera3d.position.z = _PHYSGL_orbit.r * Math.cos(_PHYSGL_orbit.theta) * Math.cos(_PHYSGL_orbit.phi);
	/*
	light3d.position.x = 100 * camera3d.position.x;
	light3d.position.y = 100 * camera3d.position.y;
	light3d.position.z = 100 * camera3d.position.z;
	*/
	camera3d.lookAt( new THREE.Vector3(0,0,0) );
	
	renderer3d.render( scene3d, camera3d );


}
	

function init_webgl(where)
{
	renderer3d = new THREE.WebGLRenderer();
	scene3d = new THREE.Scene();
	var width = $('#pmode_small').width();
	var height= $('#pmode_small').height();
	
	if (where == 'small')
		{
			var container = $('#pmode_small');
			renderer3d.setSize( width, height );
		}
	else
		{
			var container = $('#pmode_large');
			renderer3d.setSize( 800, 600 );
		}
	container.empty();
	container.append( renderer3d.domElement );
	
	
	camera3d = new THREE.PerspectiveCamera(45,width/height,0.1,1000);
	camera3d.position.set( 0, 50, 150);
	camera3d.lookAt( new THREE.Vector3(0,0,0) );
	scene3d.add( camera3d );
	_PHYSGL_orbit.r = 500;
	
	light3d = new THREE.HemisphereLight(0xffffff,0x000000,1);
	scene3d.add(light3d);
	
	//light3d = new THREE.DirectionalLight( 0xFFFFFF,1.0);
	//light3d.position.set( 0, 1000, 1000 )
	//scene3d.add( light3d );

	
	renderer3d.setClearColor( 0x000000, 1 );
	
	_PHYSGL_textures = {brick: THREE.ImageUtils.loadTexture("<?php echo base_url(); ?>textures/bricks.jpg"),
						metal: THREE.ImageUtils.loadTexture("<?php echo base_url(); ?>textures/metal.jpg"),
						rope: THREE.ImageUtils.loadTexture("<?php echo base_url(); ?>textures/rope.jpg"),
						crate: THREE.ImageUtils.loadTexture("<?php echo base_url(); ?>textures/crate.jpg"),
						water: THREE.ImageUtils.loadTexture("<?php echo base_url(); ?>textures/water.jpg"),
						grass: THREE.ImageUtils.loadTexture("<?php echo base_url(); ?>textures/grass.jpg"),
						checker01: THREE.ImageUtils.loadTexture("<?php echo base_url(); ?>textures/checker01.jpg")
						};
	
	
}


function _PHYSGL_stop_run()
{
	_PHYSGL_stop_request = true;
	programming_buttons('stopped');
	_PHYSGL_loop = false;
}

function _PHYSGL_pause_run()
{
	if (_PHYSGL_pause)
		clear();
	_PHYSGL_pause = !_PHYSGL_pause;
	_PHYSGL_single_step = false;
	pause_button_toggle();
}

function _PHYSGL_loop_run()
{
	_PHYSGL_loop = true;
	run('small',false);
}


function wait_for_fps()
{
	var now = new Date().getTime();

	if  (_PHYSGL_stop_request == true)
		{
			_PHYSGL_stop_request == false;
			_PHYSGL_in_animation_loop = false;
			programming_buttons('stopped');
			return;
		}
		
	if (now-start_time < fps_rate)
		requestAnimationFrame(wait_for_fps);
	else __animate_while();
	
}


function clear()
{
	renderer3d.render( scene3d, camera3d );
	if (_PHYSGL_clear_skip && _PHYSGL_frame_count % _PHYSGL_clear_skip == 0)
		{
			for(i=0;i<o3d.length;i++)
				_PHYSGL_kept_objects.push(o3d[i]);
		}
	for(i=0;i<o3d.length;i++)
		scene3d.remove(o3d[i]);
	o3d = [];
	
	for(i=0;i<_PHYSGL_kept_objects.length;i++)
		scene3d.add(_PHYSGL_kept_objects[i]);
	
	start_time = new Date().getTime();
	wait_for_fps();
}

	
function _PHYSGL_runtime_error(err)
{
	var line = err.line;
	var msg = err.message;
	
	msg = msg.replace('variable','variable or function');
	$('#error_message').html(msg);
	console.error(msg);
}

function _PHYSGL_go_to_url(url)
{
	window.location=url;
}

function _PHYSGL_share_link()
{
	$('#share_modal').modal('show');	
	var code= myCodeMirror.getValue();
	var narrative = $('#narrative_text').val();
	var layout = get_layout_array();

	$.ajax({
			type: "POST",
			url: '<?php echo base_url(); ?>index.php/welcome/generate_share_link/<?php echo $user_hash; ?>',
			data: { 
						narrative: narrative,
						layout: JSON.stringify(layout), 
						code: encodeURI(code),
					},
			success: function (msg) { $('#share_link').val(msg).select(); }
			});
}

function new_graph()
{
	var i;
	var width, height;
	
	$('#xy_dialog').html("<div id=\"graph\"></div>");
	width = $('#xy_dialog').width();
	height = $('#xy_dialog').height();
	$('#graph').css('width',width+'px');
	$('#graph').css('height',height+'px');
	

	_PHYSGL_graph_data = new google.visualization.DataTable();
	for(i=0;i<arguments.length;i++)
		_PHYSGL_graph_data.addColumn('number',arguments[i]);
	_PHYSGL_chart = new google.visualization.ScatterChart(document.getElementById('graph'));
	_PHYSGL_axes_labels = { horiz: arguments[0], vert: arguments[1]};
	
	show_graph();
}

function go_graph()
{
	var i;
	var xy=[];
	for(i=0;i<arguments.length;i++)
		xy[i] = arguments[i];
	_PHYSGL_graph_data.addRows([xy]);
	_PHYSGL_chart.draw(_PHYSGL_graph_data,{pointSize: 2,
											chartArea: {width: '80%', height: '80%'},
											legend: {position: 'in'},
											fontSize: 12,
											hAxis: {title: _PHYSGL_axes_labels.horiz,viewWindow: {max: _PHYSGL_axes_range.xmax,min: _PHYSGL_axes_range.xmin} },
											vAxis: {title: _PHYSGL_axes_labels.vert, viewWindow: {max: _PHYSGL_axes_range.ymax,min: _PHYSGL_axes_range.ymin}} });
}

function new_multi_graph()
{
	var i;
	var width, height, h;
	var d;
	
	d = "";
	for(i=0;i<arguments.length-1;i++)
		d = d + "<div id=\"graph"+i+"\"></div>";
	
	$('#xy_dialog').html(d);
	width = $('#xy_dialog').width();
	height = $('#xy_dialog').height();
	
	_PHYSGL_graph_data = [];
	_PHYSGL_chart = [];
	_PHYSGL_axes_labels = [];
	for(i=0;i<arguments.length-1;i++)
		{
			_PHYSGL_graph_data[i] = new google.visualization.DataTable();
			_PHYSGL_graph_data[i].addColumn('number',arguments[0]);
			_PHYSGL_graph_data[i].addColumn('number',arguments[i+1]);
			_PHYSGL_chart[i] = new google.visualization.ScatterChart(document.getElementById('graph'+i));
			_PHYSGL_axes_labels[i] = { horiz: arguments[0], vert: arguments[i+1]};
		}
	
	show_graph();
}

function go_multi_graph()
{
	var i;
	var xy=[];

	for(i=0;i<arguments.length-1;i++)
		{
			xy[0] = arguments[0]
			xy[1] = arguments[i+1]
			_PHYSGL_graph_data[i].addRows([xy]);
			
			_PHYSGL_chart[i].draw(_PHYSGL_graph_data[i],{pointSize: 2,
											chartArea: {width: '80%', height: '65%'},
											legend: {position: 'none'},
											fontSize: 14,
											hAxis: {title: _PHYSGL_axes_labels[i].horiz,viewWindow: {max: _PHYSGL_axes_range.xmax,min: _PHYSGL_axes_range.xmin} },
											vAxis: {title: _PHYSGL_axes_labels[i].vert, viewWindow: {max: _PHYSGL_axes_range.ymax,min: _PHYSGL_axes_range.ymin}} });
			
		}
	show_graph();
}


function bar_graph()
{
	var i;
	var width, height;
	var arr = [];
	
	width = $('#xy_dialog').width();
	height = $('#xy_dialog').height();
	$('#graph').css('width',width+'px');
	$('#graph').css('height',height+'px');
	
	for(i=0;i<arguments.length;i += 2)
		arr.push([arguments[i],arguments[i+1]]);
			
	_PHYSGL_graph_data = google.visualization.arrayToDataTable(arr);
	_PHYSGL_chart = new google.visualization.ColumnChart(document.getElementById('graph'));
	_PHYSGL_chart.draw(_PHYSGL_graph_data,{
					pointSize: 2,
					legend: {position: 'none'},
					hAxis: {title: arguments[0],viewWindow: {max: _PHYSGL_axes_range.xmax,min: _PHYSGL_axes_range.xmin} },
					vAxis: {title: arguments[1],viewWindow: {max: _PHYSGL_axes_range.ymax,min: _PHYSGL_axes_range.ymin}}
					
					
					});
		
    show_graph();
}




function go_graph_array(x_axis,y_axis)
{
	var i;
	var xy=[];
	for(i=0;i<x_axis.length;i++)
		{
			xy[i] = [x_axis[i],y_axis[i]];
		}
	_PHYSGL_graph_data.addRows(xy);
	_PHYSGL_chart.draw(_PHYSGL_graph_data,{pointSize: 2,
											chartArea: {width: '80%', height: '80%'},
											legend: {position: 'in'},
											hAxis: {title: _PHYSGL_axes_labels.horiz,viewWindow: {max: _PHYSGL_axes_range.xmax,min: _PHYSGL_axes_range.xmin} },
											vAxis: {title: _PHYSGL_axes_labels.vert, viewWindow: {max: _PHYSGL_axes_range.ymax,min: _PHYSGL_axes_range.ymin}} });
}

function set_xrange(xmin,xmax)
{
	_PHYSGL_axes_range.xmin = xmin;
	_PHYSGL_axes_range.xmax = xmax;
}

function set_yrange(ymin,ymax)
{
	_PHYSGL_axes_range.ymin = ymin;
	_PHYSGL_axes_range.ymax = ymax;
}



//state is running or stopped
function programming_buttons(state)
{
	var b;
	
	if (state == 'stopped')
		{
			b = '<button class="btn btn-success btn-sm mr-1" id="button_style" onclick="run(\'small\',false);">Run</button>' +
				'<button class="btn btn-info btn-sm mr-1" id="button_style" onclick="run(\'small\',true);">Step</button>' +
				'<button class="btn btn-warning btn-sm" id="button_style" onclick="_PHYSGL_loop_run();">Loop</button>';
			
		}
	else
		{
			b = '<button class="btn btn-danger btn-sm mr-1" id="button_style" onclick="_PHYSGL_stop_run();">Stop</button>';
			b = b + '<button class="btn btn-info btn-sm mr-1" id="button_style" onclick="_PHYSGL_pause_run();">Pause</button>';
			b = b + '<button class="btn btn-warning btn-sm mr-1" id="button_style" onclick="take_step();">Step</button>';
		}		
	$('#xrun_button').html(b);
}

function take_step()
{
	_PHYSGL_single_step = true;
	_PHYSGL_pause = false;
	clear();
}

function pause_button_toggle()
{
	var b;
	if (_PHYSGL_pause)
		{
			b = '<button id="button_style" onclick="_PHYSGL_stop_run();">Stop</button>';
			b = b + '<button id="button_style" onclick="_PHYSGL_pause_run();">Resume</button>';
			b = b + '<button id="button_style" onclick="take_step();">Step</button>';
		}
	else
		{
			b = '<button id="button_style" onclick="_PHYSGL_stop_run();">Stop</button>';
			b = b + '<button id="button_style" onclick="_PHYSGL_pause_run();">Pause</button>';
			b = b + '<button id="button_style" onclick="take_step();">Step</button>';
		}
	$('#xrun_button').html(b);
}


function _PHYSGL_loop_embed()
{
	console.log($('body').html());
}



</script>
