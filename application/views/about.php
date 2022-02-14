<?php

echo <<<EOT

<div id="intro_text"> 

<h1>About PhysGL</h1>

Illustrations and animations made with computer graphics are a compelling
medium.  Just look at the movie and video game industries. Where would they be
without "nice looking" computer graphics?

<p/>

Graphics can also be a compelling platform in education for the same reasons:
they look "cool," can be used to capture students' interest, and can be used as
a unique medium to illustrate lessons.  Fields easily adaptable to computer
graphics are beginning programming, physics, math, and art.

<p/>

The creators of PhysGL have always had an interest in using computer graphics in
education, but creating great looking graphics has always been prohibitive from
a software standpoint.  We are aware of VPython, Povray, OpenGL, Blender, and
Java3D. We find Python a wonderful language, but too wordy and too object-based
for the beginner. Povray's scripting langauge is awkward, and Java3D/OpenGL have
too much overhead.  We cannot figure out even "step 1" with Blender.

<p/>

We were quite intrigued with the emergence of WebGL, which runs OpenGL in the
browser.  Everyone has a browser and knows how to use it, so there's a huge
barrier removed (no software or frameworks to install).  Next, we found out
about THREE.js, which is an easy-to-use Javascript library for accessing WebGL.
So we put the two together: a ready-built scripting language (Javascript) + a
neat 3D graphics library (THREE.js), which at last gave us a 3D graphics
platform for the beginner. Welcome to PhysGL.

<p/>

PhysGL (short for "physics graphics language") is an <a
href=https://github.com/tbensky/physgl>open-source</a> scripting language that
exists in a browser-based integrated development environment.  (Physics isn't
involved, it's just that PhysGL was first used to illustrate lessons in basic
physics.)  The primary use of PhysGL is to give the programmer the easiest
possible route to creating 3D computer-graphics drawings and animations, using
line-by-line programming.  The language is modeled after <a
href=http://www.lua.org>Lua</a>, and is very plain with no particular feature or
style. Its existence fills a perceived void for the novice programmer, which is
a straightforward programming tool for producing 3D graphics and animation.

<p/>

<h1>Technology</h1>

We are grateful for <a href=http://www.threejs.org>THREE.js</a>, 
<a href=http://www.codemirror.net>Codemirror</a>, 
<a href=https://developers.google.com/chart/>Google Charts</a>,
<a href=http://www.mathjax.org>MathJax</a>,
<a href=http://www.codeigniter.com>Codeigniter</a>, and
<a href=http://www.jquery.com>jQuery</a>.


<h1>Contact</h1>

Email tbensky@calpoly.edu or mmoelter@calpoly.edu with any questions.
EOT;
?>