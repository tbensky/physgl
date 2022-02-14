<?php
//$share_hash is defined
echo form_open("welcome/share_prefs_incoming/$share_hash");

$show_code = $this->Code->get_share($share_hash,'show_code');
$code_hash = $this->Code->get_code_hash_from_share_hash($share_hash);
$yclick = $nclick = "";
if ($show_code == 'yes')
	$yclick = "selected";
else $nclick = "selected";
echo "<b>Show code?</b>";
echo "<select name=show_code>";
echo "<option value=yes $yclick>Yes</option>";
echo "<option value=no $nclick>No</option>";
echo "</select>";

echo "<p/>";
echo "<input type=submit>";
echo " | ";
if (!empty($code_hash))
	echo anchor("welcome/load_code/$code_hash","Cancel");
else echo anchor("welcome/filemanager","Cancel");
echo form_close();

?>