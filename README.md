# PhysGL

Location: https://physgl.csm.calpoly.edu


Quick Start for the Impatient 

PhysGL is a browser-based scripting language and development environment for quickly creating 3D computer graphics scenes, and/or
animation.  With your WebGL-enabled browser is pointing to a PhysGL installation
(see physgl.org), you should see the main development screen, which consists of
a code-editor to the left and a gray graphics-output window to the right. You
should be able to see a “Run” button under the code editor.


Into the code editor, type this single line

```draw_sphere(<0,0,0>,15,"red")```

then click the “run” button.  You should see a red sphere appear at the center
of the screen (x=0, y=0, z=0) with a radius of 15 pixels.  If you change the
line to


```
draw_sphere(<0,0,100>,15,"red")
```


the sphere should be bigger since the z-coordinate of 100 is closer to you than
0.  You can also draw a cube with


```draw_cube(<0,0,0>,100,"red")```


which will draw a blue cube with a side-length of 20.  If you want to see the
cube, you have to change your viewpoint and lighting a bit. Try this


```
camera(<40,50,100>,<0,0,0>)
light(<0,100,50>)
draw_cube(<0,0,0>,40,"blue")
```


Here we’ve supplied the position and “look at” point for the camera, as well as
the position of the light source.


How about a yellow line with a thickness of 3 with the following?

```
draw_line(<0,0,0>,<150,150,100>,"yellow",3)
```

To make an animation, put your statements that drive the animation between a
while-animate-end block like this

```
x=-200
t=0
while x < 200 animate
        pos = <x,0,0>
        draw_sphere(pos,25,"red")
        x=x+10
end
```

Here’s a physics-based animation that shows a cube falling off of a table.  Note
the acceleration due to gravity is suddenly “turned on” when the x-coordinate of
the box’s position exceeds 100 (the edge of the table).

```
camera(<300,20,300>,<0,0,0>)
light(<100,100,50>)
draw_box(<-200,0,100>,<100,-10,-100>,"red",true)
pos = <-100,10,0>
vel = <25,0,0>
t = 0
dt = 0.1
g=9.8
while t < 15 animate
        draw_cube(pos,20,"blue")
        a = <0,0,0>
        if pos.x > 100 then
                  a = <0,-g,0>
          end
        pos=pos+vel*dt+0.5*a*dt*dt
        vel=vel+a*dt
        t=t+dt
end
```

# Function Library 

Here are many of the core drawing functions you can use.  Note that any
parameters requring a vector (like a position) must be in an explicit vector
form, as in <5,3,2>, or fed with a vector variable.  Thus drawing a sphere can
be done with draw_sphere(<5,3,1>,15,”red”) or with the sequence pos=<5,5,5>
draw_sphere(pos,15,”red”).  Vector (vs. scalar) parameters are denoted in the
list below with a suffix of “-vector” or “-scalar.” Parameters that are to be
text and enclosed in double quotes ave the suffix “-text.”  Note the last
parameter in all functions that draw on the screen is the “persist” flag, which
is a true/false value.  This is optional, but if explicitly set to “true,” 
PhysGL will cause the object to remain on the screen during animation (i.e. not
be erased by the frame clearing).


* camera(position-vector,look-at-vector) - set the camera position and look-at point


* light(position-vector) - set the position of the light source


draw_sphere(position-vector,radius-scalar,color-text[,persist]) - draw a sphere


draw_box(corner1-vector,corner2-vector,color--text[,persist]) - draw a box


draw_cube(center-vector,color--text[,persist]) - draw a cube


draw_line(end1-vector,end2-vector,color--text,thickness-scalar[,persist]) - draw a line


draw_hspring(x0-scalar,x1-scalar,y0-scalar,radius-scalar,color-text[,persist]) - draw a horizontal spring


draw_plane(normal-vector,offset-scalar,color-text,size-scalar[,persist]) - draw a plane normal to the normal-vector with the color and side-length of size.  The plane will be slid along the normal vector by the amount offset.


printxy(position-vector,”text”,size-scalar,color-text[,persist]) - draw some text


