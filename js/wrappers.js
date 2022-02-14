function _PHYSGL_add_object(obj,persist)
{
	if (persist == undefined || persist == false)
		o3d.push(obj);
	scene3d.add(obj);
}

function draw_rocket()
{
	var pos = arguments[0], scale = arguments[1], theta = arguments[2], persist = arguments[3];
	
	 var ret = _PHYSGL_draw_rocket(pos[0],pos[1],pos[2],scale,theta)
	 _PHYSGL_add_object(ret,persist);
}

function draw_car()
{
	var pos = arguments[0], scale = arguments[1], theta = arguments[2], persist = arguments[3];
	
	 var ret = _PHYSGL_draw_car(pos[0],pos[1],pos[2],scale,theta)
	 _PHYSGL_add_object(ret,persist);
}
	
function draw_sphere()//(pos,radius,color)
{
	var pos = arguments[0], radius = arguments[1], color = arguments[2], persist = arguments[3];
	
	var ret = _PHYSGL_draw_sphere(pos[0],pos[1],pos[2],radius,_PHYSGL_hcolor(color));
	_PHYSGL_add_object(ret,persist);
}


function draw_ellipsoid()//(pos,radius,color)
{
	var pos = arguments[0], radius = arguments[1], color = arguments[2], scale = arguments[3], persist = arguments[4];
	
	var ret = _PHYSGL_draw_ellipsoid(pos[0],pos[1],pos[2],scale[0],scale[1],scale[2],radius,_PHYSGL_hcolor(color));
	_PHYSGL_add_object(ret,persist);
}

function draw_box()
{
	var corner1 = arguments[0], corner2 = arguments[1], color = arguments[2], persist = arguments[3];
	var ret = _PHYSGL_draw_box(corner1[0],corner1[1],corner1[2],corner2[0],corner2[1],corner2[2],_PHYSGL_hcolor(color));
	_PHYSGL_add_object(ret,persist);
}

function draw_cube()
{
	var center = arguments[0], size = arguments[1], color = arguments[2], persist = arguments[3];
	var ret = _PHYSGL_draw_box(center[0] - size/2,center[1] - size/2,center[2] - size/2,center[0] + size/2,center[1] + size/2,center[2] + size/2,_PHYSGL_hcolor(color));
	_PHYSGL_add_object(ret,persist);
}

function draw_label_vector()
{
	var tail = arguments[0], vec = arguments[1], color = arguments[2], label = arguments[3], persist = arguments[4];
	var ret = _PHYSGL_draw_vector(tail[0],tail[1],tail[2],vec[0],vec[1],vec[2],_PHYSGL_hcolor(color),label)
	_PHYSGL_add_object(ret,persist);
}

function draw_vector()
{
	var tail = arguments[0], vec = arguments[1], color = arguments[2], persist = arguments[3];
	var ret = _PHYSGL_draw_plain_vector(tail[0],tail[1],tail[2],vec[0],vec[1],vec[2],_PHYSGL_hcolor(color))
	_PHYSGL_add_object(ret,persist);
}

function draw_arrow()
{
	var tail = arguments[0], vec = arguments[1], color = arguments[2], persist = arguments[3];
	var ret = _PHYSGL_draw_arrow(tail[0],tail[1],tail[2],vec[0],vec[1],vec[2],_PHYSGL_hcolor(color))
	_PHYSGL_add_object(ret,persist);
}


function draw_line()
{
	var tail = arguments[0];
	var head = arguments[1];
	var color = arguments[2];
	var thickness = arguments[3];
	var persist = arguments[4];
	
	var ret = _PHYSGL_draw_line(tail[0],tail[1],tail[2],head[0],head[1],head[2],_PHYSGL_hcolor(color),thickness);
	_PHYSGL_add_object(ret,persist);
}

function draw_cylinder()
{
	var tail = arguments[0];
	var head = arguments[1];
	var radius = arguments[2];
	var color = arguments[3];
	var open = arguments[4];
	var persist = arguments[5];
	var ret = _PHYSGL_draw_cylinder(tail[0],tail[1],tail[2],head[0],head[1],head[2],radius,_PHYSGL_hcolor(color),open);
	_PHYSGL_add_object(ret,persist);
}

