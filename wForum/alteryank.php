<?php
require("inc/funcs.php");

require("inc/usermanage.inc.php");

require("inc/user.inc.php");

setStat("切换订阅状态");

show_nav();

if ($loginok==1) {
?>
<table border="0" width="97%">
<?php
	showUserMailbox();
?>
</table>
<?php
}

head_var($userid."的控制面板","usermanagemenu.php",0);

if ($loginok==1) {
	main();
}else {
	foundErr("本页需要您以正式用户身份登陆之后才能访问！");
}

if (isErrFounded()) {
		html_error_quit();
} else {
		showUserManageMenu();
		html_success_quit();
}

show_footer();

function main() {
	global $currentuser;
	$mode=bbs_alter_yank();
?>

<?php

	return setSucMsg("状态已切换".$mode);
}

?>
