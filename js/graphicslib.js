function gtest()
{
	console.log('graphicslib');
}

function get_quaternion(x1,y1,z1,x2,y2,z2)
{
	var dx = x2-x1;
	var dy = y2-y1;
	var dz = z2-z1;
	var r = Math.sqrt(dx*dx+dy*dy+dz*dz);
	
	orig = new THREE.Vector3(0,r,0);
	orig.normalize();
	align = new THREE.Vector3(dx,dy,dz);
	align.normalize();
	axis = new THREE.Vector3();
	axis.crossVectors(orig,align);
	axis.normalize();
	angle = Math.acos(align.dot(orig));

	q = new THREE.Quaternion();
	q.setFromAxisAngle(axis,angle);
	return(q);
}

function _PHYSGL_draw_box(x1,y1,z1,x2,y2,z2,color)
{
	var dx = x2-x1;
	var dy = y2-y1;
	var dz = z2-z1;
	
	var material = new THREE.MeshLambertMaterial({color: color.color, map: color.map});
	var geo = new THREE.CubeGeometry(Math.abs(dx),Math.abs(dy),Math.abs(dz),1,1,1);
	geo.applyMatrix( new THREE.Matrix4().makeTranslation( _PHYSGL_rotate.origin[0],_PHYSGL_rotate.origin[1],_PHYSGL_rotate.origin[2]));
	var box = new THREE.Mesh(geo,material);
	
	box.rotateOnAxis(_PHYSGL_rotate.axis,-_PHYSGL_rotate.angle);
	box.position.set(x1+dx/2,y1+dy/2,z1+dz/2);
	
	return(box);	

}

