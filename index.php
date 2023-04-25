<?PHP
define ("debug",false);

require_once "db.php";
require_once "payroll.php";
require_once "timeMatrix.php";
require_once "userMgmt.php";
require_once "randomColor.php";

function displayPeriodInfo($period)
{
	?>
<link href="styles.css" rel="stylesheet" type="text/css">

	<table border="1" cellspacing="0" cellpadding="2" class="datesHeaders">
  <tr>
    <td>Payroll ID</td>
    <td>Work Period Beginning</td>
    <td>Work Period Ending</td>
    <td>Time Sheet Due Date</td>
    <td>Payday</td>
  </tr>
  <tr>
    <td><?PHP echo $period->id; //echo $period['id'];?></td>
    <td><?PHP echo strftime('%D',$period->startDate);?></td>
    <td><?PHP echo strftime('%D',$period->endDate);?></td>
    <td><?PHP echo strftime('%D',$period->dueDate);?></td>
    <td><?PHP echo strftime('%D',$period->payday);?></td>
  </tr>
</table>
<?PHP
}

function displayTimePeriod ($period,$stage,$timeMatrix,$defaultAcctNum,$showAcctNum)
{
	$period->findByID($period->id); //get started on the right week
	if (debug) echo var_dump($timeMatrix);
	?>
<table cellpadding="1" cellspacing="0">
  <?PHP
	for ($week=1;$week<=2;$week++)
	{
		$class='timeRowVeryDark';
		?>
  <tr class="datesHeaders"><td><?PHP echo "<b>WK<br>".($period->week+$week-1)."</b>";?></td><td class="whiteSpace" width="5"></td>
		<?PHP for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period->startDate)." + $day days");$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period->startDate)." + $day days")) {
		?>
	
		
					<td align="center" valign="top" nowrap class="datesHeaders"
					<?PHP if ($stage == 'multiple') {
					//calculate colspan
					//$colspan=0;
					//foreach($timeMatrix as $userkey => $userMatrix) 
					//	if ($userMatrix->entryExists(strftime('%D',$date),0)) $colspan++;
					//if ($colspan>1)
					$colspan=sizeof($timeMatrix);
					echo "colspan=\"$colspan\"";
					}?>
					> 
					  <? //echo $day."d after: ".$date."<br>";
						echo strftime('%A',$date)."<br>";
						echo strftime('%D',$date);
						if (debug) echo "<br>".strftime('%R',$date);
						?>
					  <br>
					  <?PHP if ($stage != 'multiple') {
					  
							  if ($stage=='confirm' || $stage=='view' || $stage=='lock' || $stage=='unlock') { //fixed boxes
							  ?>
							  <table width="100%" border="0" cellpadding="2" cellspacing="2">
								<?PHP if ($timeMatrix->entryExists(strftime('%D',$date),0)) {?>
								<tr> 
								  <td align="right" class="hourBox"><?PHP echo $timeMatrix->showEntry(strftime('%D',$date),0,'S');?></td>
								  <td align="center">-</td>
								  <td align="left" class="hourBox"><?PHP echo $timeMatrix->showEntry(strftime('%D',$date),0,'F');?></td>
								</tr>
								<? }
								if ($timeMatrix->entryExists(strftime('%D',$date),1)) {?>
								<tr> 
								  <td align="right" class="hourBox"><?PHP echo $timeMatrix->showEntry(strftime('%D',$date),1,'S');?></td>
								  <td align="center">-</td>
								  <td align="left" class="hourBox"><?PHP echo $timeMatrix->showEntry(strftime('%D',$date),1,'F');?></td>
								</tr>
								<? }
								if ($timeMatrix->entryExists(strftime('%D',$date),2)) {?>
								<tr> 
								  <td align="right" class="hourBox"><?PHP echo $timeMatrix->showEntry(strftime('%D',$date),2,'S');?></td>
								  <td align="center">-</td>
								  <td align="left" class="hourBox"><?PHP echo $timeMatrix->showEntry(strftime('%D',$date),2,'F');?></td>
								</tr>
								<?PHP } ?>
							  </table> 
							  <?PHP } 
							  
							  else 
							  
							  { // inputs ?>
							  <input name="<?PHP echo strftime('%D',$date)."SA";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP echo $timeMatrix->showEntry(strftime('%D',$date),0,'S');?>"  onMouseDown="this.style.backgroundColor=''">-<input name="<?PHP echo strftime('%D',$date)."FA";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP echo $timeMatrix->showEntry(strftime('%D',$date),0,'F');?>"  onMouseDown="this.style.backgroundColor=''"><br>
								<input name="<?PHP echo strftime('%D',$date)."SB";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP echo $timeMatrix->showEntry(strftime('%D',$date),1,'S');?>"  onMouseDown="this.style.backgroundColor=''">-<input name="<?PHP echo strftime('%D',$date)."FB";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP echo $timeMatrix->showEntry(strftime('%D',$date),1,'F');?>"  onMouseDown="this.style.backgroundColor=''"><br>
								<input name="<?PHP echo strftime('%D',$date)."SC";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP echo $timeMatrix->showEntry(strftime('%D',$date),2,'S');?>"  onMouseDown="this.style.backgroundColor=''">-<input name="<?PHP echo strftime('%D',$date)."FC";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP echo $timeMatrix->showEntry(strftime('%D',$date),2,'F');?>"  onMouseDown="this.style.backgroundColor=''"><br>
								<img src="images/reset.gif" onMouseDown="Javascript:resetDate('<?PHP echo strftime('%D',$date);?>');">
								<?PHP } 
						
						}//not multiple
						
						?>
						
						
						</td>
						<td class="whiteSpace" width="5"></td>
		<?PHP }//for ($day=(($week-1)*7), $dat ?>
		
		
		<td><!--weekly total column--> <?PHP if ($stage=='new' || $stage=='edit') {
		if ($showAcctNum) {?>
     			 Acct#<br><input name="acct<?PHP echo $week;?>" type="text" value="<?PHP if ($timeMatrix->showAcctNum($week)) echo $timeMatrix->showAcctNum($week); else echo $defaultAcctNum; ?>" size="4">
	  <?PHP }//if show acct num
	  else { ?>
	 		 <input name="acct<?PHP echo $week;?>" type="hidden" value="<?PHP if ($timeMatrix->showAcctNum($week)) echo $timeMatrix->showAcctNum($week); else echo $defaultAcctNum; ?>" size="4">
	  <?PHP
	  }//else
	  }// new or edit
	  
	  else if ($stage != 'multiple')
	  {
	  	if ($showAcctNum)
	  		echo "Acct#<br>".$timeMatrix->showAcctNum($week);
	  }
	  ?></td>
		</tr>
		
		<?PHP if ($stage=='confirm' || $stage=='view'  || $stage=='lock' || stage=='unlock') { //totals ?>
					<tr><td></td><td class="whiteSpace" width="5"></td><?PHP 
					for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period->startDate)." + $day days");$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period->startDate)." + $day days")) {
					?>
   							 <td align="center" class="hourBox"> 
              				 <?PHP echo number_format($timeMatrix->showDailyTotal(strftime('%D',$date)),2);?>
							 <input name="<?PHP echo strftime('%D',$date)."SA";?>" type="hidden" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."SA"];?>" ><input name="<?PHP echo strftime('%D',$date)."FA";?>" type="hidden" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."FA"];?>">
							 <input name="<?PHP echo strftime('%D',$date)."SB";?>" type="hidden" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."SB"];?>"><input name="<?PHP echo strftime('%D',$date)."FB";?>" type="hidden" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."FB"];?>">
							 <input name="<?PHP echo strftime('%D',$date)."SC";?>" type="hidden" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."SC"];?>"><input name="<?PHP echo strftime('%D',$date)."FC";?>" type="hidden" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."FC"];?>">
     						 </td>
    						 <td class="whiteSpace" width="5"></td>
					<?PHP } ?>
		
					<td class="totalHourBox"> 
					<!--weekly total column-->
					<?PHP echo number_format($timeMatrix->showWeeklyTotal($period->week+$week-1),2);  ?> 
					</td>
					</tr>
		<?PHP } 
   ?>
		
		<?PHP for ($timeLine=0;$timeLine<57600;$timeLine+=900) {
			if (($timeLine % 3600) == 0) $class='timeRowVeryDark';//60min
			else if (($timeLine % 1800) == 0) $class='timeRowDark';//30min
			else $class='timeRowLight';//15 minute
			
			
			if ($stage=='multiple')
			{ ?>
							<tr class='<?PHP echo $class;?>'>
							<?PHP if (($timeLine % 1800) == 0)
							{?>
								
    <td height="10" rowspan="2"> <?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);//daylight savings safe ?> 
    </td>
								
    <td width="5" height="10" rowspan="2" class="whiteSpace"></td>
							<?PHP } //if ($class=='timeRowDark')?>
							<?PHP for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period->startDate)." + $day days"),$time=strtotime(strftime('%D',$period->startDate)." + $day days 06:00")+$timeLine;$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period->startDate)." + $day days"),$time=strtotime(strftime('%D',$period->startDate)." + $day days 06:00")+$timeLine) {
								 	 foreach($timeMatrix as $userkey => $userMatrix) {?>
											
    <td height="10" <?PHP if ($userMatrix->isIn(strftime('%D',$date),strftime('%R',$time))) { echo "bgcolor=\"".$userMatrix->myColor."\"";?>><?PHP echo "<span class=\"tiny\">$userkey</span>"; } else echo ">"; ?></td>					
											
								<?PHP }//for each user ?>
										
    <td width="5" height="10" class="whiteSpace"></td>
								<?PHP }//for ($day=(( ?>
								
								
    <td height="10" class="whiteSpace">
      <!--weekly total column-->
    </td>
								</tr>
							<?PHP 
			}
			
			
			else if ($stage=='confirm' || $stage=='view' || $stage=='lock' || $stage=='unlock')
			{ //non-clickable boxes
							?>
							<tr class='<?PHP echo $class;?>'>
							<?PHP if (($timeLine % 1800) == 0) {?>
								<td rowspan="2">					
								<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);//daylight savings safe ?>
								</td>
								<td rowspan="2" class="whiteSpace" width="5"></td>
							<?PHP } //if ($class=='timeRowDark')?>
							<?PHP for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period->startDate)." + $day days"),$time=strtotime(strftime('%D',$period->startDate)." + $day days 06:00")+$timeLine;$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period->startDate)." + $day days"),$time=strtotime(strftime('%D',$period->startDate)." + $day days 06:00")+$timeLine) {?>
								
							<td <?PHP if ($timeMatrix->isIn(strftime('%D',$date),strftime('%R',$time))) echo "class=\"filledHour\"";?>></td>					
								<td class="whiteSpace" width="5"></td>
							<?PHP }//for ($day=(( ?>
							<td class="whiteSpace"><!--weekly total column--></td>
							</tr>
			
			<?PHP } else { //clickable boxes ?>
			
							<tr class='<?PHP echo $class;?>' id='<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);?>' name='<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);?>'>
							<?PHP if (($timeLine % 1800) == 0) {?>
								<td rowspan="2" id='<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);?>' name='<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);?>' onMouseDown="columnDown(this,'<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);?>');" onMouseUp="columnUp(this,'<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine+1800);?>');" onMouseOver="columnOver(this);">					
								<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);//daylight savings safe ?>
								</td>
								<td rowspan="2" class="whiteSpace" width="5"></td>
							<?PHP } //if ($class=='timeRowDark')?>
							<?PHP for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period->startDate)." + $day days"),$time=strtotime(strftime('%D',$period->startDate)." + $day days 06:00")+$timeLine;$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period->startDate)." + $day days"),$time=strtotime(strftime('%D',$period->startDate)." + $day days 06:00")+$timeLine) {?>
								<td <?PHP if ($timeMatrix->isIn(strftime('%D',$date),strftime('%R',$time))) echo "class=\"filledHour\"";?> onMouseDown="cellDown(this,'<?PHP echo strftime('%R',$time);?>','<?PHP echo strftime('%D',$date);?>');" onMouseUp="cellUp(this,'<?PHP echo strftime('%R',$time+900);?>','<?PHP echo strftime('%D',$date);?>');" onMouseOver="cellOver(this,'<?PHP echo strftime('%D',$date);?>');" title="<?PHP echo strftime('%D',$date);?>" name="<?PHP echo strftime('%D',$date);?>" id="<?PHP echo strftime('%D',$date);?>"></td>					
								<td class="whiteSpace" width="5"></td>
							<?PHP }//for ($day=(( ?><td class="whiteSpace"><!--daily total column--></td>
							</tr>
		<?PHP 
		}//else clickable boxes
		} //for ($timeLine=0;$timeLine<=57600;$timeLine+=900)
	   } //for ($week=1;$week<=2;$week++) ?>
	    <?PHP if ($stage=='confirm' || $stage=='view'  || $stage=='lock') {?>
	   <tr>
    <td colspan="15" align="right" class="datesHeaders">Total hours for this work 
      period: </td>
    <td></td>
    <td class="totalHourBox"><?PHP echo number_format($timeMatrix->showPeriodTotal(),2);?></td>
    </td>
  </tr><?PHP } ?>
	</table>


