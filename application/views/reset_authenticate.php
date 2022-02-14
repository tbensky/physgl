<?php

include('config_paths.php');

echo "<div id=\"wrapper\">";
echo "<h1>Reset your password</h1>";

echo <<<EOT

Your password must be reset.

EOT;
echo form_open("welcome/reset_account");
echo "<input type=hidden name=email value=\"$username\">";

echo "<h2>1. User name: $username</h2>";

echo "<p/>";

echo "<h2>2.  Create a good password</h2>";
echo "Your password must have at least 4 characters. Your password can not contain your username or spaces.<p/>";
echo "<input id=account_input type=password name=password size=30>";
echo form_error('password');

echo "<p/>";

echo "<h2>2.  Re-type your password</h2>";
echo "<input id=account_input type=password name=password_confirm size=30>";
echo form_error('password_confirm');

echo "<p/>";

echo "<input type=submit value=\"Reset Password\">  ";
echo anchor("welcome/","Cancel");

echo form_close();

echo "</div>";

?>