function _PHYSGL_draw_line(x1,y1,z1,x2,y2,z2,color,thickness)
 {
	var result = new THREE.Object3D();
	var dx = x2-x1;
	var dy = y2-y1;
	var dz = z2-z1;
	var r = Math.sqrt(dx*dx+dy*dy+dz*dz);
	
	var material = new THREE.MeshLambertMaterial({ color: color.color,map: color.map});
	var geo = new THREE.CylinderGeometry(thickness,thickness,r,10,10,false);
	var cylinder = new THREE.Mesh(geo,material);
	//geo.applyMatrix( new THREE.Matrix4().makeTranslation( _PHYSGL_rotate.origin[0],_PHYSGL_rotate.origin[1],_PHYSGL_rotate.origin[2]));
	cylinder.position.set(0,r/2,0);
	result.add(cylinder);
	
	if (dx ==0 && dz == 0 && dy < 0)
		result.rotation.z = Math.PI;
	else
		{
			orig = new THREE.Vector3(0,r,0);
			orig.normalize();
			align = new THREE.Vector3(dx,dy,dz);
			align.normalize();
			axis = new THREE.Vector3();
			axis.crossVectors(orig,align);
			axis.normalize();
			angle = Math.acos(align.dot(orig));
			q = new THREE.Quaternion();
			q.setFromAxisAngle(axis,angle);
			result.quaternion = q;
		}
	
	result.position.set(x1,y1,z1);
	return(result);
 }
 
 

 
 function _PHYSGL_draw_cylinder(x1,y1,z1,x2,y2,z2,radius,color,open)
 {
	var result = new THREE.Object3D();
	var dx = x2-x1;
	var dy = y2-y1;
	var dz = z2-z1;
	var r = Math.sqrt(dx*dx+dy*dy+dz*dz);
	
	var material = new THREE.MeshLambertMaterial({ color: color.color,map: color.map});
	var geo = new THREE.CylinderGeometry(radius,radius,r,100,50,open);
	geo.applyMatrix( new THREE.Matrix4().makeTranslation( _PHYSGL_rotate.origin[0],_PHYSGL_rotate.origin[1],_PHYSGL_rotate.origin[2]));
	var cylinder = new THREE.Mesh(geo, material);
	
	cylinder.position.set(0,r/2,0);
	result.add(cylinder);
	
	if (dx ==0 && dz == 0 && dy < 0)
		result.rotation.z = Math.PI;
	else
		{
			orig = new THREE.Vector3(0,r,0);
			orig.normalize();
			align = new THREE.Vector3(dx,dy,dz);
			align.normalize();
			axis = new THREE.Vector3();
			axis.crossVectors(orig,align);
			axis.normalize();
			angle = Math.acos(align.dot(orig));
			q = new THREE.Quaternion();
			q.setFromAxisAngle(axis,angle);
			result.quaternion = q;
		}
	
	result.rotateOnAxis(new THREE.Vector3(_PHYSGL_rotate.axis.x,_PHYSGL_rotate.axis.z,_PHYSGL_rotate.axis.y),_PHYSGL_rotate.angle);
	result.position.set(x1,y1,z1);
	
	return(result);
 }
 
 function _PHYSGL_draw_cone(x1,y1,z1,x2,y2,z2,radius,color,open)
 {
	var result = new THREE.Object3D();
	var dx = x2-x1;
	var dy = y2-y1;
	var dz = z2-z1;
	var r = Math.sqrt(dx*dx+dy*dy+dz*dz);
	
	var material = new THREE.MeshLambertMaterial({ color: color.color,map: color.map});
	var geo = new THREE.CylinderGeometry(0,radius,r,100,50,open);
	geo.applyMatrix( new THREE.Matrix4().makeTranslation( _PHYSGL_rotate.origin[0],_PHYSGL_rotate.origin[1],_PHYSGL_rotate.origin[2]));
	var cylinder = new THREE.Mesh(geo, material);
	
	cylinder.position.set(0,r/2,0);
	result.add(cylinder);
	
	if (dx ==0 && dz == 0 && dy < 0)
		result.rotation.z = Math.PI;
	else
		{
			orig = new THREE.Vector3(0,r,0);
			orig.normalize();
			align = new THREE.Vector3(dx,dy,dz);
			align.normalize();
			axis = new THREE.Vector3();
			axis.crossVectors(orig,align);
			axis.normalize();
			angle = Math.acos(align.dot(orig));
			q = new THREE.Quaternion();
			q.setFromAxisAngle(axis,angle-Math.PI);
			result.quaternion = q;
		}
	
	result.rotateOnAxis(new THREE.Vector3(_PHYSGL_rotate.axis.x,_PHYSGL_rotate.axis.z,_PHYSGL_rotate.axis.y),_PHYSGL_rotate.angle);
	result.position.set(x1,y1,z1);
	
	return(result);
 }