<?PHP } //end function

$mywrap=$_SERVER['WRAP_USERID'];
if (debug) echo $mywrap."<br>";
$myRights=new myRights($mywrap);
if ($myRights->validate()==false)
{
	echo "You are not authorized to view this page";
	exit;
}

$timeMatrix=new timeMatrix;

//get info about me
$myInfo=$myRights->returnInfo();
$myGroups=$myRights->myGroups();

if (isset($_POST['userid']) || isset($_GET['userid']))
{
	if (isset($_POST['userid'])) $wantUserid = $_POST['userid']; else $wantUserid = $_GET['userid'];
	if ($wantUserid==$mywrap)
	{
		$viewUserid=$mywrap; //you can always see your own
	}
	else if ($myRights->showRights($mywrap,$wantUserid) == 'R' || $myRights->showRights($mywrap,$wantUserid) == 'W')
	{
		$viewUserid=$wantUserid;

	}
	else
	{
		echo "No rights to that user";
		$viewUserid=$mywrap;
	}
}
else
	$viewUserid=$mywrap; //for now
	
$viewRights=new myRights($viewUserid);
$viewInfo=$viewRights->returnInfo();
$viewGroups=$viewRights->myGroups();
		
if (isset($_POST['id'])) $id=$_POST['id'];
else if (isset($_GET['id'])) $id=$_GET['id'];



