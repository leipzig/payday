<?PHP

class myRights
{
	function myRights($userid)
	{
		$this->db = new db;
		$this->mywrap=$userid;
	}
	
	function returnInfo()
	{
		$sql="SELECT * FROM users WHERE userid='".$this->mywrap."'";
		$res=$this->db->query($sql);
		if (mysql_num_rows($res)) //if not we are in trouble
		{
			$row=mysql_fetch_assoc($res);
			return $row;
		}
		else return NULL;
	}
	
	function myGroups()
	{
		$sql="SELECT groupName FROM groups WHERE userid='".$this->mywrap."'";
		$res=$this->db->query($sql);
		if (mysql_num_rows($res)) //if not we are in trouble
		{
			while($row=mysql_fetch_assoc($res))
				$groups[]=$row['groupName'];
			return $groups;
		}
		else return NULL;
	}
	
	function validate()
	{
		$sql="SELECT * FROM users WHERE userid='".$this->mywrap."'";
		$res=$this->db->query($sql);
		if (mysql_num_rows($res)) return true;
		else return false;
	}
	
	function showRights($viewuserid)
	{
		$sql="SELECT rights.rights FROM rights, groups
			  WHERE rights.userid = '$this->mywrap' AND
			  groups.userid = '$viewuserid' AND
			  groups.groupName = rights.groupName";
		//if (debug) echo $sql."<br>";
		$res = $this->db->query($sql);
		if (mysql_num_rows($res))
		{
			$row=mysql_fetch_assoc($res);
			return $row['rights'];
		}
		else return NULL;
	}
	
	//return as array
	function showUsers($rights)
	{
		$sql="SELECT users.firstname, users.lastname, users.userid,rights.rights FROM groups, rights, users
		WHERE rights.userid = '$this->mywrap' AND
		groups.groupName = rights.groupName AND
		groups.userid = users.userid
		AND (";
		if ($rights=='R') $sql.="rights.rights='R'";
		else if ($rights=='W') $sql.="rights.rights='W'";
		else $sql.= "1";
		$sql .= ")";
		$sql .= " ORDER BY users.lastname";
		$res=$this->db->query($sql);
		if (mysql_num_rows($res))
		{
			while($row=mysql_fetch_assoc($res))
				$users[$row['userid']]=array('firstname'=>$row['firstname'],'lastname'=>$row['lastname'],'rights'=>$row['rights']);
				
			return $users;
		}
		else return NULL;
	}
	
	function showUsersAsList($userids)
	{
		$userArray=$this->showUsers(NULL); //both read and write
		if ($userArray)
		foreach($userArray as $user=>$userinfo) {?>
		<nobr><input name="userids[]" type="checkbox" <?PHP if (isset($userids)) if (in_array($user,$userids)) echo "selected";?> value="<?PHP echo $user;?>"><?PHP echo $userinfo['firstname']." ".$userinfo['lastname']."(".$userinfo['rights'].")";?></nobr>
		<?PHP }
	}
	
	function showUsersAsSelect($userid)
	{
		$userArray=$this->showUsers(NULL);
		foreach($userArray as $user=>$userinfo) {?>
		<option value="<?PHP echo $user;?>" <?PHP if ($user==$userid) echo "selected";?>><?PHP echo $userinfo['firstname']." ".$userinfo['lastname']."(".$userinfo['rights'].")";?>
		<?PHP } 
	}
}
?>