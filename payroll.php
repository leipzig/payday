<?PHP

class biData
{
	//R01 starts on teh first saturday of june
	//a time period id is in the format YYYY2RWW 
	function findByID($id)
	{
		$this->id=$id;
		$this->week=((substr($id,6,2))*2)-1;//the first week of the period
		//$this->startDate=$this->firstSaturday(substr($id,0,4)-1)+(substr($id,6,2)-1)*1209600; //YYYY2RWW starts on 2*(WW-1) weeks after first saturday of YYYY-1
		$this->startDate=strtotime(strftime('%D',$this->firstSaturday(substr($id,0,4)-1))." + ".(2*(substr($id,6,2)-1))." weeks"); //YYYY2RWW starts on 2*(WW-1) weeks after first saturday of YYYY-1
		//$this->endDate=$this->startDate']+1123200; //13 days later
		$this->endDate=strtotime(strftime('%D',$this->startDate)." + 13 days + 23 hours");
		//$this->deadline=$this->startDate']+1382400; //16 days later
		$this->dueDate=strtotime(strftime('%D',$this->startDate)." + 16 days + 23 hours");
		//$this->payday']=$this->startDate']+2332800; //27 days later
		$this->payday=strtotime(strftime('%D',$this->startDate)." + 27 days + 23 hours");
	}
	function findByDay($anyday)
	{
		if ($anyday>=$this->firstSaturday(date('Y',$anyday)) )
		{
			$year=date('Y',$anyday)+1;//calendar year is before fiscal year
		}
		else
		{
			$year=date('Y',$anyday);//calendar year is  fiscal year
		}
		
		$i=0;
		
		do {
			$i++;
			$id=substr('0'.$i,-2,2); //get 2 digit payroll id suffix
			$this->findByID($year.'2R'.$id); //recursive baby!!
		} 
		while 
		($anyday > $this->endDate); //keep lookin till you're in the zone
		
		//you've got the time period now figure out which half of it you're in
		if ( $anyday >= strtotime(strftime('%D',$this->startDate)." + 7 days") )
			$this->week=$i*2;
		else
			$this->week=$i*2-1;
	}
	function firstSaturday($year)
	{
    	$linkDay = "06/01/$year";
    	$w = date("w", strtotime("$linkDay"));
    	$sow=strtotime("$linkDay")+(6-$w)*86400;
    	return $sow;
	}
}

?>