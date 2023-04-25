<?PHP

class timeMatrix
{
	function timeMatrix()
	{
		$this->matrix = NULL;
		$this->period = new biData;
		$this->db = new db;
		$this->myColor=random_color();
		//what about the id??
	}
	
	//R01 starts on teh first saturday of june
	function loadFromPost($post)
	{
		if (debug) echo "loading from POST<br>";
		$this->period->findByID($post['id']);
		
		foreach($post as $key=>$value)
		{
			if (preg_match('/[0-9][0-9]\/[0-9][0-9]\/[0-9][0-9]F/',$key) && $value)
			{
				$this->matrix[substr($key,0,8)][]=array('S'=>$startvalue,'F'=>$value,'T'=>$this->timeDifference($startvalue,$value));
			}
			else if (preg_match('/acct1/',$key))
			{
				$this->acct1=$value;
			}
			else if (preg_match('/acct2/',$key))
			{
				$this->acct2=$value;
			}
			else $startvalue=$value; //save this for when we get the finish
		}
		
		if ($this->matrix) {
			foreach ($this->matrix as $date=>$entries)
			{
				array_multisort($this->matrix[$date]);
				foreach ($entries as $entry)
					$this->matrix[$date]['dailyTotal'] += $entry['T'];
				$this->matrix['periodTotal']+=$this->matrix[$date]['dailyTotal'];
				$this->period->findByDay(strtotime($date));
				$this->matrix['weeklyTotal'][$this->period->week]+=$this->matrix[$date]['dailyTotal'];
				if (debug) echo "adding to week".$this->period->week."=".$this->matrix['weeklyTotal'][$this->period->week];
			}
		}
	}
	function storeToDB($userid)
	{
		//clear out existing entries
		$sql="DELETE FROM empHours WHERE period='".$this->period->id."' AND userid='".$userid."'";
		if ($this->db->query($sql)) { if (debug) echo "Successful delete ".$sql."<br>"; }
		
		if ($this->matrix) {
			foreach ($this->matrix as $date=>$entries)
			{
				if ($date != 'periodTotal' && $date != 'weeklyTotal')
				foreach ($entries as $entryNum=>$entry)
				{
					if ($entryNum !== 'dailyTotal') //identity
					{
						$sql= "INSERT INTO empHours (userid,period,date,start,finish,total) VALUES (";
						$sql.="'".$userid."','".$this->period->id."','".$date."','".$entry['S']."','".$entry['F']."','".$entry['T']."')";
						if ($this->db->query($sql))
						{
							if (debug) echo "Successful daily insert<br>";
						}
						else echo "Daily insert failed<br>";
					}
				}
			}
		}
		
		$sql="DELETE FROM periodHours WHERE period='".$this->period->id."' AND userid='".$userid."'";
		if ($this->db->query($sql)) { if (debug) echo "Successful delete ".$sql."<br>"; }
		
		$weeknum=0;
		if ($this->matrix['weeklyTotal']) {
			foreach ($this->matrix['weeklyTotal'] as $aWeeklyTotal)
			{
				$weeklyTotal[$weeknum]=$aWeeklyTotal;
				$weeknum++;
			}
		}
		
		$sql = "INSERT INTO periodHours (userid,period,total,wk1acct,wk2acct,wk1total,wk2total) VALUES (";
		$sql .= "'".$userid."','".$this->period->id."','".$this->matrix['periodTotal']."','".$this->acct1."','".$this->acct2."','".$weeklyTotal[0]."','".$weeklyTotal[1]."')";
		if ($this->db->query($sql)) echo "Successful period insert<br>";
		else echo "Period insert failed<br>";
	}
	function loadFromDB($id,$userid)
	{
		if (debug) echo "loading from db<br>";
		$this->matrix = NULL;
		$this->period = new biData;
		
		$sql="SELECT wk1acct, wk2acct FROM periodHours WHERE period='".$id."' AND userid='".$userid."'";
		if (debug) echo $sql."<br>";
		$perres=$this->db->query($sql);
		if (mysql_num_rows($perres) > 0)
		{
			$perrow=mysql_fetch_assoc($perres);
			$this->acct1=$perrow['wk1acct'];
			$this->acct2=$perrow['wk2acct'];
		}
		else
			if (debug) echo "no rows<br>";
			
		$sql="SELECT * FROM empHours WHERE period='".$id."' AND userid='".$userid."' ORDER BY date,start";
		if (debug) echo $sql."<br>";
		$empres=$this->db->query($sql);
		if (mysql_num_rows($empres) > 0)
		{
			while($emprow=mysql_fetch_assoc($empres))
			{
				$this->matrix[$emprow['date']][]=array('S'=>$emprow['start'],'F'=>$emprow['finish'],'T'=>$emprow['total']);
				$this->matrix[$emprow['date']]['dailyTotal'] += $emprow['total'];
				$this->matrix['periodTotal']+= $emprow['total'];
				$this->period->findByDay(strtotime($emprow['date']));
				$this->matrix['weeklyTotal'][$this->period->week]+=$emprow['total'];
			}
		}
		else
			if (debug) echo "no rows<br>";
	}
	
