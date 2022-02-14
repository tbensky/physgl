function writeln()
{
	var current;
	var i;
	current = $('#console_dialog').html();
	
	if (_PHYSGL_console_border == false)
		$('#console_dialog').css('border','1px solid #aaaaaa');
	
	for(i=0;i<arguments.length;i++)
		current = current + arguments[i];
	current = current + '<br/>';
	$('#console_dialog').html(current);
	$('#console_dialog').scrollTop($('#console_dialog')[0].scrollHeight);
}

function write()
{
	var current;
	var i;
	
	current = $('#console_dialog').html();
	
	/*
	if (_PHYSGL_console_border == false)
		$('#console_dialog').css('border','1px solid #aaaaaa');
	*/
	
	for(i=0;i<arguments.length;i++)
		current = current + arguments[i];
	$('#console_dialog').html(current.replace(' ','&nbsp;'));
	$('#console_dialog').scrollTop($('#console_dialog')[0].scrollHeight);
}

function clear_console()
{
	$('#console_dialog').html('');
}

function clear_graph()
{
	$('#graph').css('visibility','hidden');
	//$('#graph').css('display','none');
}

function show_graph()
{
	$('#graph').css('visibility','visible');
	//$('#graph').css('display','inline');
	//$('#graph').css('width','500px');
	//$('#graph').css('height','500px');
	$('#graph').css('margin','0px');
}

function frames_per_second(n)
{
	fps_rate = 1000/n;
}

function fps(n)
{
	fps_rate = 1000/n;
}

function camera(pos,look_at)
{
	camera3d.position.set( pos[0], pos[1], pos[2]);
	camera3d.lookAt( new THREE.Vector3(look_at[0],look_at[1],look_at[2]) );
	//renderer3d.render( scene3d, camera3d );
}

function light(pos)
{
	light3d.position.set( pos[0], pos[1], pos[2] );
}

function new_slider(name,min,max,step,value)
{
	var cur;
	
	cur = $(_PHYSGL_interact).html();
	cur = cur + '<span id="slider_text_label">'+name + ': </span><span id="slider_labels">'+min+'</span> <input type=\"range\" id=\"slider_'+name+'\" min='+min+' max='+max+' step='+step+' value='+value+' onchange=\'$(\"#sliderval_'+name+'\").html("("+this.value+")")\'> <span id="slider_labels">'+max+'</span> <span id="slider_labels"><span id=\"sliderval_'+name+'\">'+'('+value+')'+'</span></span><br/>';
	$(_PHYSGL_interact).html(cur);
}

function get_slider(name)
{
	return(parseFloat($('#slider_'+name).val()));
}

function clear_sliders()
{
	$('#interact_small').html('');
	$('#interact_large').html('');
}

function new_button(name,func)
{
	var cur;
	
	cur = $(_PHYSGL_interact).html();
	cur = cur + '<button onclick=\"'+func+'();\">'+name+'</button>';
	$(_PHYSGL_interact).html(cur);
}

function snapshot(n)
{
	_PHYSGL_clear_skip = n;
}


	