<?php
include('config_paths.php');

if (empty($_GET['hide']))
  $Hide = "";
else $Hide = $_GET['hide'];

$about_link = anchor("welcome/about","About",Array("class" => "nav-link"));

if (empty($home_link))
	$home_link = false;

$li_form = form_open("welcome/login");
$login = "";
$ca = "";
if ($login_form == 'yes')
{
	$login .= form_open("welcome/authenticate");
	$login .= '<span class="navbar-text">Username: </span>';
	$login .= "<input type=text name=username>  ";
	$login .= '<span class="navbar-text">Password: </span>';
	$login .=  "<input type=password name=password>  ";
	$login .=  "<input class='btn btn-outline-success btn-sm' type=submit value=\"Sign in\">";
	$ca = '<li class="nav-item">';
	$ca .= anchor("welcome/create_account","Create account",Array("class" => "nav-link"));
	$ca .= '</li>';
}

if ($home_link === true)
	{
		$login .=  anchor("welcome/","Home",Array("class" => "nav-link"));
	}
if ($login_form == 'no')
	{
		$login .=  "<span class='navbar-text'>($username)</span>";
		$login .=  anchor("welcome/logout","Logout",Array("class" => "nav-link"));
	}
?>

<?php
	if ($login_form == 'yes')
		$login .= "</form>";

PRINT<<<END
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>PhysGL: Easy 3D graphics and animation in the cloud</title>

	<script src="$threejs_path"></script>
	<script src="$graphicslib_path"></script>
	<script src="$functionlib_path"></script>
	<script src="$wrappers_path"></script>
	<script src="$font_path"></script>
	<script src="$jspreprocess_path"></script>
	<script src="$toprefix_path"></script>
	<script src="$errorcheck_path"></script>


<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

   <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
   
   <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js" integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
  
  
   <script src="$dialog_extend"></script>
   
   <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  
   
<script src="$codemirror_path/codemirror.js"></script>
<link rel="stylesheet" href="$codemirror_path/codemirror.css">
<script src="$codemirror_path/lua.js"></script>


<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script>
MathJax = {
  tex: {
    inlineMath: [['$', '$']]
  }
};
</script>
<script id="MathJax-script" async
  src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-chtml.js">
</script>




<link rel="stylesheet" type="text/css" href="$dt_css"/>
<script type="text/javascript" src="$dt_js"></script>



<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37461438-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>



<body>
END;


if (strpos($Hide,"header") === false)
  echo <<<NAV_BAR
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">

 <img src="$base_url/logo.png">


  <!-- Links -->
  <ul class="navbar-nav ml-auto">
  	$login

    <li class="nav-item">
      <a class="nav-link" href=http://www.github.com/tbensky/physgl target="_blank" id="nav">Github</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="https://github.com/tbensky/physgl/blob/master/README.md" target="_blank">Docs</a>
    </li>
    <li class="nav-item">
    	$about_link
    </li>
    <li class="nav-item">
      <a class="nav-link" href="https://groups.google.com/forum/#!forum/physgl-site" target="_blank">Group</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="https://iopscience.iop.org/book/978-1-6817-4425-4" target="_blank">Book</a>
    </li>

    $ca
  </ul>

</nav>
NAV_BAR;


?>
</span>
</div>
<p/>

<div class="container">
	<div class="row">
		<div class="col">