	function showAcctNum($week)
	{
		//$week is 1 or 2
			if ($week==1) return $this->acct1;
			else if ($week==2) return $this->acct2;
			else return NULL;
	}
	
	function lock($id,$userid)
	{
		$sql="UPDATE periodHours SET locked='Y' WHERE period='".$id."' AND userid='".$userid."'";
		if (debug) echo $sql."<br>";
		$lockres=$this->db->query($sql);
	}
	
	function unlock($id,$userid)
	{
		$sql="UPDATE periodHours SET locked='N' WHERE period='".$id."' AND userid='".$userid."'";
		if (debug) echo $sql."<br>";
		$lockres=$this->db->query($sql);
	}
	
	function isLocked($id,$userid)
	{
		$sql="SELECT locked FROM periodHours WHERE period='".$id."' AND userid='".$userid."'";
		$islockres=$this->db->query($sql);
		if (debug) echo $sql."<br>";
		if (mysql_num_rows($islockres))
		{	$lockrow=mysql_fetch_assoc($islockres);
			if ($lockrow['locked']=="Y") return true;
			else return false;
		}
		return false;
	}
	
	
	function showEntry($date,$index,$end)
	{
		if (isset($this->matrix[$date][$index][$end])) return $this->matrix[$date][$index][$end];
		else return NULL;
	}
	function showEntry12Hour($date,$index,$end)
	{
		if (isset($this->matrix[$date][$index][$end])) 
		{
			if (substr($this->matrix[$date][$index][$end],0,2) > 12) return ((substr($this->matrix[$date][$index][$end],0,2)-12).(substr($this->matrix[$date][$index][$end],2,3)));
			else if (substr($this->matrix[$date][$index][$end],0,2) < 10) return ((substr($this->matrix[$date][$index][$end],1,1)).(substr($this->matrix[$date][$index][$end],2,3)));
			else return $this->matrix[$date][$index][$end];
		}
		else return NULL;
	}
	
	function entryExists($date,$index)
	{
		if (isset($this->matrix[$date][$index])) return true; else return false;
	}
	function showDailyTotal($date)
	{
		if (isset($this->matrix[$date]['dailyTotal'])) return $this->matrix[$date]['dailyTotal'];
		else return 0;
	}
	function showWeeklyTotal($week)
	{
		//week # 1-52
		if (isset($this->matrix['weeklyTotal'][$week])) return $this->matrix['weeklyTotal'][$week];
		else return 0;
	}
		function showPeriodTotal()
	{
		//week # 1-52
		if (isset($this->matrix['periodTotal'])) return $this->matrix['periodTotal'];
		else return 0;
	}
	function isIn($date,$time)
	{
		if (isset($this->matrix[$date]))
		{
			$myday=$this->matrix[$date];
			//echo strtotime($time)."<br>";
			foreach($myday as $entryNum=>$entry)
			{
				//echo var_dump($myday[$entryNum]);
				//echo $entryNum."=>".$entry;
				if ($entryNum === "dailyTotal") //identity because it can be 0 and then 0==dailyTotal is true
				{
				}
				else
				{
					//if (strtotime($entry['S'])<=strtotime($time) && strtotime($entry['F'])>=strtotime($time))
					//	return true;
					if ($entry['S']<=$time && $entry['F']>$time) //do not include finish time
						return true;
				}
			}
		}
		return false;
	}
	
	function recordExists($id,$userid)
	{
		$sql="SELECT * FROM periodHours WHERE userid='".$userid."' AND period='".$id."'";
		if (mysql_num_rows($this->db->query($sql)) > 0) return true;
		else return false;
	}
	function timeDifference($start,$end)
	{
		// get the time bits:
		$prstBits=explode(":", $start);
		$prendBits=explode(":", $end);
		
		// create unix timestamps for each - day, month, year all set to 1
		// since we're calculating a relative time only:
		$prst_t=mktime($prstBits[0], $prstBits[1], $prstBits[2], 1, 1, 1);
		$prend_t=mktime($prendBits[0], $prendBits[1], $prendBits[2], 1, 1, 1);
		$diff_seconds=$prend_t-$prst_t;
		$diff_hours=$diff_seconds/3600;
		return $diff_hours;
	}
}
?>