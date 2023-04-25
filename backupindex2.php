<?PHP
require_once "payroll.php";
function displayPeriodInfo($period)
{
	?>
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

function displayTimePeriod ($period)
{
	?>
	
<table cellpadding="1" cellspacing="0">
  <?PHP
	for ($week=1;$week<=2;$week++)
	{
		$class='timeRowVeryDark';
		?>
  <tr class="dateLine"><td></td><td class="whiteSpace" width="5"></td>
		<?PHP for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period->startDate)." + $day days");$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period->startDate)." + $day days")) {
		 //for ($day=(($week-1)*7), $date=$period->startDate+$day*86400;$day<=((($week-1)*7)+6);$day++,$date=$period->startDate+$day*86400) {
		?>
	
		
    <td align="center" nowrap class="datesHeaders"> 
      <? //echo $day."d after: ".$date."<br>";
		echo strftime('%D',$date);?>
      <br>
	  <?PHP if ($stage=='confirm' || $stage=='view') {?>
	  <table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><?PHP echo $_POST[strftime('%D',$date)."SA"];?></td>
    <td>-</td>
    <td><?PHP echo $_POST[strftime('%D',$date)."FA"];?></td>
  </tr>
  <tr>
    <td><?PHP echo $_POST[strftime('%D',$date)."SB"];?></td>
    <td>-</td>
    <td><?PHP echo $_POST[strftime('%D',$date)."FB"];?></td>
  </tr>
  <tr>
    <td><?PHP echo $_POST[strftime('%D',$date)."SC"];?></td>
    <td>-</td>
    <td><?PHP echo $_POST[strftime('%D',$date)."FC"];?></td>
  </tr>
</table>

	  <?PHP } else { ?>
		<input name="<?PHP echo strftime('%D',$date)."SA";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."SA"];?>" onMouseDown="this.style.backgroundColor=''">-<input name="<?PHP echo strftime('%D',$date)."FA";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."FA"];?>"  onMouseDown="this.style.backgroundColor=''"><br>
		<input name="<?PHP echo strftime('%D',$date)."SB";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."SB"];?>"  onMouseDown="this.style.backgroundColor=''">-<input name="<?PHP echo strftime('%D',$date)."FB";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."FB"];?>"  onMouseDown="this.style.backgroundColor=''"><br>
		<input name="<?PHP echo strftime('%D',$date)."SC";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."S";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."SC"];?>"  onMouseDown="this.style.backgroundColor=''">-<input name="<?PHP echo strftime('%D',$date)."FC";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."F";?>" value="<?PHP if($stage=='confirm') echo $_POST[strftime('%D',$date)."FC"];?>"  onMouseDown="this.style.backgroundColor=''"><br>
		<img src="images/reset.gif" onMouseDown="Javascript:resetDate('<?PHP echo strftime('%D',$date);?>');"></td>
	 <?PHP } ?>
		<td class="whiteSpace" width="5"></td>
		<?PHP }//for ($day=(($week-1)*7), $dat ?>
		
		</tr>
		<?PHP for ($timeLine=0;$timeLine<57600;$timeLine+=900) {
			if (($timeLine % 3600) == 0) $class='timeRowVeryDark';//60min
			else if (($timeLine % 1800) == 0) $class='timeRowDark';//30min
			else $class='timeRowLight';//15 minute
			if ($stage='confirm' || $stage='view')
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
				
    <td <?PHP if ($timeMatrix->isIn($time)) echo "bgcolor=\"#000000\"";?>></td>					
				<td class="whiteSpace" width="5"></td>
			<?PHP }//for ($day=(( ?>
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
				<td onMouseDown="cellDown(this,'<?PHP echo strftime('%R',$time);?>','<?PHP echo strftime('%D',$date);?>');" onMouseUp="cellUp(this,'<?PHP echo strftime('%R',$time+900);?>','<?PHP echo strftime('%D',$date);?>');" onMouseOver="cellOver(this,'<?PHP echo strftime('%D',$date);?>');" title="<?PHP echo strftime('%D',$date);?>" id="<?PHP echo strftime('%D',$date);?><?PHP echo strftime('%R',$time);?>"></td>					
				<td class="whiteSpace" width="5"></td>
			<?PHP }//for ($day=(( ?>
			</tr>
			<?PHP 
		
		
		}//else clickable boxes
		} //for ($timeLine=0;$timeLine<=57600;$timeLine+=900)
	   } //for ($week=1;$week<=2;$week++) ?>
	</table>


<?PHP } //end function


if (isset($_POST['id'])) $id=$_POST['id'];
else if (isset($_GET['id'])) $id=$_GET['id'];


if (!isset($id))
{
	$workPeriod = new biData(NULL,mktime());
	$id = $workPeriod->id;
}
else
$workPeriod=new biData($id,NULL);

//have I submitted a time sheet



include "index_template.php";


?>