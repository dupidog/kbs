<?php

require("inc/funcs.php");
require("inc/usermanage.inc.php");
require("inc/user.inc.php");

setStat("操作邮件");

requireLoginok();

if ($_POST['action']=='lock') { //ToDo: need to revise
	setStat("锁定邮件");
} else {
	setStat("删除邮件");
}

show_nav(false);

echo "<br>";

$boxDesc=getMailBoxName($_POST['boxname']);
head_var($userid."的".$boxDesc,"usermailbox.php?boxname=".$_POST['boxname'],0);
main();

show_footer();

function main(){
	global $_POST;
	global $boxDesc;
	if (!isset($_POST['boxname'])) {
		foundErr("您没有指定操作的邮箱!");
	}
	$boxName=$_POST['boxname'];
	if (getMailBoxPathDesc($boxName, $path, $desc) === false) {
		foundErr("您指定了错误的邮箱名称！");
	}
	if (!isset($_POST['nums'])) {
		foundErr("您所指定的信件不存在!");
	}
	$action=$_POST['action'];
	if ($action=='deleteAll') {
		deleteAllMails($boxName, $path, $desc);
	} else if ($action=='delete'){
		if ($_POST['nums'] == "") {
			foundErr("您没有指定信件！");
		}
		$nums=split(',',$_POST['nums']);
		deleteMails($boxName, $path, $desc, $nums);
	} else if ($action=='lock'){
		if ($_POST['nums'] == "") {
			foundErr("您没有指定信件！");
		}
		$nums=split(',',$_POST['nums']);
		lockMails($boxName, $path, $desc, $nums);
	} else {
		foundErr("参数错误！");
	}
}

function deleteMails($boxName, $boxPath, $boxDesc, $nums){
	global $currentuser;
	$dir = bbs_setmailfile($currentuser["userid"],$boxPath);

	$total = filesize( $dir ) / 256 ;
	if( $total <= 0 ){
		foundErr("您所指定的信件不存在。");
	}
	$mailnum=count($nums);

	for ($i=0;$i<$mailnum;$i++) {
		if( $articles=bbs_getmails($dir, intval($nums[$i]), 1) ) {
			if (strtoupper($articles[0]["FLAGS"][0])!='M') {
				$ret = bbs_delmail($boxPath, $articles[0]["FILENAME"]);
			}
		}
	}
	setSucMsg("邮件已成功删除！");
	return html_success_quit('返回'.$boxDesc, 'usermailbox.php?boxname='.$boxName);
}

function deleteAllMails($boxName, $boxPath, $boxDesc) {
	global $currentuser;
	$dir = bbs_setmailfile($currentuser["userid"],$boxPath);
	$mailnum = bbs_getmailnum2($dir);
	if( $mailnum <= 0 ){
		setSucMsg("邮件已成功删除！");
		return true;
	}
	$articles=bbs_getmails($dir, 0, $mailnum);
	for ($i=0;$i<$mailnum;$i++) {
		if (strtoupper($articles[$i]["FLAGS"][0])!='M') {
			bbs_delmail($boxPath,$articles[$i]["FILENAME"]);
		}
	}
	setSucMsg("邮件已成功删除！");
	return html_success_quit('返回'.$boxDesc, 'usermailbox.php?boxname='.$boxName);
}

function lockMails($boxName, $boxPath, $boxDesc, $nums){
	setSucMsg("本功能尚未实现:((");
	return html_success_quit('返回'.$boxDesc, 'usermailbox.php?boxname='.$boxName);
}
?>