function _PHYSGL_draw_arrow(x1,y1,z1,x2,y2,z2,color)
 {
	var result = new THREE.Object3D();
	
	var dx = x2-x1;
	var dy = y2-y1;
	var dz = z2-z1;
	var r = Math.sqrt(dx*dx+dy*dy+dz*dz);
	
	var material = new THREE.MeshLambertMaterial({ color: color.color,map: color.map});
	var geo = new THREE.CylinderGeometry(_PHYSGL_arrow_thickness,_PHYSGL_arrow_thickness,r,10,10,false);
	geo.applyMatrix( new THREE.Matrix4().makeTranslation( _PHYSGL_rotate.origin[0],_PHYSGL_rotate.origin[1],_PHYSGL_rotate.origin[2]));
	var cylinder = new THREE.Mesh(geo,material);
	cylinder.position.set(0,r/2,0);
	result.add(cylinder);
	
	var cylinder = new THREE.Mesh(new THREE.CylinderGeometry(0,3.5*_PHYSGL_arrow_thickness,0.4*r,10,10,false),material);
	cylinder.position.set(0,0.9*r,0);
	result.add(cylinder);
	
	if (dx ==0 && dz == 0 && dy < 0)
		result.rotation.z = Math.PI;
	else
		{
			orig = new THREE.Vector3(0,r,0);
			orig.normalize();
			align = new THREE.Vector3(dx,dy,dz);
			align.normalize();
			axis = new THREE.Vector3();
			axis.crossVectors(orig,align);
			axis.normalize();
			angle = Math.acos(align.dot(orig));
			q = new THREE.Quaternion();
			q.setFromAxisAngle(axis,angle);
			//result.useQuaternion = true;
			result.quaternion = q;
		}
		
	result.rotateOnAxis(_PHYSGL_rotate.axis,_PHYSGL_rotate.angle);
	result.position.set(x1,y1,z1);
	return(result);
 }
 
 function _PHYSGL_draw_rocket(x0,y0,z0,scale,theta)
 {
	var result = new THREE.Object3D();
	var L=scale*30, R=scale*10;
	
	var dx = L*Math.cos(theta);
	var dy = L*Math.sin(theta);
	var dz = 0;
	var r = Math.sqrt(dx*dx+dy*dy+dz*dz);
	
	var material = new THREE.MeshLambertMaterial({ color: 0x0000ff});
	var geo = new THREE.CylinderGeometry(R,R,r,10,10,false);
	geo.applyMatrix( new THREE.Matrix4().makeTranslation( _PHYSGL_rotate.origin[0],_PHYSGL_rotate.origin[1],_PHYSGL_rotate.origin[2]));
	var cylinder = new THREE.Mesh(geo,material);
	cylinder.position.set(0,r/2,0);
	result.add(cylinder);
	
	var material = new THREE.MeshLambertMaterial({ color: 0xff0000});
	var cylinder = new THREE.Mesh(new THREE.CylinderGeometry(0,R,0.8*r,10,10,false),material);
	cylinder.position.set(0,1.4*r,0);
	result.add(cylinder);
	
	var material = new THREE.MeshLambertMaterial({color: 0x00ff00});
	var geo = new THREE.CubeGeometry(scale*35,scale*15,scale*1,1,1,1);
	var vane = new THREE.Mesh(geo,material);
	vane.position.set(0,7,0);
	result.add(vane);
	
	var vane = new THREE.Mesh(geo,material);
	vane.rotation.y = Math.PI/2;
	vane.position.set(0,7,0);
	result.add(vane);
	
	if (dx ==0 && dz == 0 && dy < 0)
		result.rotation.z = Math.PI;
	else
		{
			orig = new THREE.Vector3(0,r,0);
			orig.normalize();
			align = new THREE.Vector3(dx,dy,dz);
			align.normalize();
			axis = new THREE.Vector3();
			axis.crossVectors(orig,align);
			axis.normalize();
			angle = Math.acos(align.dot(orig));
			q = new THREE.Quaternion();
			q.setFromAxisAngle(axis,angle);
			result.quaternion = q;
		}
		
	result.rotateOnAxis(new THREE.Vector3(0,1,0),theta);
	result.position.set(x0,y0,z0);
	return(result);
 }
 
 function _PHYSGL_draw_car(x0,y0,z0,scale,theta)
 {
	var result = new THREE.Object3D();
	var L=scale*30, R=scale*10;
	
	var dx = L*Math.cos(theta);
	var dy = L*Math.sin(theta);
	var dz = 0;
	var r = Math.sqrt(dx*dx+dy*dy+dz*dz);
	
	
	
	var material = new THREE.MeshLambertMaterial({color: 0xffff00});
	var geo = new THREE.CubeGeometry(scale*3.5,scale*4.5,scale*4.5,1,1,1);
	var body = new THREE.Mesh(geo,material);
	body.position.set(-scale,0,0);
	result.add(body);
	
	var material = new THREE.MeshLambertMaterial({color: 0x00ff00});
	var geo = new THREE.CubeGeometry(scale*2,scale*12,scale*5,1,1,1);
	var body = new THREE.Mesh(geo,material);
	body.position.set(2/5*scale,scale,0);
	result.add(body);
	
	
	
	var material = new THREE.MeshLambertMaterial({ color: 0x000000});
	var geo = new THREE.CylinderGeometry(scale,scale,5*scale,10,10,false);
	var tire = new THREE.Mesh(geo,material);
	tire.rotation.x = Math.PI/2;
	tire.position.set(12/5*scale,5*scale,0);
	result.add(tire);
	
	var material = new THREE.MeshLambertMaterial({ color: 0x000000});
	var geo = new THREE.CylinderGeometry(scale,scale,5*scale,10,10,false);
	var tire = new THREE.Mesh(geo,material);
	tire.rotation.x = Math.PI/2;
	tire.position.set(12/5*scale,-2*scale,0);
	result.add(tire);
	
	
	if (dx ==0 && dz == 0 && dy < 0)
		result.rotation.z = Math.PI;
	else
		{
			orig = new THREE.Vector3(0,r,0);
			orig.normalize();
			align = new THREE.Vector3(dx,dy,dz);
			align.normalize();
			axis = new THREE.Vector3();
			axis.crossVectors(orig,align);
			axis.normalize();
			angle = Math.acos(align.dot(orig));
			q = new THREE.Quaternion();
			q.setFromAxisAngle(axis,angle);
			result.quaternion = q;
		}
		
	result.rotateOnAxis(new THREE.Vector3(0,1,0),theta);
	result.position.set(x0,y0,z0);
	return(result);
 }

 
 
 function _PHYSGL_draw_vector(tx,ty,tz,vx,vy,vz,color,label)
 {
	var result = new THREE.Object3D();

	result.add(_PHYSGL_draw_arrow(tx,ty,tz,tx+vx*_PHYSGL_vector_scale,ty+vy*_PHYSGL_vector_scale,tz+vz*_PHYSGL_vector_scale,color));
	result.add(_PHYSGL_dtext(tx+1.2*_PHYSGL_vector_scale*vx,ty+1.2*_PHYSGL_vector_scale*vy,tz+vz+0.2*vz,label,5*_PHYSGL_vector_label_scale,color));
	return(result);
 }
 
  function _PHYSGL_draw_plain_vector(tx,ty,tz,vx,vy,vz,color)
 {
	var result = new THREE.Object3D();

	result.add(_PHYSGL_draw_arrow(tx,ty,tz,tx+vx*_PHYSGL_vector_scale,ty+vy*_PHYSGL_vector_scale,tz+vz*_PHYSGL_vector_scale,color));
	return(result);
 }
 
	 
