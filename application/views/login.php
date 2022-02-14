<?php

echo form_open("welcome/authenticate");
echo "Username: ";
echo "<input type=text name=username>";
echo "<br/>";
echo "Password: ";
echo "<input type=password name=password>";
echo "<br/>";
echo "<input type=submit>";
echo form_close();

?>