if (!isset($id))
{
	$workPeriod = new biData;
	$workPeriod->findByDay(mktime());
	$id = $workPeriod->id;
}
else
{
	$workPeriod=new biData;
	$workPeriod->findByID($id);
}


if (debug) echo $id."<br>";

//have I submitted a time sheet
if (!isset($_POST['stage']))
{
	if ($timeMatrix->recordExists($workPeriod->id,$viewUserid))
	{
		$timeMatrix->loadFromDB($workPeriod->id,$viewUserid);
		$stage="view";
	}
	else
	{
		$stage="new";
	}
}
else
	$stage=$_POST['stage'];
	
if ($stage=='multiple')
{
	if (isset($_POST['userids']) && sizeof($_POST['userids']>0))
	{
		foreach ($_POST['userids'] as $value)
		{
			$viewUserids[]=$value;
			$timeMatrices[$value]=new timeMatrix;
			$timeMatrices[$value]->loadFromDB($workPeriod->id,$value);
		}
	}
	else
	{
		$viewUserids[]=$mywrap;//just see yoself
		$timeMatrices[$mywrap]=new timeMatrix;
		$timeMatrices[$mywrap]->loadFromDB($workPeriod->id,$mywrap);
	}
}

if ($stage=='print')
{
	$timeMatrix->loadFromDB($workPeriod->id,$viewUserid);
	include "pdfTimesheet.php";
}
else
{
	if ($stage=='confirm')
	{
		if (debug) echo "attempting to load from post<br>";
		$timeMatrix->loadFromPost($_POST);
		$timeMatrix->storeToDB($viewUserid);//let's store the tentative schedule
	}
		
	if ($stage=='lock')
	{
		//$timeMatrix->loadFromPOST($_POST);
		//$timeMatrix->storeToDB($viewUserid);
		$timeMatrix->loadFromDB($workPeriod->id,$viewUserid);
		$timeMatrix->lock($workPeriod->id,$viewUserid);
	}
	if ($stage=='edit' || $stage=='unlock')
	{
		$timeMatrix->unlock($workPeriod->id,$viewUserid);
		$timeMatrix->loadFromDB($workPeriod->id,$viewUserid);
	}
	
	if (debug) {echo "<b>POST_[userids]:</b>";
	echo var_dump($_POST['userids']);
	echo "<b>timeMatrix:</b> ";
	echo var_dump($timeMatrix);
	echo "<b>timeMatrices:</b> ";
	echo var_dump($timeMatrices);
	echo "<b>mywrap:</b> ".$mywrap."<br><b>userid</b>: ".$viewUserid."<br><b>userids</b>: ";
	echo var_dump($viewUserids)."<br>";
	echo "<b>stage:</b> ".$stage;
	}
	include "index_template.php";
}
?>