rnd([min-scalar,max-scalar]) - find a random number between 0 and 1, or optionally between min and max.


draw_axes(scale-scalar[,persist]) - draw the x-y-z coordinate axes with a zoom factor of  scale


# Installation

To use PhyGL, you need to have a “LAMP” (Linux, Apache, MySql, Php) or
equivalent server available.  In your allocated web-space, unzip the physgl.zip
file.  PhysGL was written using the “Codeigniter” framework, so it is very
portable.  After unzipping it, configure the following.


1. Go to the application/config directory. Edit the file called config.php.
Change the line


$config['base_url'] = 'XXXX;  


to be the base URL of your PhysGL installation.  An example might be: 


$config['base_url'] = 'http://my.server.edu/physics/physgl/';


2. Next, in the same directory, edit database.php.  Look for these lines:

```
$db['default']['hostname'] = 'your-database-hostname';
$db['default']['username'] = 'your-mysql-user-name';
$db['default']['password'] = your-mysql-user-password;
$db['default']['database'] = 'physgl';
```

Set the ‘username’ and ‘password’ fields to your login credentials on your MySql
database.  Keep the ‘database’ name ‘physgl.’  If you change it, change the name
of the database created in the next step too.  Often the ‘hostname’ field of
‘127.0.0.1’ or ‘localhost’ will work.


3. Go to the directory labeled “database” in the root of the PhysGL package. The
MySql commands in the file called “init” will create the database and two tables
needed by PhysGL. For precautions, the “drop” and “create” database lines are
commented out.  Uncomment these two lines when you run the script for the first
time.  When done, at least re-comment out the “drop” line.


That’s it. PhysGL should be ready to go.




# Introduction to PhysGL
PhysGL (short for "physics graphics language") is a scripting language that
exists in a browser-based integrated development environment.  Its primary use
is to give the programmer the easiest route possible to creating 3D
computer-graphics drawings and animations, using line-by-line programming.  The
language is very plain with no particular feature or style that one would
develop any evangelistic zeal for (it will never appear on the TIOBE index, for
example).  Its existence fills a perceived void for the novice programmer, which
is a straightforward programming tool for  producing 3D graphics and animation.


PhysGL has been used mostly by freshman college students, to program and
prototype 3D drawing and animation ideas, in the context of exploring physics
(mechanics).  It is aimed at the programming novice, who is not  interested in
installing frameworks, compiling code, command prompts, import statements,
excessive use of objects or logical requirements in order to draw 3D graphics
and/or create animations.  Its use is in a learning environment where “computer
science” is not the primary goal (or even a goal), as in a physics course, where
graphics are used as a segway for learning physics.


In our learning context, “nice looking” graphics are important to keep the user
interested and to convince them that graphics are a compelling way to learn (in
this case, physics).  Thus, we are not interested in two-dimensional
stick-and-line graphics, but insist on full-three-dimensional capabilities with
shadows, perspective, and other lighting effects (like Lambertian reflectance,
etc.).  We found that this type of graphics are generally the most difficult to
access, and freely-available software for creating such by the novice are few. 
We have tried Povray, VPython, C/OpenGL, Java3D, Sage, Glowscript, and Blender.
These packages all have strengths and weaknesses (mostly weaknesses as the
novice goes), and it was felt there is a need for a new “graphics development
plan” driven by five primary needs, none of which are offered in totality by any
single available packages (which frustrated our learning efforts).  The five
needs are:


1) The novice user has difficulty installing and configuring software.  They
must be able to get up and running quickly and easily with zero software
installation or configuration steps.


2) Today’s novice user typically owns a rather powerful computer (i.e. laptop),
which is often more powerful than our “two-year-old server.”  We’d like to take
advantage of this power. Thus, all CPU-intensive computations, including running
the programs and maintaining any animation frame-rates should use the client's
CPU (i.e. not a central server's CPU). This is also important when instructing
100+ students, who will easily bog down a central server when requesting CPU
intensive operations (such as graphics rendering or other computations), as a
work deadline looms.


3) The novice needs a very direct and obvious “cause and effect” path to seeing
graphics and what creates them.  Thus, we insist on a system whereby a complete
“program” can contains exactly one line of “common sense” text such as