function _PHYSGL_draw_sphere(x,y,z,radius,color)
{
	var material = new THREE.MeshLambertMaterial({ color: color.color,map: color.map});
	geo =  new THREE.SphereGeometry(radius, 30, 20 );
	geo.applyMatrix( new THREE.Matrix4().makeTranslation( _PHYSGL_rotate.origin[0],_PHYSGL_rotate.origin[1],_PHYSGL_rotate.origin[2]));
	var sphere = new THREE.Mesh(geo, material);
	
	sphere.rotateOnAxis(_PHYSGL_rotate.axis,-_PHYSGL_rotate.angle);
	sphere.position.set(x,y,z);
	return(sphere);
}

function _PHYSGL_draw_ellipsoid(x,y,z,dx,dy,dz,radius,color)
{
	var material = new THREE.MeshLambertMaterial({ color: color.color,map: color.map});
	geo =  new THREE.SphereGeometry(radius, 30, 20 );
	geo.applyMatrix( new THREE.Matrix4().makeTranslation( _PHYSGL_rotate.origin[0],_PHYSGL_rotate.origin[1],_PHYSGL_rotate.origin[2]));
	geo.applyMatrix( new THREE.Matrix4().makeScale( dx, dy, dz) );
	var sphere = new THREE.Mesh(geo, material);
	
	sphere.rotateOnAxis(_PHYSGL_rotate.axis,-_PHYSGL_rotate.angle);
	sphere.position.set(x,y,z);
	return(sphere);
}

function dc(x,y,z,L,color)
{
	var material = new THREE.MeshLambertMaterial({ color: color.color,map: color.map});
	var geo = new THREE.CubeGeometry( L, L, L );
	geo.applyMatrix( new THREE.Matrix4().makeTranslation( _PHYSGL_rotate.origin[0],_PHYSGL_rotate.origin[1],_PHYSGL_rotate.origin[2]));
	var cube = new THREE.Mesh( geo, material);
	cube.rotateOnAxis(_PHYSGL_rotate.axis,_PHYSGL_rotate.angle);
	cube.position.set(x,y,z);
	return(cube);
}