function draw_cone()
{
	var tail = arguments[0];
	var head = arguments[1];
	var radius = arguments[2];
	var color = arguments[3];
	var open = arguments[4];
	var persist = arguments[5];
	var ret = _PHYSGL_draw_cone(tail[0],tail[1],tail[2],head[0],head[1],head[2],radius,_PHYSGL_hcolor(color),open);
	_PHYSGL_add_object(ret,persist);
}

function draw_torus()
{
	var position = arguments[0];
	var normal= arguments[1];
	var Rtorus = arguments[2];
	var Rtube = arguments[3];
	var color = arguments[4];
	var persist = arguments[5];
	var ret = _PHYSGL_draw_torus(position,normal,Rtorus,Rtube,_PHYSGL_hcolor(color));
	_PHYSGL_add_object(ret,persist);
}

function draw_spring()
{
	var p1=arguments[0];
	var p2=arguments[1];
	var R=arguments[2];
	var thick = arguments[3];
	var color=arguments[4];
	var persist=arguments[5];
	
	var ret = _PHYSGL_draw_spring(p1[0],p1[1],p1[2],p2[0],p2[1],p2[2],R,thick,_PHYSGL_hcolor(color),persist);
	_PHYSGL_add_object(ret,persist);
}


function draw_plane()
{
	var normal= arguments[0];
	var z = arguments[1];
	var color = arguments[2];
	var size = arguments[3];
	var persist = arguments[4];
	
	var ret = _PHYSGL_draw_plane(normal,z,_PHYSGL_hcolor(color),size);
	_PHYSGL_add_object(ret,persist);
}

function printxy()
{
	var pos = arguments[0], str = arguments[1], size = arguments[2], color = arguments[3], persist = arguments[4];
	var ret = _PHYSGL_dtext(pos[0],pos[1],pos[2],str,size,_PHYSGL_hcolor(color));
	_PHYSGL_add_object(ret,persist);
}

function printxyz()
{
	var pos = arguments[0], str = arguments[1], size = arguments[2], color = arguments[3], persist = arguments[4];
	var ret = _PHYSGL_dtext(pos[0],pos[1],pos[2],str,size,_PHYSGL_hcolor(color));
	_PHYSGL_add_object(ret,persist);
}

function set_vector_scale(n)
{
	_PHYSGL_vector_scale = 3*n;
}


function set_vector_thickness(n)
{
	_PHYSGL_arrow_thickness = n;
}

function set_vector_label_scale(n)
{
	_PHYSGL_vector_label_scale = n;
}

function rotate()
{
	var angle = arguments[0];
	var axis = arguments[1];
	var origin = arguments[2];
	
	
	switch(arguments.length)
		{
			case 1:	
					_PHYSGL_rotate.angle = angle;
					_PHYSGL_rotate.axis = new THREE.Vector3(0,0,1).normalize();
					_PHYSGL_rotate.origin = [0,0,0];
					break;
			case 2:
					_PHYSGL_rotate.angle = angle;
					_PHYSGL_rotate.axis = new THREE.Vector3(axis[0],axis[1],axis[2]).normalize();
					_PHYSGL_rotate.origin = [0,0,0];
					break;
			default:
					_PHYSGL_rotate.angle = angle;
					_PHYSGL_rotate.axis = new THREE.Vector3(axis[0],axis[1],axis[2]).normalize();
					_PHYSGL_rotate.origin = origin;
					break;
		}
}
					
						
	
	
	

function plotxy()
{
	var f = arguments[0];
	var xmin = arguments[1], xmax=arguments[2];
	var dx = arguments[3];
	var z = arguments[4];
	var color = arguments[5];
	var thickness = arguments[6];
	var persist = arguments[7];
	
	if (xmin > xmax)
		{
			var t = xmin;
			xmin = xmax;
			xmax = t;
		}
	dx = Math.abs(dx);
	
	var x = xmin;
	var oldx, oldy;
	
	oldx = x;
	oldy = f(x);
	
	x += dx;
	
	while(x <= xmax)
	{
		y = f(x);
		draw_line([oldx,oldy,z],[x,y,z],color,thickness,persist);
		oldx = x;
		oldy = y;
		x += dx;
	}
}