draw_sphere(<0,0,0>,15,"red"),


to show a red “nice-looking” sphere with some shadows and perspective at
coordinates (x=0,y=0,z=0) with a radius of 15 pixels.  This single line is the
cause, and seeing a red sphere immediately appear is the effect. This
one-line-to-graphics is our “litmus test” that most other packages fail.


4) The novice is easily put off (if not outright scared) by curly brackets,
funny words like “void,” and the odd use of semicolons at the end of lines. 
Thus we require a syntactically clean and unpunctuated language, with no
objects, semicolons, void or new statements, curly brackets, import statements,
or required white space.


5) For the budding science student and to aid in the 3D representations, we
insist on a native vector data type.  The data type should be in the form of the
standard called "ordered-set notation," as found in college-level instruction.
Thus x=<5,3,2> is valid and makes x a vector variable with an x-component of 5,
y-component of 3, and z-component of 2. Lines like y=2*<5,3*cos(Pi/7),2> are
valid, as are sequences like

```
dt=0.1
vel=<1,0,0>
pos=<0,0,0>
a=<-2,0,0>
pos=pos+vel*dt+0.5*a*dt*dt
```

Where here we note definitions of both vectors and scalars, and mathematical
operations that mixes the two.


To date, no software package can deliver on all five of these needs, thus the
birth of PhysGL. In this package one will find:


* Zero installation which means the entire platform runs in the browser.  A
server is used only to deliver the platform, store code, and manage users.  To
use PhysGL one must merely point a web-browser to a server hosting it.


* A browser-based application using the client's CPU means basing PhysGL either
on Java or JavaScript. Javascript was chosen here, since it ties easily to the
HTML5 Canvas and WebGL for graphics.


* “Cause and effect” graphics, a clean syntax, and the native vector data type
are handled in PhysGL by the implementation of a Javascript transcompiler.  This
translates our language requirements (the “PhysGL scripting language”) to
Javascript for execution by the browser.


Language 
The PhysGL language is based on the Lua language (lua.org).  Lua has a
clean, minimally punctuated syntax with natural and minimal programming
constructs.  The PhyGL package contains a transcompiler, that converts a
Lua-like language into JavaScript for subsequent execution by the browser.


Variables PhysGL is an untyped language, so variable are declared when assigned
like this:

```
x=5
area=3.1415*2^2
y=5.2/8+15
str= ‘this is a test’
out=”this is a string too”
```

Arrays are initialized using square brackets, like this:

```
my_array = []
numbers = []
ages = [14,20,5]
```

Arrays are zero-index based.


Prefixing any variable definition with the var keyword forces the variable to
remain only in the local scope. In the absence of the var keyword, the variable
is in the global scope (see var in the Javascript context).  Mostly the var
statement can be ignored.


# Constants
Various constants are predefined as follows.
Pi =3.1415...


# Vector data type
PhysGL has a native vector data type.  As common in scientific and mathematical
texts, it uses ordered set or “triple tuple” notation, which is <xx,yy,zz>,
where xx, yy, and zz are the x, y, and z-components of the vector. Thus the
following lines are valid in PhysGL

```
pos=<1,5,-2>
vel=<5*cos(10),2+16^2,1>
```

The language also overloads the common mathematical operators to handle vectors
in arithmetic as follows (assume these definitions: pos=<1,0,0>, pos1=<0,5,1>,
vel=<5,0,0>, a=<-1,1,3> and dt=0.1).


* Dot product: vsq = vel * vel
* Cross product vp = vel # vel (not implemented yet)
* Multiply vector by a scalar: pos = vel * dt
* Add two vectors: pos=pos+pos1
* Subtract two vectors: pos=pos1-pos
* Full vector expression: pos=pos+vel*dt+0.5*a*dt^2




# Loops
PhysGL offers both while for for loop constructs.  Javascript however, is an
awkward single-threaded language, and one must be careful not to have loops that
require a large number of iterations. They’ll work, but your browser will become
inactive until the loop finishes.