function _PHYSGL_draw_torus(position,normal,Rtorus,Rtube,color)
{
	var material = new THREE.MeshLambertMaterial({ color: color.color,map: color.map});
	var geo = new THREE.TorusGeometry(Rtorus,Rtube,50,50);
	geo.applyMatrix( new THREE.Matrix4().makeTranslation( _PHYSGL_rotate.origin[0],_PHYSGL_rotate.origin[1],_PHYSGL_rotate.origin[2]));
	var torus = new THREE.Mesh(geo, material);
	var orig, align, axis, angle, q;
	
	orig = new THREE.Vector3(0,0,1);
	align = new THREE.Vector3(normal[0],normal[1],normal[2]);
	align.normalize();
	axis = new THREE.Vector3();
	axis.crossVectors(orig,align);
	axis.normalize();
	angle = Math.acos(align.dot(orig));
	q = new THREE.Quaternion();
	q.setFromAxisAngle(axis,angle);
	torus.quaternion = q;
	torus.rotateOnAxis(new THREE.Vector3(_PHYSGL_rotate.axis.x,_PHYSGL_rotate.axis.z,_PHYSGL_rotate.axis.y),_PHYSGL_rotate.angle);
	torus.position.set(position[0],position[1],position[2]);
	return(torus);

}


function _PHYSGL_draw_plane(normal,z,color,side)
{
	var material = new THREE.MeshLambertMaterial({ color: color.color,map: color.map});
	var geo = new THREE.CubeGeometry( side, side, 1 );
	geo.applyMatrix( new THREE.Matrix4().makeTranslation( _PHYSGL_rotate.origin[0],_PHYSGL_rotate.origin[1],_PHYSGL_rotate.origin[2]));
	var plane = new THREE.Mesh(geo, material);
	var orig, align, axis, angle, q;
	
	orig = new THREE.Vector3(0,0,-1);
	align = new THREE.Vector3(normal[0],normal[1],normal[2]);
	align.normalize();
	axis = new THREE.Vector3();
	axis.crossVectors(orig,align);
	axis.normalize();
	angle = Math.acos(align.dot(orig));
	q = new THREE.Quaternion();
	q.setFromAxisAngle(axis,angle);
	//plane.useQuaternion = true;
	plane.quaternion = q;
	
	align.multiplyScalar(z);
	plane.rotateOnAxis(new THREE.Vector3(-_PHYSGL_rotate.axis.x,-_PHYSGL_rotate.axis.z,_PHYSGL_rotate.axis.y),_PHYSGL_rotate.angle);
	plane.position.x = align.dot(new THREE.Vector3(1,0,0));
	plane.position.y = align.dot(new THREE.Vector3(0,1,0));
	plane.position.z = align.dot(new THREE.Vector3(0,0,1));
	return(plane);

}

function _PHYSGL_dtext(x,y,z,str,size,color)
{
		var result = new THREE.Object3D();
    	var material = new THREE.MeshLambertMaterial({ color: color.color,map: color.map});
    	textWhy = new THREE.TextGeometry( str, { size: size,height: 0.15, curveSegments: 6, font: "helvetiker", weight: "normal", style: "normal"});
		textWhy.applyMatrix( new THREE.Matrix4().makeTranslation( _PHYSGL_rotate.origin[0],_PHYSGL_rotate.origin[1],_PHYSGL_rotate.origin[2]));
		text = new THREE.Mesh(textWhy,material);
		text.position.set(x,y,z);
		result.add(text);
		result.rotateOnAxis(_PHYSGL_rotate.axis,_PHYSGL_rotate.angle);
		return(result);
}