function rnd()
{
        if (arguments.length == 0)
                return(Math.random());
        var low = arguments[0];
        var high = arguments[1];
        var n = low + (high-low)*Math.random();
        if (arguments.length == 2)
                return(n);
        var makeint = arguments[2];
        if (makeint == true)
                return(Math.floor(n));
        return(n);
}


function draw_axes()
{
	var scale=arguments[0], thickness = arguments[1], persist = arguments[2];
	var a = _PHYSGL_vector_label_scale;
	var b = _PHYSGL_arrow_thickness;
	set_vector_label_scale(scale);
	set_vector_thickness(thickness);
	draw_label_vector([0,0,0],[scale*100,0,0],"red","x",persist);
	draw_label_vector([0,0,0],[0,scale*100,0],"green","y",persist);
	draw_label_vector([0,0,0],[0,0,scale*100],"blue","z",persist);
	_PHYSGL_vector_label_scale = a;
	_PHYSGL_arrow_thickness = b;
}

function draw_axes_full()
{
	var scale=arguments[0], thickness = arguments[1], persist = arguments[2];
	var a = _PHYSGL_vector_label_scale;
	var b = _PHYSGL_arrow_thickness;
	set_vector_label_scale(5*scale);
	set_vector_thickness(thickness);
	draw_label_vector([-scale*100,0,0],[2*scale*100,0,0],"red","x",persist);
	draw_label_vector([0,-scale*100,0],[0,2*scale*100,0],"green","y",persist);
	draw_label_vector([0,0,-scale*100],[0,0,2*scale*100],"blue","z",persist);
	_PHYSGL_vector_label_scale = a;
	_PHYSGL_arrow_thickness = b;
}

function frame_delta(n)
{
	return(_PHYSGL_frame_count % n == 0);
}

function new_spline()
{
	_PHYSGL_spline_data = [];
}

function add_spline(vec)
{
	_PHYSGL_spline_data.push(new THREE.Vector3(vec[0],vec[1],vec[02]));
}

function draw_spline()
{
	var color=_PHYSGL_hcolor(arguments[0]);
	var thickness=arguments[1];
	var num_points=arguments[2];
	var persist=arguments[3];
	
	spline = new THREE.SplineCurve3(_PHYSGL_spline_data);
	var result;
	
	//var material = new THREE.LineBasicMaterial({color: color.color,map: color.map,rlinewidth: thickness, linejoin: 'round'});
	var geometry = new THREE.Geometry();
	var splinePoints = spline.getPoints(num_points);
	
	for(var i = 0; i < splinePoints.length; i++)
	{
		geometry.vertices.push(splinePoints[i]);  
	}
	
	var tube = new THREE.TubeGeometry(spline, 100, thickness, 10, false);

	tube.dynamic = true;
	var material = new THREE.MeshLambertMaterial({color: color.color, map: color.map});
	_PHYSGL_add_object(new THREE.Mesh(tube,material),persist);
}

function mousex()
{
	return(_PHYSGL_mouse.x);
}

function mousey()
{
	return(_PHYSGL_mouse.y);
}

function mean(list)
{
	var sum=0,i;
	if (list.length == 0)
		return(false);
	for(i=0;i<list.length;i++)
		sum += list[i];
	return(sum/list.length);
}


function std(list)
{
	var themean; 
	var sum = 0;
	var i;
	if (list.length < 2)
		return(false);
	themean = mean(list);
	for(i=0;i<list.length;i++)
		sum +=(list[i]-themean)*(list[i]-themean);
	return Math.sqrt(sum/(list.length-1));
}

function magnitude(a)
{
	return(Math.sqrt(a[0]*a[0]+a[1]*a[1]+a[2]*a[2]));
}

function distance(a,b)
{
	return(Math.sqrt(Math.pow(a[0]-b[0],2)+Math.pow(a[1]-b[1],2)+Math.pow(a[2]-b[2],2)));
}
	
	
		
	
	
