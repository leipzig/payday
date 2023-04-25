<?PHP
define ("debug",true);

require_once "db.php";
require_once "payroll.php";
require_once "timeMatrix.php";
require_once "userMgmt.php";


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

function displayTimePeriod ($period,$stage,$timeMatrix)
{
	$period->findByID($period->id); //get started on the right week
	?>
<table cellpadding="1" cellspacing="0">
  <?PHP
	for ($week=1;$week<=2;$week++)
	{
		$class='timeRowVeryDark';
		?>
  <tr class="datesHeaders"><td><?PHP echo "<b>WK<br>".($period->week+$week-1)."</b>";?></td><td class="whiteSpace" width="5"></td>
		<?PHP for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period->startDate)." + $day days");$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period->startDate)." + $day days")) {
		 //for ($day=(($week-1)*7), $date=$period->startDate+$day*86400;$day<=((($week-1)*7)+6);$day++,$date=$period->startDate+$day*86400) {
		?>
	
		
    <td align="center" valign="top" nowrap class="datesHeaders"> 
      <? //echo $day."d after: ".$date."<br>";
	  	echo strftime('%A',$date)."<br>";
		echo strftime('%D',$date);?>
      <br>
      <?PHP if ($stage=='confirm' || $stage=='view' || $stage=='store') {?>
	  
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
      <?PHP } else { ?>
        <input name="<?PHP echo strftime('%D',$date)."SA";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP echo $timeMatrix->showEntry(strftime('%D',$date),0,'S');?>"  onMouseDown="this.style.backgroundColor=''">-<input name="<?PHP echo strftime('%D',$date)."FA";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP echo $timeMatrix->showEntry(strftime('%D',$date),0,'F');?>"  onMouseDown="this.style.backgroundColor=''"><br>
		<input name="<?PHP echo strftime('%D',$date)."SB";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP echo $timeMatrix->showEntry(strftime('%D',$date),1,'S');?>"  onMouseDown="this.style.backgroundColor=''">-<input name="<?PHP echo strftime('%D',$date)."FB";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP echo $timeMatrix->showEntry(strftime('%D',$date),1,'F');?>"  onMouseDown="this.style.backgroundColor=''"><br>
		<input name="<?PHP echo strftime('%D',$date)."SC";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP echo $timeMatrix->showEntry(strftime('%D',$date),2,'S');?>"  onMouseDown="this.style.backgroundColor=''">-<input name="<?PHP echo strftime('%D',$date)."FC";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP echo $timeMatrix->showEntry(strftime('%D',$date),2,'F');?>"  onMouseDown="this.style.backgroundColor=''"><br>
		<img src="images/reset.gif" onMouseDown="Javascript:resetDate('<?PHP echo strftime('%D',$date);?>');">
		</td>
	 <?PHP } ?>
		<td class="whiteSpace" width="5"></td>
		<?PHP }//for ($day=(($week-1)*7), $dat ?>
		<td><!--weekly total column--></td>
		</tr>
		
		<?PHP if ($stage=='confirm' || $stage=='view'  || $stage=='store') {?>
		<tr><td></td><td class="whiteSpace" width="5"></td><?PHP 
		for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period->startDate)." + $day days");$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period->startDate)." + $day days")) {
		 //for ($day=(($week-1)*7), $date=$period->startDate+$day*86400;$day<=((($week-1)*7)+6);$day++,$date=$period->startDate+$day*86400) {
		?>
    <td align="center" class="hourBox"> 
                 <?PHP echo number_format($timeMatrix->showDailyTotal(strftime('%D',$date)),2);?>
				 <input name="<?PHP echo strftime('%D',$date)."SA";?>" type="hidden" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."SA"];?>" ><input name="<?PHP echo strftime('%D',$date)."FA";?>" type="hidden" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."FA"];?>">
		<input name="<?PHP echo strftime('%D',$date)."SB";?>" type="hidden" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."SB"];?>"><input name="<?PHP echo strftime('%D',$date)."FB";?>" type="hidden" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."FB"];?>">
		<input name="<?PHP echo strftime('%D',$date)."SC";?>" type="hidden" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."SC"];?>"><input name="<?PHP echo strftime('%D',$date)."FC";?>" type="hidden" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."FC"];?>">
				
    </td>
    <td class="whiteSpace" width="5"></td><?PHP } ?>
		
    <td class="totalHourBox"> 
      <!--weekly total column-->
      <?PHP echo number_format($timeMatrix->showWeeklyTotal($period->week+$week-1),2);?> 
    </td>
  </tr><?PHP } ?>
		
		<?PHP for ($timeLine=0;$timeLine<57600;$timeLine+=900) {
			if (($timeLine % 3600) == 0) $class='timeRowVeryDark';//60min
			else if (($timeLine % 1800) == 0) $class='timeRowDark';//30min
			else $class='timeRowLight';//15 minute
			if ($stage=='confirm' || $stage=='view' || $stage=='store')
			{
			?>
			<tr class='<?PHP echo $class;?>'>
			<?PHP if (($timeLine % 1800) == 0) {?>
				<td rowspan="2">					
				<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);//daylight savings safe ?>
				</td>
				<td rowspan="2" class="whiteSpace" width="5"></td>
			<?PHP } //if ($class=='timeRowDark')?>
			<?PHP for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period->startDate)." + $day days"),$time=strtotime(strftime('%D',$period->startDate)." + $day days 06:00")+$timeLine;$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period->startDate)." + $day days"),$time=strtotime(strftime('%D',$period->startDate)." + $day days 06:00")+$timeLine) {?>
				
    <td <?PHP if ($timeMatrix->isIn(strftime('%D',$date),strftime('%R',$time))) echo "bgcolor=\"#000066\"";?>></td>					
				<td class="whiteSpace" width="5"></td>
			<?PHP }//for ($day=(( ?>
			<td class="whiteSpace"><!--weekly total column--></td>
			</tr>
			
			
			
			
			<?PHP } else { ?>
			<tr class='<?PHP echo $class;?>' id='<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);?>' name='<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);?>'>
			<?PHP if (($timeLine % 1800) == 0) {?>
				<td rowspan="2" id='<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);?>' name='<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);?>' onMouseDown="columnDown(this,'<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);?>');" onMouseUp="columnUp(this,'<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine+1800);?>');" onMouseOver="columnOver(this);">					
				<?PHP echo strftime('%R',strtotime(strftime('%D',$period->startDate)." 06:00")+$timeLine);//daylight savings safe ?>
				</td>
				<td rowspan="2" class="whiteSpace" width="5"></td>
			<?PHP } //if ($class=='timeRowDark')?>
			<?PHP for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period->startDate)." + $day days"),$time=strtotime(strftime('%D',$period->startDate)." + $day days 06:00")+$timeLine;$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period->startDate)." + $day days"),$time=strtotime(strftime('%D',$period->startDate)." + $day days 06:00")+$timeLine) {?>
				<td onMouseDown="cellDown(this,'<?PHP echo strftime('%R',$time);?>','<?PHP echo strftime('%D',$date);?>');" onMouseUp="cellUp(this,'<?PHP echo strftime('%R',$time+900);?>','<?PHP echo strftime('%D',$date);?>');" onMouseOver="cellOver(this,'<?PHP echo strftime('%D',$date);?>');" title="<?PHP echo strftime('%D',$date);?>" id="<?PHP echo strftime('%D',$date);?><?PHP echo strftime('%R',$time);?>" <?PHP if ($stage=="edit" && $timeMatrix->isIn(strftime('%D',$date),strftime('%R',$time))) echo "bgcolor=\"#000066\"";?>></td>					
				<td class="whiteSpace" width="5"></td>
			<?PHP }//for ($day=(( ?><td class="whiteSpace"><!--daily total column--></td>
			</tr>
		<?PHP 
		}//else clickable boxes
		} //for ($timeLine=0;$timeLine<=57600;$timeLine+=900)
	   } //for ($week=1;$week<=2;$week++) ?>
	    <?PHP if ($stage=='confirm' || $stage=='view'  || $stage=='store') {?>
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

$timeMatrix=new timeMatrix;
$myRights=new myRights($mywrap);

if (isset($_POST['userid']) || isset($_GET['userid']))
{
	if (isset($_POST['userid'])) $wantUserids = $_POST['userid']; else $wantUserids = array($_GET['userid']);
	foreach ($wantUserids as $wantUserid)
	{
		if ($wantUserid==$mywrap)
		{
			$viewUserids[]=$mywrap; //you can always see your own
		}
		else if ($myRights->showRights($wantUserid) == 'R' || $myRights->showRights($wantUserid) == 'W')
			$viewUserids[]=$wantUserid;
	}
	else
	{
		echo "No rights to that user";
	}
}
else
	$viewUserids[]=$mywrap;

if(!isset($viewUserids)) $viewUserids[]=$mywrap; //in case you chose a bunch of illegitimates

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

	foreach($viewUserids[] as $viewUserid)
	{
		if (!isset($_POST['stage'][$viewUserid]))
		{
			if ($timeMatrix[$viewUserid]->recordExists($workPeriod->id,$viewUserid))
			{
				$timeMatrix[$viewUserid]->loadFromDB($workPeriod->id,$viewUserid);
				$stage[$viewUserid]="view";
			}
			else
			{
				$stage[$viewUserid]="new"; //don't show in multiple
			}
		}
		else
			$stage[$viewUserid]=$_POST['stage'][$viewUserid];

		if ($stage[$viewUserid]=='confirm')
		{
			if (debug) echo "attempting to laod from post<br>";
			$timeMatrix[$viewUserid]->loadFromPost($_POST);
		}
	
		if ($stage[$viewUserid]=='store')
		{
			$timeMatrix[$viewUserid]->loadFromPOST($_POST);
			$timeMatrix[$viewUserid]->storeToDB($viewUserid);
		}
	}
if ($stage=='edit')
{
	$timeMatrix->loadFromDB($workPeriod->id,$viewUserid);
}

if(debug) echo var_dump($timeMatrix);
if (debug) echo "mywrap: ".$mywrap."<br>viewing: ".$viewUserid."<br>";
echo "stage: ".$stage;
include "index_template.php";
?>