function _PHYSGL_draw_spring(x1,y1,z1,x2,y2,z2,R,thick,color,persist)
{
	var numPoints = 1000;
	var result = new THREE.Object3D();
	var points = [];
	var i,s,ds;
	var dx = x2-x1;
	var dy = y2-y1;
	var dz = z2-z1;
	var r = Math.sqrt(dx*dx+dy*dy+dz*dz);
	

	ds = r/50.0;
	if (ds < 0.01)
		ds = 0.01;
		
	f = 10.0/(r+1);
	for(s=0;s<=r;s += ds)
		points.push(new THREE.Vector3(R*Math.cos(2*Math.PI*f*s),s,R*Math.sin(2*Math.PI*f*s)));

	spline = new THREE.SplineCurve3(points);
	
	var geometry = new THREE.TubeGeometry(spline, 200, thick ,R , false);
	geometry.dynamic = true;
	var material = new THREE.MeshLambertMaterial({color: color.color, map: color.map});
	tube = new THREE.Mesh(geometry,material);
	tube.position.set(0,0,0);
	result.add(tube);
	
	if (dx ==0 && dz == 0 && dy < 0)
		result.rotation.z = Math.PI;
	else
		{
			orig = new THREE.Vector3(0,r,0);
			orig.normalize();
			align = new THREE.Vector3(dx,dy,dz);
			align.normalize();
			axis = new THREE.Vector3();
			axis.crossVectors(orig,align);
			axis.normalize();
			angle = Math.acos(align.dot(orig));
			q = new THREE.Quaternion();
			q.setFromAxisAngle(axis,angle);
			result.quaternion = q;
		}
	
	result.position.set(x1,y1,z1);
	return(result);
}
	