The for and while loops are available via for-do-end or while-do-end structures.
Here are two examples that count to 10. Using the while-loop we’ll have

```
i=0
while i < 10 do
 writeln(i)
 i=i+1
end
```

While the for-loop version is:

```
for i=1,10 do
 writeln(i)
end
```

The for-loop also has an optional counting step as follows.

```
for i=1,10,2 do
 writeln(i)
end
```

which will count to 10 by twos.


If-then-statement
The if-statement has an if-then-end structure.  Here is an

```
if x<10 then
 writeln(‘x is less than 10.’)
end
```

Note the then statement is required as is the terminating end statement. 
Logical operators include <, >, <> (for not equal), <=, and >=.  Combination
truth-table elements include and, or and not, as in this example


if x < 10 and y > 50 then
 ….
end


No else construct is available (yet).




Functions
Function are declared starting with the function keyword, a function name, a
required parenthesized, comma-separated argument list, the function body, then
an end statement to terminate the function body.  To return a value to the
caller, the return statement is used, which exits the function returning its
argument to the caller.  Here is an example that take x as an input argument and
returns twice its value through the variable y.


function timestwo(x)
  y=2*x
  return(y)
end




Animation
Animation requires a loop to execute, to allow for rapid drawing and erasing of
the display area to produce “animation.”  This requires a tight loop with close
ties to the browser timer as implemented in Javascript, in order to not paralyze
the browser. This can happen either with the Javascript setTimer or (the newer)
reqeustAnimationFrame function functions.  PhysGL handles all of this in a
special while loop which has a while-animate-end structure (as opposed to the
while-do-end structure).  There may only be one while-animate-end structure in a
given program, akin to the single and eventual event-loop ultimately required in
OpenGL, for instance. Here is an example that will send a red sphere from
left-to-right across the screen.


x=-200
t=0
while x < 200 animate
        pos=<x,0,0>
        draw_sphere(pos,25,"red")
        x=x+10
end


Note the while-animate-end structure is a while loop, but one that prevents the
repeated iterations from dominating the CPU time allocated to the browser 
(which would freeze the browser).  The “animate” keyword tells the while loop to
iterate in a way that the browser can still service its internal needs. Lastly,
this loop structure auto-clears the graphic area at the end of each loop. So for
animation, the programmer needs only worry about drawing (but not removing)
objects within the body of the while-animate-end structure.


PhysGL function library


Graphics
The coordinate system has the x-axis running left and right across the screen
with +x to the the right and -x to the left.  The y-axis runs up and down, with
+y up and -y down.  The +z axis comes out of the screen toward the viewer, with
-z going into the screen.


Function: camera(position,look-at)

Summary: Sets the position of the camera and the point at which the camera
should be aimed.  The default (with no camera function call) is
camera(<0,0,500>,<0,0,0>)


Parameters: position and look-at are both vectors.  position sets the position
of the camera and look-at sets the point at which the camera should be aimed.


Examples: 
camera(<0,0,500>,<0,0,0>)


For an orbiting camera, assuming t is defined:
x=500*cos(2*Pi*t)
z=500*sin(2*Pi*t)
camera(<x,0,z>,<0,0,0>)




Function: light(position)

Summary: Sets the position of the light source.  The default (with no light function call) is light(<0,0,200>)


Parameters: position is a vector, which sets the position of the light source.


Examples: 
light(<0,100,500>)


camera(position,look-at)
light(position)
draw_sphere(position,radius,color[,persist])
draw_box(corner1,corner2,color[,persist])
draw_cube(center,color[,persist])
draw_vector(tail,vector,color,”label”[,persist])
draw_line(end1,end2,color,thickness[,persist])
draw_hspring(x0,x1,y0,radius,color[,persist])
draw_plane(normal,offset,color,size[,persist])
printxy(position,”text”,size,color[,persist])
set_vector_scale(n)
set_vector_thickness(n)
set_vector_label_scale(n)
plotxy(function,xmin,xmax,dx,z,color,thickness[,persist])
rnd([min,max])
draw_axes(scale[,persist])
frame_delta(n)