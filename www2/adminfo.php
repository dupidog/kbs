<?php
	require("www2-admin.php");

    if(!($currentuser["userlevel"] & BBS_PERM_ACCOUNTS))
        admin_deny();

    if(isset($_GET["userid"])) {
        $userid = $_GET["userid"];
        $username = $_POST["username"];
        $realname = $_POST["realname"];
        $address = $_POST["address"];
        $email = $_POST["email"];
        if($_POST["gender"] == "M")
            $gender = 77;
        else
            $gender = 70;
        $birthyear = $_POST["birthyear"];
        $birthmonth = $_POST["birthmonth"];
        $birthday = $_POST["birthday"];
        $title = $_POST["title"];
        $realemail = $_POST["realemail"];
        $numlogins = $_POST["numlogins"];
        $numposts = $_POST["numposts"];
        if(@$_POST["firstlogin"] == "yes")
            $firstlogin = 1;
        else
            $firstlogin = 0;
        if(@$_POST["lastlogin"] == "yes")
            $lastlogin = 1;
        else
            $lastlogin = 0;
        $ret = bbs_admin_setuserinfo($userid, $username, $realname, $address, $email, $gender, $birthyear, $birthmonth, $birthday, $title, $realemail, $numlogins, $numposts, $firstlogin, $lastlogin);
        $msg[0] = "�����޸ĳɹ���";
        $msg[1] = $msg[2] = $msg[3] = "���ղ���ȷ��";
        $msg[4] = "�����ڵ��û�ְ��";
    }

    admin_header("�ı�������", "�޸�ʹ��������");

    if(!isset($userid)) {
        if(isset($_POST["userid"]))
            $userid = $_POST["userid"];
        else
            $userid = $currentuser["userid"];
    }
    
?>
<form method="post" action="adminfo.php" class="medium">
<fieldset><legend>Ҫ�޸ĵ��û�ID</legend><div class="inputs">
<label>ID:</label><input type="text" name="userid" value="<?php print($userid); ?>" size="12" maxlength="12">
<input type="submit" value="ȷ��">
</div></fieldset></form>
<?php
    print($msg[-$ret]);
    $userinfo = array();
    $uid = bbs_admin_getuserinfo($userid, $userinfo);
    if($uid == -1)
        html_error_quit("�޷���ʼ�����顣");
    if($uid > 0) {
?>
<form method="post" action="adminfo.php?userid=<?php echo $userid; ?>" class="medium">
<fieldset><legend>��������</legend><div class="inputs">
<label>�ʺ�:</label><?php echo $userinfo["userid"];?><br/>
<label>�ǳ�:</label><input type="text" name="username" value="<?php echo htmlspecialchars($userinfo["username"],ENT_QUOTES);?>" size="24" maxlength="39"><br/>
<label>��ʵ����:</label><input type="text" name="realname" value="<?php echo $userinfo["realname"];?>" size="16" maxlength="39"><br/>
<label>��ס��ַ:</label><input type="text" name="address" value="<?php echo $userinfo["address"];?>" size="40" maxlength="79"><br/>
<label>��������:</label><input type="text" name="email" value="<?php echo $userinfo["email"];?>" size="40" maxlength="79"><br/>
<label>�Ա�:</label><input type="radio" name="gender" value='M'<?php echo ($userinfo["gender"]==77)?" checked":""; ?>>�� <input type="radio" name="gender" value="F"<?php echo ($userinfo["gender"]==77)?"":" checked"; ?>>Ů<br />
<label>����:</label><input type="text" name="birthyear" value="<?php echo $userinfo["birthyear"]+1900; ?>" size="4" maxlength="4"> �� <input type="text" name="birthmonth" value="<?php echo $userinfo["birthmonth"]; ?>" size="2" maxlength="2"> �� <input type="text" name="birthday" value="<?php echo $userinfo["birthday"]; ?>" size="2" maxlength="2"> ��<br/>
<label>��ǰְ��:</label><input type="text" name="title" value="<?php echo $userinfo["title"];?>" size="15" maxlength="254"><br/>
<label>��ʵEmail:</label><input type="text" name="realemail" value="<?php echo $userinfo["realemail"];?>" size="40" maxlength="79"><br/>
<label>��վ����:</label><input type="text" name="numlogins" value="<?php echo $userinfo["numlogins"];?>" size="6" maxlength="7"><br/>
<label>��������:</label><input type="text" name="numposts" value="<?php echo $userinfo["numposts"];?>" size="6" maxlength="7"><br/>
<label>ע��ʱ��:</label><?php echo date("D M j H:i:s Y",$userinfo["firstlogin"]);?> <input type="checkbox" name="firstlogin" value="yes">��ǰ1����<br/>
<label>�������:</label><?php echo date("D M j H:i:s Y",$userinfo["lastlogin"]);?> <input type="checkbox" name="lastlogin" value="yes">��Ϊ����<br/>
</div></fieldset>
<div class="oper">
<input type="submit" name="submit" value="ȷ��" /> <input type="reset" value="��ԭ" />
</div>
</form>
<?php
    }
	page_footer();
?>