function _PHYSGL_hcolor(text_color)
{	
	switch($.trim(text_color.toLowerCase()))
		{
			case 'brick': return({color: null,map: _PHYSGL_textures.brick});
			case 'metal': return({color: null,map: _PHYSGL_textures.metal});
			case 'rope': return({color: null,map: _PHYSGL_textures.rope});
			case 'crate': return({color: null,map: _PHYSGL_textures.crate});
			case 'water': return({color: null,map: _PHYSGL_textures.water});
			case 'grass': return({color: null,map: _PHYSGL_textures.grass});
			case 'checker01': return({color: null,map: _PHYSGL_textures.checker01});
			case 'black':return({color: 0x000000,map: null});
			case 'navy':return({color: 0x000080,map: null});
			case 'darkblue':return({color: 0x00008b,map: null});
			case 'mediumblue':return({color: 0x0000cd,map: null});
			case 'blue':return({color: 0x0000ff,map: null});
			case 'darkgreen':return({color: 0x006400,map: null});
			case 'green':return({color: 0x008000,map: null});
			case 'teal':return({color: 0x008080,map: null});
			case 'darkcyan':return({color: 0x008b8b,map: null});
			case 'deepskyblue':return({color: 0x00bfff,map: null});
			case 'darkturquoise':return({color: 0x00ced1,map: null});
			case 'mediumspringgreen':return({color: 0x00fa9a,map: null});
			case 'lime':return({color: 0x00ff00,map: null});
			case 'springgreen':return({color: 0x00ff7f,map: null});
			case 'aqua':return({color: 0x00ffff,map: null});
			case 'cyan':return({color: 0x00ffff,map: null});
			case 'midnightblue':return({color: 0x191970,map: null});
			case 'dodgerblue':return({color: 0x1e90ff,map: null});
			case 'lightseagreen':return({color: 0x20b2aa,map: null});
			case 'forestgreen':return({color: 0x228b22,map: null});
			case 'seagreen':return({color: 0x2e8b57,map: null});
			case 'darkslategray':return({color: 0x2f4f4f,map: null});
			case 'darkslategrey':return({color: 0x2f4f4f,map: null});
			case 'limegreen':return({color: 0x32cd32,map: null});
			case 'mediumseagreen':return({color: 0x3cb371,map: null});
			case 'turquoise':return({color: 0x40e0d0,map: null});
			case 'royalblue':return({color: 0x4169e1,map: null});
			case 'steelblue':return({color: 0x4682b4,map: null});
			case 'darkslateblue':return({color: 0x483d8b,map: null});
			case 'mediumturquoise':return({color: 0x48d1cc,map: null});
			case 'indigo':return({color: 0x4b0082,map: null});
			case 'darkolivegreen':return({color: 0x556b2f,map: null});
			case 'cadetblue':return({color: 0x5f9ea0,map: null});
			case 'cornflowerblue':return({color: 0x6495ed,map: null});
			case 'mediumaquamarine':return({color: 0x66cdaa,map: null});
			case 'dimgray':return({color: 0x696969,map: null});
			case 'dimgrey':return({color: 0x696969,map: null});
			case 'slateblue':return({color: 0x6a5acd,map: null});
			case 'olivedrab':return({color: 0x6b8e23,map: null});
			case 'slategray':return({color: 0x708090,map: null});
			case 'slategrey':return({color: 0x708090,map: null});
			case 'lightslategray':return({color: 0x778899,map: null});
			case 'lightslategrey':return({color: 0x778899,map: null});
			case 'mediumslateblue':return({color: 0x7b68ee,map: null});
			case 'lawngreen':return({color: 0x7cfc00,map: null});
			case 'chartreuse':return({color: 0x7fff00,map: null});
			case 'aquamarine':return({color: 0x7fffd4,map: null});
			case 'maroon':return({color: 0x800000,map: null});
			case 'purple':return({color: 0x800080,map: null});
			case 'olive':return({color: 0x808000,map: null});
			case 'gray':return({color: 0x808080,map: null});
			case 'grey':return({color: 0x808080,map: null});
			case 'skyblue':return({color: 0x87ceeb,map: null});
			case 'lightskyblue':return({color: 0x87cefa,map: null});
			case 'blueviolet':return({color: 0x8a2be2,map: null});
			case 'darkred':return({color: 0x8b0000,map: null});
			case 'darkmagenta':return({color: 0x8b008b,map: null});
			case 'saddlebrown':return({color: 0x8b4513,map: null});
			case 'darkseagreen':return({color: 0x8fbc8f,map: null});
			case 'lightgreen':return({color: 0x90ee90,map: null});
			case 'mediumpurple':return({color: 0x9370d8,map: null});
			case 'darkviolet':return({color: 0x9400d3,map: null});
			case 'palegreen':return({color: 0x98fb98,map: null});
			case 'darkorchid':return({color: 0x9932cc,map: null});
			case 'yellowgreen':return({color: 0x9acd32,map: null});
			case 'sienna':return({color: 0xa0522d,map: null});
			case 'brown':return({color: 0x835c3b,map: null});
			case 'darkgray':return({color: 0xa9a9a9,map: null});
			case 'darkgrey':return({color: 0xa9a9a9,map: null});
			case 'lightblue':return({color: 0xadd8e6,map: null});
			case 'greenyellow':return({color: 0xadff2f,map: null});
			case 'paleturquoise':return({color: 0xafeeee,map: null});
			case 'lightsteelblue':return({color: 0xb0c4de,map: null});
			case 'powderblue':return({color: 0xb0e0e6,map: null});
			case 'firebrick':return({color: 0xb22222,map: null});
			case 'darkgoldenrod':return({color: 0xb8860b,map: null});
			case 'mediumorchid':return({color: 0xba55d3,map: null});
			case 'rosybrown':return({color: 0xbc8f8f,map: null});
			case 'darkkhaki':return({color: 0xbdb76b,map: null});
			case 'silver':return({color: 0xc0c0c0,map: null});
			case 'mediumvioletred':return({color: 0xc71585,map: null});
			case 'indianred':return({color: 0xcd5c5c,map: null});
			case 'peru':return({color: 0xcd853f,map: null});
			case 'chocolate':return({color: 0xd2691e,map: null});
			case 'tan':return({color: 0xd2b48c,map: null});
			case 'lightgray':return({color: 0xd3d3d3,map: null});
			case 'lightgrey':return({color: 0xd3d3d3,map: null});
			case 'palevioletred':return({color: 0xd87093,map: null});
			case 'thistle':return({color: 0xd8bfd8,map: null});
			case 'orchid':return({color: 0xda70d6,map: null});
			case 'goldenrod':return({color: 0xdaa520,map: null});
			case 'crimson':return({color: 0xdc143c,map: null});
			case 'gainsboro':return({color: 0xdcdcdc,map: null});
			case 'plum':return({color: 0xdda0dd,map: null});
			case 'burlywood':return({color: 0xdeb887,map: null});
			case 'lightcyan':return({color: 0xe0ffff,map: null});
			case 'lavender':return({color: 0xe6e6fa,map: null});
			case 'darksalmon':return({color: 0xe9967a,map: null});
			case 'violet':return({color: 0xee82ee,map: null});
			case 'palegoldenrod':return({color: 0xeee8aa,map: null});
			case 'lightcoral':return({color: 0xf08080,map: null});
			case 'khaki':return({color: 0xf0e68c,map: null});
			case 'aliceblue':return({color: 0xf0f8ff,map: null});
			case 'honeydew':return({color: 0xf0fff0,map: null});
			case 'azure':return({color: 0xf0ffff,map: null});
			case 'sandybrown':return({color: 0xf4a460,map: null});
			case 'wheat':return({color: 0xf5deb3,map: null});
			case 'beige':return({color: 0xf5f5dc,map: null});
			case 'whitesmoke':return({color: 0xf5f5f5,map: null});
			case 'mintcream':return({color: 0xf5fffa,map: null});
			case 'ghostwhite':return({color: 0xf8f8ff,map: null});
			case 'salmon':return({color: 0xfa8072,map: null});
			case 'antiquewhite':return({color: 0xfaebd7,map: null});
			case 'linen':return({color: 0xfaf0e6,map: null});
			case 'lightgoldenrodyellow':return({color: 0xfafad2,map: null});
			case 'oldlace':return({color: 0xfdf5e6,map: null});
			case 'red':return({color: 0xff0000,map: null});
			case 'fuchsia':return({color: 0xff00ff,map: null});
			case 'magenta':return({color: 0xff00ff,map: null});
			case 'deeppink':return({color: 0xff1493,map: null});
			case 'oranger':return({color: 0xff4500,map: null});
			case 'tomato':return({color: 0xff6347,map: null});
			case 'hotpink':return({color: 0xff69b4,map: null});
			case 'coral':return({color: 0xff7f50,map: null});
			case 'darkorange':return({color: 0xff8c00,map: null});
			case 'lightsalmon':return({color: 0xffa07a,map: null});
			case 'orange':return({color: 0xffa500,map: null});
			case 'lightpink':return({color: 0xffb6c1,map: null});
			case 'pink':return({color: 0xffc0cb,map: null});
			case 'gold':return({color: 0xffd700,map: null});
			case 'peachpuff':return({color: 0xffdab9,map: null});
			case 'navajowhite':return({color: 0xffdead,map: null});
			case 'moccasin':return({color: 0xffe4b5,map: null});
			case 'bisque':return({color: 0xffe4c4,map: null});
			case 'mistyrose':return({color: 0xffe4e1,map: null});
			case 'blanchedalmond':return({color: 0xffebcd,map: null});
			case 'papayawhip':return({color: 0xffefd5,map: null});
			case 'lavenderblush':return({color: 0xfff0f5,map: null});
			case 'seashell':return({color: 0xfff5ee,map: null});
			case 'cornsilk':return({color: 0xfff8dc,map: null});
			case 'lemonchiffon':return({color: 0xfffacd,map: null});
			case 'floralwhite':return({color: 0xfffaf0,map: null});
			case 'snow':return({color: 0xfffafa,map: null});
			case 'yellow':return({color: 0xffff00,map: null});
			case 'lightyellow':return({color: 0xffffe0,map: null});
			case 'ivory':return({color: 0xfffff0,map: null});
			case 'white':return({color: 0xffffff,map: null});
			default: return('0x' + $.trim(color));
		}
}
