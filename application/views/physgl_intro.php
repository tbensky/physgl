<?php

echo <<<EOT
<div id="intro_text">
PhysGL is an open-source, 3D graphics scripting langauge and programming environment.  It works right in your 
<a href=http://www.khronos.org/webgl/wiki/Getting_a_WebGL_Implementation>WebGL-enabled browser</a> and allows you to easily experiment with 3D graphics, drawing and animation.    It has been used to teach beginning programming, mathematics, physics, and art in the context of computer graphics
and animation. We wrote <a href=https://iopscience.iop.org/book/978-1-6817-4425-4 target="_blank">a book</a> about using PhysGL to study physics.
<p/>
Here are some examples:
<span style="font-size: 12pt;">
<a href="javascript:void(0);" onClick="example(0);">Red sphere</a>, 
<a href="javascript:void(0);" onClick="example(7);">Stick figure</a>,
<a href="javascript:void(0);" onClick="example(1);">Bouncing ball</a>,
<a href="javascript:void(0);" onClick="example(2);">Drawing</a>,
<a href="javascript:void(0);" onClick="example(3);">Ball on ramp</a>,
<a href="javascript:void(0);" onClick="example(4);">Basic animation</a>,
<a href="javascript:void(0);" onClick="example(5);">Balls</a>,
<a href="javascript:void(0);" onClick="example(6);">Block hits spring</a>,
<a href="javascript:void(0);" onClick="example(8);">Plane normal</a>,
<a href="javascript:void(0);" onClick="example(9);">E-field vectors of a capacitor</a>,
<a href="javascript:void(0);" onClick="example(10);">B-field vectors of a wire</a>,
<a href="javascript:void(0);" onClick="example(11);">Ball on loop-the-loop</a>,
<a href="javascript:void(0);" onClick="example(12);">Game of Pong</a>,
<a href="javascript:void(0);" onClick="example(13);">Interaction</a>,
<a href="javascript:void(0);" onClick="example(14);">Circular motion</a>,
<a href="javascript:void(0);" onClick="example(15);">Ball off table</a>,
<a href="javascript:void(0);" onClick="example(16);">The classic physics projectile</a>,
<a href="javascript:void(0);" onClick="example(17);">Spheres</a>,
<a href="javascript:void(0);" onClick="example(18);">Falling balls</a>,
<a href="javascript:void(0);" onClick="example(19);">Trefoil Knot</a>,
<a href="javascript:void(0);" onClick="example(20);">Breathing Knot</a>,
</span>



</div>

EOT;
?>

<script>

