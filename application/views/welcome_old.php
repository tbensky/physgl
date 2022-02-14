<?php
include('config_paths.php');
?>

<div id="tabs-min">
<ul>
    <li><a href="#code">Work</a></li>
    <li><a href="#graphics">600p</a></li>
</ul>


<div id="code">

<div class="work_space">
    <div class="col1">
    <textarea id="code_editor">
theta=math.pi/2.5
v0=30
pos=<-100,0,0>
vel=<v0*math.cos(theta),v0*math.sin(theta),0>
a=<0,-9.8,0>
t=0
dt=0.25
frames_per_second(20)
while t<20 do
 draw_text(<0,0,0>,"hi",20,"white")
 plane(0,"red")
 sphere(pos,5,"orange")
 draw_vector(pos,vel,"green","v")
 draw_vector(pos,<vel.x,0,0>,"blue","vx")
 draw_vector(pos,<0,vel.y,0>,"white","vy")
 pos=pos+vel*dt+0.5*a*dt*dt
 vel=vel+a*dt
 if (pos.y < 0 and vel.y < 0) then
   vel=<vel.x,-vel.y,0>
 end
 t=t+dt
 clear()
end
	
	</textarea>
<br/>
<div id="run_button">
<button onclick="run('<?php echo $lua_url;?>','small');">Run</button>
<button onclick="run('<?php echo $lua_url;?>','large');">Run 600p</button>
</div>

</div>

    <div class="col2">
    <div id="pmode_small"></div>
    </div>

</div>
</div>
	
<div id="graphics">
	<div id="pmode_large"></div>
</div>

</div>

<div id="status"></div>
<div id="dump"></div>
<div id="text"></div>

<script>

var renderer3d, scene3d, camera3d;
var o3d = [];
var ri;
var lf = [];
var width=800, height=600;
var fps_rate = 25;
var current_part, max_part;
var myCodeMirror = CodeMirror.fromTextArea(code_editor,{lineNumbers: true});
var start_time;

$(document).ready(function() {
    $("#tabs-min").tabs();
    $('#tabs-min').tabs({selected: 'code'});
  });
  
  
function partition(str)
{
	var MAX_LINES = 100;
	var line_count = 0;
	var js = "", html="";
	var in_script = false;
	a = str.split("\n");
	part_count = 0;
	n = 1;
	
	js = "function go(n)\n{\nswitch(n)\n{\ncase 0:\n";
	i = 0;
	while(i < a.length)
		{
			line = $.trim(a[i]);
			line_count++;
			if (line == '<script>')
				in_script = true;	
			else if (line == '</scr'+'ipt>')
					in_script = false;
			else if (in_script == true && line_count < MAX_LINES)
					js = js + line + '\n';
			else if (in_script == true && line_count >= MAX_LINES)
					{
						line_count = 0;
						js = js + 'break;\ncase '+n+':\n';
						n = n + 1;
					}	
			else if (in_script == false)
					html = html +  line + '\n';
			i=i+1;
		}
		js = js + "break;\n}\n}\n";
		return({html:html,js:js,parts: n});
}

function run(lua_url,where)
{
	$('#run_button').html('Running...');
	$('#status').html('Executing code...');
	ret = luait(lua_url);
	ret = partition(ret);
	console.log(ret.js);
	$('#dump').html('<script>' + ret.js + '</sc' +'ript>');
	$('#text').html('<pre>' + ret.html + '</pre>');
	$('#status').html('Starting animation...');
	init_webgl(where);
	if (where == 'large')
		$('#tabs-min').tabs({selected: 'graphics'});
	
	current_part = 0;
	max_part = ret.parts;
	ri = 0;
	o3d = [];
	lf = [];
	run_in_parts();
}

function luait(lua_url)
{
	//code = $('#code_editor').val();
	code = myCodeMirror.getValue();
	current = $('#pmode').text();
	var ret = null;
	$.ajax({
				type: "POST",
				url: lua_url,
				data: {code: code},
				async: false,
				success: function(data)
				{
					ret = data;	
				}
			});
	return(ret);
}

function init_webgl(where)
{


	renderer3d = new THREE.WebGLRenderer();
	scene3d = new THREE.Scene();
	
	if (where == 'small')
		{
			var container = $('#pmode_small');
			renderer3d.setSize( 500, 400 );
		}
	else
		{
			var container = $('#pmode_large');
			renderer3d.setSize( 800, 600 );
		}
		
	container.empty();
	container.append( renderer3d.domElement );
	
	camera3d = new THREE.PerspectiveCamera(50,width/height,0.1,10000);
	camera3d.position.set( 0, 50, 200);
	camera3d.lookAt( new THREE.Vector3(0,0,0) );
	scene3d.add( camera3d );
	
	var light = new THREE.DirectionalLight( 0xFFFFFF);
	light.position.set( 0, 1000, 1000 );
	scene3d.add( light );

	
	
}


function run_in_parts()
{
	if (ri < o3d.length)
		{
			//wait for this part to finish
			requestAnimationFrame(run_in_parts);
			return;
		}
	go(current_part);
	render();
	if (current_part < max_part)
		{
			current_part++;
			//start the next part
			requestAnimationFrame(run_in_parts);
		}
	else 
		{
			$('#run_button').html('<button onclick="run(\'<?php echo $lua_url;?>\',\'small\');">Run</button><button onclick="run(\'<?php echo $lua_url;?>\',\'large\');">Run 600p</button>');
			$('#status').html('');
		}
}



function render()
{		
	while(ri < o3d.length)
		{
			if (o3d[ri].toString() != 'cls')
				{
					scene3d.add(o3d[ri]);
					lf.push(o3d[ri]);
					ri++;
				}
			else
				{
					renderer3d.render( scene3d, camera3d );
					ri++;
					if (ri < o3d.length)
						{
							for(i=0;i<lf.length;i++)
								scene3d.remove(lf[i]);
							lf = [];
						}
					start_time = new Date().getTime();
					requestAnimationFrame(wait_for_fps); 
					return;
				}
		}
	if (current_part >= max_part)
		renderer3d.render( scene3d, camera3d );

}

function wait_for_fps()
{
	var now = new Date().getTime();
	if (now - start_time >= fps_rate)
		render();
	else requestAnimationFrame(wait_for_fps);
}



</script>