function example(n)
{
	var code;
	
	switch(n)
		{
			case 0:
				code = 'draw_sphere(<0,0,0>,30,"red")';
				break;
			case 1: 
				code = 'camera%28%3C0%2C50%2C700%3E%2C%3C0%2C0%2C0%3E%29%0Atheta%3DPi/2.5%0Av0%3D40%0Apos%3D%3C-100%2C0%2C0%3E%0Avel%3D%3Cv0*cos%28theta%29%2Cv0*sin%28theta%29%2C0%3E%0Ag%3D-9.8%0Aa%3D%3C0%2Cg%2C0%3E%0At%3D0%0Adt%3D0.25%0Aframes_per_second%2860%29%0A%0Aset_vector_thickness%282%29%0Aset_vector_label_scale%285%29%0Adraw_plane%28%3C0%2C1%2C0%3E%2C-15%2C%22green%22%2C500%2Ctrue%29%0A%0Awhile%20t%3C%2035%20animate%0A%09draw_sphere%28pos%2C10%2C%22blue%22%29%0A%09draw_label_vector%28pos%2Cvel%2C%22red%22%2C%22v%22%29%0A%09draw_label_vector%28pos%2Ca%2C%22blue%22%2C%22a%22%29%0A%09draw_label_vector%28pos%2C%3Cvel.x%2C0%2C0%3E%2C%22yellow%22%2C%22vx%22%29%0A%09draw_label_vector%28pos%2C%3C0%2Cvel.y%2C0%3E%2C%22purple%22%2C%22vy%22%29%0A%09%0A%09pos%3Dpos+vel*dt+0.5*a*dt*dt%0A%09vel%3Dvel+a*dt%0A%09%0A%09if%20pos.y%20%3C%200%20and%20vel.y%20%3C%200%20then%0A%20%20%20%20%20%20vel.y%20%3D%20-vel.y%0A%20%20%20%20end%0A%20%20%20%20if%20pos.x%20%3E%20100%20and%20vel.x%20%3E%200%20then%0A%20%20%20%20%09vel.x%20%3D-vel.x%0A%09end%0A%09t%3Dt+dt%0Aend%0A';
				break;
			case 2:
				code = 'pos%3D%3C1%2C1%2C1%3E%0Avel%3D%3C3%2C3%2C2%3E%0Adt%3D.1%0Aa%3D%3C5%2C5%2C5%3E%0Adraw_label_vector%28pos%2Ca%2C%22red%22%2C%22test%22%29%0Adraw_label_vector%28pos%2C2*a%2C%22red%22%2C%22a%22%29%0Adraw_sphere%28%3C0%2C-20%2C0%3E%2C15%2C%22yellow%22%29%0Apos%20%3D%20pos+vel*dt+0.5*a*dt*dt%0Adraw_sphere%28pos%2C5%2C%22orange%22%29%0Adraw_vector%28pos%2Cvel%2C%22red%22%2C%22v%22%29%0Adraw_vector%28pos%2C2*a%2C%22red%22%2C%22a%22%29%0Adraw_box%28%3C-35%2C-25%2C-50%3E%2C%3C-20%2C-20%2C50%3E%2C%22green%22%29%0Aprintxy%28-5*pos%2C%22hello%22%2C20%2C%22green%22%29%0Awriteln%28%27hello%27%29'
				break;
			case 3: 
				code = 'camera%28%3C0%2C0%2C35%3E%2C%3C0%2C0%2C0%3E%29%0A%0Afunction%20f%28x%29%0A%20%20%20%20return%28A*%281+tanh%28B*x%29%29%29%0Aend%0A%0Afunction%20fp%28x%29%0A%20%20return%28A*B*sech%28B*x%29%5E2%29%0Aend%0A%0Afunction%20fpp%28x%29%0A%20%20return%28-2.0*A*B%5E2*sech%28B*x%29%5E2*tanh%28B*x%29%29%0Aend%0A%0AA%3D2%0AB%3D.3%0Aplotxy%28f%2C-15%2C15%2C0.5%2C0%2C%22yellow%22%2C0.2%2Ctrue%29%0Apos%3D%3C-10%2C0%2C0%3E%0Avel%20%3D%20%3C6%2C0%2C0%3E%0At%20%3D%200%0Adt%20%3D%200.05%0Ag%3D9.8%0Am%3D0.5%0Aset_vector_scale%28.5%29%0Aset_vector_thickness%28.2%29%0Aset_vector_label_scale%280.5%29%0Anew_graph%28%27Time%27%2C%27Energy%27%2C%27KE%27%2C%27PE%27%29%0Awhile%20t%20%3C%205%20animate%0A%20%20ax%20%3D%20-fp%28pos.x%29%20*%20%28fpp%28pos.x%29*vel.x%5E2+g%29/%281+fp%28pos.x%29%5E2%29%0A%20%20ay%20%3D%20fpp%28pos.x%29*vel.x%5E2+fp%28pos.x%29*ax%0A%20%20a%20%3D%20%3Cax%2Cay%2C0%3E%0A%20%20Nx%20%3D%20m%20*ax%0A%20%20Ny%20%3D%20m*%28g+ay%29%0A%20%20N%3D%3Cm*ax%2Cm*%28g+ay%29%2C0%3E%0A%0A%20%20draw_sphere%28pos%2C1%2C%22red%22%29%0A%20%20draw_label_vector%28pos%2Cvel%2C%22blue%22%2C%22v%22%29%0A%20%20draw_label_vector%28pos%2Ca%2C%22green%22%2C%22a%22%29%0A%20%20draw_label_vector%28pos%2CN%2C%22purple%22%2C%22N%22%29%0A%0A%0A%20%20pos%20%3D%20pos%20+%20vel*dt%20+%200.5*a*dt%5E2%0A%20%20vel%20%3D%20vel%20+%20a*dt%0A%20%20%0A%20%20KE%3D0.5*m*vel*vel%0A%20%20PE%3Dm*g*pos.y%0A%20%20E%20%3D%20KE+PE%0A%20%20go_graph%28t%2CE%2CKE%2CPE%29%0A%20%20t%3Dt+dt%0Aend%0A';
				break;
			case 4:
				code = 'x%3D-90%0Awhile%20x%20%3C%20200%20animate%0A%09pos%3D%3Cx%2C0%2C0%3E%0A%09draw_sphere%28pos%2C5%2C%22red%22%29%0A%09x%3Dx+2%0Aend%20';
				break;
			case 5:
				code = 'camera(%3C200,0,300%3E,%3C0,0,0%3E)%0Axx=rnd(-200,200)%0Ayy=rnd(-200,200)%0Azz=rnd(-200,200)%0Afor%20i=200,1%20do%0A%09x=rnd(-200,200)%0A%09y=rnd(-200,200)%0A%09z=rnd(-200,200)%0A%09draw_sphere(%3Cx,y,z%3E,15,%22yellow%22,true)%0A%20%20%09draw_line(%3Cx,y,z%3E,%3Cxx,yy,zz%3E,%22red%22,2,true)%0A%20%20%09xx=x%0A%20%20%09yy=y%0A%20%20%09zz=z%0Aend%0A%0Aframes_per_second(60)%0At%20=%200%0Awhile%20t%20%3C%2020%20animate%0A%09x=500*cos(2*Pi*t)%0A%09y=500*Math.tan(2*Pi*t)%0A%09z=500*sin(2*Pi*t)%0A%09camera(%3Cx,y,z%3E,%3C0,0,0%3E)%0A%09light(%3Cx,0,z%3E)%0A%09t=t+0.01%0Aend';
				break;
			case 6:
				code='camera%28%3C0%2C0%2C250%3E%2C%3C0%2C0%2C0%3E%29%0Apos%20%3D%20%3C-100%2C0%2C0%3E%0Avel%3D%3C35%2C0%2C0%3E%0Aa%3D%3C0%2C0%2C0%3E%0Ak%3D.5%0As0%3D20%0Asx%20%3D%20s0%0At%3D0%0Am%3D1%0Adt%20%3D%200.1%0Anew_graph%28%22time%22%2C%22energy%22%29%0Awhile%20t%3C10%20animate%0A%09draw_cube%28pos%2C50%2C%27red%27%29%0A%09pos%20%3Dpos+vel*dt+0.5*a*dt%5E2%0A%09vel%3Dvel+a*dt%0A%09a%3D%3C0%2C0%2C0%3E%0A%09sx%3Ds0%0A%09if%20pos.x%20%3E%20s0%20then%0A%20%20%09%09a%3D%3C-k*%28pos.x-s0%29/m%2C0%2C0%3E%0A%20%20%09%09sx%3Dpos.x%0A%20%09end%0A%09if%20pos.x%20%3C%20-100%20and%20vel.x%20%3C%200%20then%0A%20%20%09%09vel.x%20%3D%20-vel.x%0A%20%20%09end%0A%09dx%20%3D%20sx-s0%0A%09draw_spring%28%3Csx%2C0%2C0%3E%2C%3C150%2C0%2C0%3E%2C20%2C2%2C%27yellow%27%29%0A%09E%3D0.5*m*vel*vel+0.5*k*dx%5E2%0A%09go_graph%28t%2CE%29%0A%09t%3Dt+0.1%0Aend';
				break;
			case 7: 
				code = 'camera%28%3C0%2C0%2C600%3E%2C%3C0%2C0%2C0%3E%29%0Adraw_sphere%28%3C0%2C0%2C0%3E%2C20%2C%22orange%22%29%0A//body%0Adraw_line%28%3C0%2C0%2C0%3E%2C%3C0%2C-100%2C0%3E%2C%22yellow%22%2C5%29%0A//arms%0Adraw_line%28%3C0%2C-25%2C0%3E%2C%3C-40%2C-65%2C0%3E%2C%22yellow%22%2C5%29%0Adraw_line%28%3C0%2C-25%2C0%3E%2C%3C40%2C-65%2C0%3E%2C%22yellow%22%2C5%29%0A//legs%0Adraw_line%28%3C0%2C-100%2C0%3E%2C%3C-50%2C-140%2C0%3E%2C%22yellow%22%2C5%29%0Adraw_line%28%3C0%2C-100%2C0%3E%2C%3C50%2C-140%2C0%3E%2C%22yellow%22%2C5%29%0A';
				break;
			case 8:
				code = '//normal%20to%20a%20plane%0A//click%20and%20drag%20in%20the%20graphics%20window%0Acamera%28%3C0%2C100%2C150%3E%2C%3C0%2C0%2C0%3E%29%0An%20%3D%20%3C1%2C1%2C0%3E%0Adraw_vector%28%3C0%2C0%2C0%3E%2C5*n%2C%22red%22%2C%22n%22%29%0Adraw_plane%28n%2C0%2C%22green%22%2C50%29';
				break;
			case 9:
				code = 'camera%28%3C2%2C2%2C15%3E%2C%3C0%2C0%2C0%3E%29%0Aaxlen%20%3D%205%0Adraw_line%28%3C-axlen%2C0%2C0%3E%2C%3Caxlen%2C0%2C0%3E%2C%22red%22%2C0.1%2Ctrue%29%0Aprintxy%28%3Caxlen%2C0%2C0%3E%2C%22x%22%2C0.5%2C%22red%22%2Ctrue%29%0Adraw_line%28%3C0%2C-axlen%2C0%3E%2C%3C0%2Caxlen%2C0%3E%2C%22green%22%2C0.1%2Ctrue%29%0Aprintxy%28%3C0%2Caxlen%2C0%3E%2C%22y%22%2C0.5%2C%22green%22%2Ctrue%29%0Adraw_line%28%3C0%2C0%2C-axlen%3E%2C%3C0%2C0%2Caxlen%3E%2C%22blue%22%2C0.1%2Ctrue%29%0Aprintxy%28%3C0%2C0%2Caxlen%3E%2C%22z%22%2C0.5%2C%22blue%22%2Ctrue%29%0Adraw_box%28%3C-4%2C0%2C3%3E%2C%3C4%2C0.05%2C-3%3E%2C%22orange%22%29%0Adraw_box%28%3C-4%2C2.0%2C3%3E%2C%3C4%2C2.05%2C-3%3E%2C%22orange%22%29%0Aset_vector_thickness%280.1%29%0Aset_vector_scale%281%29%0A%0Afor%20x%20%3D%20-3.5%2C3.5%20do%0A%20%20%20for%09z%20%3D%20-2.5%2C2.5%20do%0A%20%20%20%20%20%20for%20y%20%3D%200%2C1.0%20do%0A%20%20%20%20%20%20Evec%20%3D%20%3C0%2C0.8%2C0%3E%0A%20%20%20%20%20%20draw_vector%28%3Cx%2Cy%2Cz%3E%2CEvec%2C%22gray%22%29%0A%20%20%20%20%20%20end%0A%20%20%20end%0Aend';
				break;
			case 10:
				code = 'camera%28%3C2%2C2%2C15%3E%2C%3C0%2C0%2C0%3E%29%0Adraw_axes_full%280.04%2C0.1%29%0Aset_vector_thickness%280.1%29%0Adraw_line%28%3C0%2C0%2C-10%3E%2C%3C0%2C0%2C10%3E%2C%22purple%22%2C0.1%2Ctrue%29%0Afor%20z%20%3D%20-4%2C4%20do%0A%20%20%20for%09r%20%3D%201%2C3%20do%0A%20%20%20%20%20%20for%20theta%20%3D%200%2C2*Pi%2CPi/4%20do%0A%20%20%20%20%20%20%20%20%20x%20%3D%20r*cos%28theta%29%0A%20%20%20%20%20%20%20%20%20y%20%3D%20r*sin%28theta%29%0A%20%20%20%20%20%20%20%20%20Bvec%20%3D%20%281/r%29*%3C-sin%28theta%29%2Ccos%28theta%29%2C0%3E%0A%20%20%20%20%20%20%20%20%20draw_vector%28%3Cx%2Cy%2Cz%3E%2CBvec%2C%22yellow%22%29%0A%20%20%20%20%20%20end%0A%20%20%20end%0Aend';
				break;
			case 11:
				code = 'A%3D3%0Acamera%28%3C0%2C0%2C50%3E%2C%3C0%2C0%2C0%3E%29%0Afunction%20tris_x%28p%29%0A%20%20%20%20return%28A*%28p*%28p*p-3%29%29/%28p*p+1%29%29%0Aend%0A%0Afunction%20tris_y%28p%29%0A%09return%28-A*%28p*p-3%29/%28p*p+1%29%29%0Aend%0A%0Afunction%20dxdu%28p%29%0A%20%20%20return%28-2*%28p*p%20-%203%29*A*p*p/%28p*p%20+%201%29%5E2%20+%202*A*p*p/%28p*p%20+%201%29%20+%20%28p*p%20-%203%29*A/%28p*p%20+1%29%29%0Aend%0A%0Afunction%20d2xdu%28p%29%0A%20%20%20%20return%288*%28p*p%20-%203%29*A*p*p*p/%28p*p%20+%201%29%5E3%20-%208*A*p*p*p/%28p*p%20+%201%29%5E2%20-%206*%28p*p%20-3%29*A*p/%28p*p%20+%201%29%5E2%20+%206*A*p/%28p*p%20+%201%29%29%0Aend%0A%0A%0Afunction%20dydu%28p%29%0A%09return%282*%28p*p%20-%203%29*A*p/%28p*p%20+%201%29%5E2%20-%202*A*p/%28p*p%20+%201%29%29%0Aend%0A%0Afunction%20d2ydu%28p%29%0A%09return%28-8*%28p*p%20-%203%29*A*p*p/%28p*p%20+%201%29%5E3%20+%208*A*p*p/%28p*p%20+%201%29%5E2%20+%202*%28p*p%20-3%29*A/%28p*p%20+%201%29%5E2%20-%202*A/%28p*p%20+%201%29%29%0Aend%0A%0Aset_vector_scale%280.2%29%0Aset_vector_label_scale%280.75%29%0Aset_vector_thickness%28.3%29%0A%0Ag%3D9.8%0AU%3D-9%0Apos%20%3D%20%3Ctris_x%28U%29%2Ctris_y%28U%29%2C0%3E%0Avel%20%3D%20%3C17%2C0%2C0%3E%0Aa%20%3D%20%3C0%2C0%2C0%3E%0Am%3D1%0Adt%20%3D%200.05%0At%20%3D%200.0%20%20%0AU_d%3Dvel.x/dxdu%28U%29%0A%0Anew_spline%28%29%0Afor%20u%3D-10%2C10%2C.1%20do%0A%09add_spline%28%3Ctris_x%28u%29%2Ctris_y%28u%29%2C0%3E%29%0Aend%0Adraw_spline%28%22hotpink%22%2C.5%2C200%2Ctrue%29%0A%0Awhile%20t%20%3C%20100%20animate%0A%0A%09U_dd%20%3D%20%28-U_d*U_d%20*%20%28dxdu%28U%29*d2xdu%28U%29+dydu%28U%29*d2ydu%28U%29%29-g*dydu%28U%29%29/%20%28dxdu%28U%29%5E2+dydu%28U%29%5E2%29%0A%09U_d%20%3D%20U_d%20+%20U_dd%20*%20dt%0A%09U%3DU+U_d*dt%0A%09%0A%09a%3D%3Cd2xdu%28U%29*U_d*U_d%20+%20dxdu%28U%29%20*%20U_dd%2C%20d2ydu%28U%29*U_d*U_d+%20dydu%28U%29%20*%20U_dd%2C0%3E%0A%09pos%3D%3Ctris_x%28U%29%2Ctris_y%28U%29%2C0%3E%0A%20%20%20%09vel%3D%20%3Cdxdu%28U%29*U_d%2Cdydu%28U%29*U_d%2C0%3E%0A%0A%09draw_sphere%28pos%2C1.5%2C%22red%22%29%0A%09draw_label_vector%28pos%2Cvel%2C%22blue%22%2C%22v%22%29%0A%09draw_label_vector%28pos%2Ca%2C%22green%22%2C%22a%22%29%0A%20%20%20%20%20%20%20%20%09%0A%09t%3Dt+dt%0Aend%0A';
				break;
			case 12:
				code = 'camera%28%3C0%2C0%2C600%3E%2C%3C0%2C0%2C0%3E%29%0Apos%3D%3C0%2C0%2C0%3E%0Avel%3D%3Crnd%285%2C25%29%2Crnd%285%2C25%29%2C0%3E%0At%3D0%0Adt%3D0.5%0Alen%3D100%0Abottom_row%3D-170%0Atop_row%3D210%0Ah%3D0%0Ac%3D0%0Awhile%20true%20animate%0A%09printxy%28%3C-300%2C200%2C0%3E%2Ch%2C25%2C%22red%22%29%0A%09printxy%28%3C-250%2C200%2C0%3E%2Cc%2C25%2C%22yellow%22%29%0A%09draw_sphere%28pos%2C10%2C%22blue%22%29%0A%09pos%3Dpos+vel*dt%0A%09t%3Dt+dt%0A%09if%20pos.y%20%3E%20200%20and%20vel.y%20%3E%200%20then%0A%20%20%09%09vel.y%3D-vel.y%0A%20%20%09end%0A%0A%09if%20pos.y%20%3C%20-250%20and%20vel.y%20%3C%200%20then%0A%20%20%09%09c%3Dc+10%0A%20%20%09%09pos%3D%3C0%2C0%2C0%3E%0A%20%20%09%09vel%3D%3Crnd%285%2C25%29%2Crnd%285%2C25%29%2C0%3E%0A%20%20%09%09dt%20%3D0.5%0A%20%20%09end%0A%0A%09if%20pos.x%20%3C%20-250%20and%20vel.x%20%3C%200%20then%0A%20%20%09%09vel.x%3D-vel.x%0A%20%20%09end%0A%0A%09if%20pos.x%20%3E%20250%20and%20vel.x%20%3E%200%20then%0A%20%20%09%09vel.x%3D-vel.x%0A%20%20%09end%0A%0A%09if%20pos.y%3Cbottom_row+15%20and%20vel.y%20%3C%200%20and%20pos.x%20%3E%20mousex%28%29%20and%20pos.x%20%3C%20mousex%28%29+len%20then%0A%20%20%09%09vel.y%3D-vel.y%0A%20%20%09end%0A%0A%09draw_line%28%3Cmousex%28%29%2Cbottom_row%2C0%3E%2C%3Cmousex%28%29+len%2Cbottom_row%2C0%3E%2C%22red%22%2C5%29%0A%09draw_line%28%3Cpos.x-len/2%2Ctop_row%2C0%3E%2C%3Cpos.x+len/2%2Ctop_row%2C0%3E%2C%22yellow%22%2C5%29%0A%09dt%3Ddt+0.001%0Aend%20%20';				
                break;
             case 13:
				code = 'new_slider%28%27blue%27%2C10%2C200%2C5%2C2%29%0Anew_slider%28%27red%27%2C10%2C200%2C5%2C2%29%0Awhile%20true%20animate%0A%09rblue%20%3D%20get_slider%28%27blue%27%29%0A%09rred%20%3D%20get_slider%28%27red%27%29%0A%09draw_sphere%28%3C20%2C0%2C0%3E%2Crred%2C%22red%22%29%0A%09draw_sphere%28%3C-20%2C0%2C0%3E%2Crblue%2C%22blue%22%29%0Aend';
				break;
			case 14:
				code = 'pos%3D%3C5%2C0%2C0%3E%0Avel%3D%3C0%2C5%2C0%3E%0Aa%3D%3C0%2C0%2C0%3E%0AR%3D25%0At%3D0%0Adt%3D0.2%0Aset_vector_scale%283%29%0Awhile%20t%3C50%20animate%0A%09draw_sphere%28pos%2C5%2C%22red%22%29%0A%09draw_sphere%28pos%2C1%2C%22yellow%22%2Ctrue%29%0A%09amag%3Dvel%5E2/R%0A%09theta%3Datan2%28vel.y%2Cvel.x%29%0A%09theta%3Dtheta+Pi/2%0A%09a%20%3D%20%3Camag*cos%28theta%29%2Camag*sin%28theta%29%2C0%3E%0A%09draw_vector%28pos%2Cvel%2C%22blue%22%29%0A%09draw_vector%28pos%2Ca%2C%22green%22%29%0A%09pos%3Dpos+vel*dt+0.5*a*dt%5E2%0A%09vel%3Dvel+a*dt%0A%09t%3Dt+dt%0Aend%0A';
				break;
			case 15:
				code = 'camera%28%3C500%2C20%2C300%3E%2C%3C0%2C0%2C0%3E%29%0Alight%28%3C100%2C100%2C50%3E%29%0Adraw_box%28%3C-200%2C0%2C100%3E%2C%3C100%2C-10%2C-100%3E%2C%22red%22%2Ctrue%29%0Apos%20%3D%20%3C-100%2C10%2C0%3E%0Avel%20%3D%20%3C25%2C0%2C0%3E%0At%20%3D%200%0Adt%20%3D%200.1%0Ag%3D9.8%0Aset_vector_scale%282%29%0Aset_vector_thickness%282%29%0Aset_vector_label_scale%282%29%0Adraw_axes%28.7%2Ctrue%29%0Awhile%20t%20%3C%2015%20animate%0A%09draw_cube%28pos%2C20%2C%22blue%22%29%0A%09if%20frame_delta%2810%29%20then%0A%20%20%09%09draw_sphere%28pos%2C3%2C%22yellow%22%2Ctrue%29%0A%20%20%09end%0A%09a%20%3D%20%3C0%2C0%2C0%3E%0A%09if%20pos.x%20%3E%20100%20then%0A%20%20%09%09a%20%3D%20%3C0%2C-g%2C0%3E%0A%20%20%09end%0A%09pos%3Dpos+vel*dt+0.5*a*dt*dt%0A%09vel%3Dvel+a*dt%0A%09draw_label_vector%28pos%2Cvel%2C%22yellow%22%2C%22v%22%29%0A%09draw_label_vector%28pos%2Ca%2C%22green%22%2C%22a%22%29%0A%09t%3Dt+dt%0Aend%0A%09';
				break;
			case 16:
				code = 'camera%28%3C0%2C0%2C200%3E%2C%3C0%2C0%2C0%3E%29%0Apos%3D%3C-70%2C-20%2C0%3E%0Atheta%3DPi/3%0Av0%3D35%0Avel%3D%3Cv0*cos%28theta%29%2Cv0*sin%28theta%29%2C0%3E%0Adt%3D.1%0At%3D0%0Adraw_plane%28%3C0%2C1%2C0%3E%2C-25%2C%22green%22%2C250%2Ctrue%29%0Awhile%20t%3C20%20animate%0A%09a%3D%3C0%2C-9.8%2C0%3E%0A%09draw_sphere%28pos%2C5%2C%22red%22%29%0A%09draw_label_vector%28pos%2Cvel%2C%22green%22%2C%22v%22%29%0A%09draw_label_vector%28pos%2Ca%2C%22blue%22%2C%22a%22%29%0A%09vx%20%3D%20%3Cvel.x%2C0%2C0%3E%0A%09vy%3D%20%3C0%2Cvel.y%2C0%3E%0A%09draw_label_vector%28pos%2Cvx%2C%22blue%22%2C%22vx%22%29%0A%09draw_label_vector%28pos%2Cvy%2C%22black%22%2C%22vy%22%29%0A%09pos%3Dpos+vel*dt+0.5*a*dt%5E2%0A%09vel%3Dvel+a*dt%0A%09t%3Dt+dt%0Aend';
				break;
			case 17:
				code = '//lots%20of%20spheres%0A//click%20and%20drag%20in%20the%20graphics%20window%0A//when%20the%20rendering%20is%20done.%0Ai%20%3D%200%0Awhile%20i%3C500%20do%0A%09draw_sphere%28%3Crnd%28-200%2C200%29%2Crnd%28-200%2C200%29%2Crnd%28-200%2C200%29%3E%2C15%2C%22red%22%29%0A%09i%3Di+1%0Aend';
				break;
			case 18:
				code = 'camera%28%3C0%2C10%2C250%3E%2C%3C0%2C0%2C0%3E%29%0Apos%3D%5B%5D%0Avel%3D%5B%5D%0AN%3D100%0Adraw_plane%28%3C0%2C1%2C0%3E%2C-20%2C%22green%22%2C1000%2Ctrue%29%0Afor%20i%3D0%2CN%20do%0A%20%20pos%5Bi%5D%20%3D%20%3Crnd%28-50%2C50%29%2C100%2Crnd%28-50%2C50%29%3E%0A%20%20vel%5Bi%5D%20%3D%20%3C5*rnd%28%29-2.5%2C0%2C5*rnd%28%29-2.5%3E%0Aend%0A%0Aframes_per_second%2860%29%0At%20%3D%200%0Adt%20%3D%201%0Aa%20%3D%20%3C0%2C-2%2C0%3E%0Ai%3D0%0Awhile%20t%20%3C%20150%20animate%0A%09for%20i%3D1%2CN%20do%0A%20%09%09draw_sphere%28pos%5Bi%5D%2C5%2C%22red%22%29%0A%20%20%09%09pos%5Bi%5D%20%3D%20pos%5Bi%5D+vel%5Bi%5D*dt+0.5*a*dt%5E2%0A%20%20%09%09vel%5Bi%5D%20%3D%20vel%5Bi%5D+a*dt%0A%20%20%09%09if%20pos%5Bi%5D.y%20%3C%200%20and%20vel%5Bi%5D.y%20%3C%200%20then%0A%20%20%20%20%09%09vel%5Bi%5D.y%20%3D%20-rnd%280.8%2C.9%29*vel%5Bi%5D.y%0A%20%20%20%09%09end%0A%20%09end%0A%09t%3Dt+dt%0Aend';
				break;
			case 19: 
				code = 'new_spline%28%29%0AM%3D10%0Afor%20t%3D1%2C10%2C.1%20do%0A%09add_spline%28M*%3Csin%28t%29+2*sin%282*t%29%2Ccos%28t%29-2*cos%282*t%29%2C-sin%283*t%29%3E%29%0Aend%0A%0Adraw_spline%28%22red%22%2C3%2C50%29';
				break;
			case 20:
				code = 's%3D0%0Awhile%20true%20animate%0A%09new_spline%28%29%0A%09zoom%20%3D%2030*sin%28s%29%0A%09for%20t%3D0%2C10%2C0.1%20do%0A%09%09add_spline%28zoom*%3Csin%28t%29+2*sin%282*t%29%2Ccos%28t%29-2*cos%282*t%29%2C-sin%283*t%29%3E%29%0A%09end%0A%09draw_spline%28%22red%22%2C3%2C10%29%0A%09s%3Ds+0.1%0Aend%0A';
				break;
			}
		
	myCodeMirror.setValue(unescape(code));
}
</script>