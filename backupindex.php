<?PHP
require_once "payroll.php";
function displayPeriodInfo($id)
{
	$period = returnBiData($id,NULL);
	?>
	<table border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>Payroll ID</td>
    <td>Work Period Beginning</td>
    <td>Work Period Ending</td>
    <td>Time Sheet Due Date</td>
    <td>Payday</td>
  </tr>
  <tr>
    <td><?PHP echo $period['id'];?></td>
    <td><?PHP echo strftime('%D',$period['startDate']);?></td>
    <td><?PHP echo strftime('%D',$period['endDate']);?></td>
    <td><?PHP echo strftime('%D',$period['dueDate']);?></td>
    <td><?PHP echo strftime('%D',$period['payday']);?></td>
  </tr>
</table>
<?PHP
}

function displayTimePeriod ($id)
{
	$period = returnBiData($id,NULL);
	
	echo '<script language="JavaScript" type="text/JavaScript">
          function hitAllDates()
 		  {
		  ';
		for ($week=1;$week<=2;$week++)
		{
		  for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period['startDate'])." + $day days");$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period['startDate'])." + $day days")) 
		  {
		   echo "setDown('".strftime('%D',$date)."');\n";
		  }
		}
	echo '}
		  </script>
		  ';?>
	<table cellpadding="0" cellspacing="0">
	<?PHP
	for ($week=1;$week<=2;$week++)
	{
		$class='timeRowVeryDark';
		?>
		<tr class="dateLine"><td></td><td class="whiteSpace" width="5"></td>
		<?PHP for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period['startDate'])." + $day days");$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period['startDate'])." + $day days")) {
		 //for ($day=(($week-1)*7), $date=$period['startDate']+$day*86400;$day<=((($week-1)*7)+6);$day++,$date=$period['startDate']+$day*86400) {
		?>
	
		<td><? //echo $day."d after: ".$date."<br>";
		echo strftime('%D',$date);?><br>
		<input name="<?PHP echo strftime('%D',$date)."SA";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."S";?>">-<input name="<?PHP echo strftime('%D',$date)."FA";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."F";?>"><br>
		<input name="<?PHP echo strftime('%D',$date)."SB";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."S";?>">-<input name="<?PHP echo strftime('%D',$date)."FB";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."F";?>"><br>
		<input name="<?PHP echo strftime('%D',$date)."SC";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."S";?>">-<input name="<?PHP echo strftime('%D',$date)."FC";?>" type="text" size="4" id="<?PHP echo strftime('%D',$date)."F";?>"></td>
		<td class="whiteSpace" width="5"></td>
		<?PHP }//for ($day=(($week-1)*7), $dat ?>
		
		</tr>
		<?PHP for ($timeLine=0;$timeLine<57600;$timeLine+=900) {
			if (($timeLine % 3600) == 0) $class='timeRowVeryDark';//60min
			else if (($timeLine % 1800) == 0) $class='timeRowDark';//30min
			else $class='timeRowLight';//15 minute
			?>
			
			<tr class='<?PHP echo $class;?>'>
			<?PHP if (($timeLine % 1800) == 0) {?>
				<td rowspan="2" onMouseDown="Javascript: columnDown(this,'<?PHP echo strftime('%R',strtotime(strftime('%D',$period['startDate'])." 06:00")+$timeLine);?>');"
					onMouseUp="Javascript: columnUp(this,'<?PHP echo strftime('%R',strtotime(strftime('%D',$period['startDate'])." 06:00")+$timeLine+1800);?>');"
					onMouseOver="Javascript: columnOver(this);">
					
					<!-- onMouseDown="Javascript: MOUSEDOWN='<?PHP echo strftime('%R',strtotime(strftime('%D',$period['startDate'])." 06:00")+$timeLine);?>'; ORIGIN='leftmostColumn'; this.className='redCell';" -->
					<!--onMouseUp="Javascript: MOUSEUP='<?PHP echo strftime('%R',strtotime(strftime('%D',$period['startDate'])." 06:00")+$timeLine+1800);?>'; if (ORIGIN=='leftmostColumn') {hitAllDates();} MOUSEDOWN=''; MOUSEUP=''; ORIGIN='';" -->
					<!-- onMouseOver="Javascript:if(MOUSEDOWN != '' && ORIGIN=='leftmostColumn') this.className='redCell';"-->
					
				<?PHP echo strftime('%R',strtotime(strftime('%D',$period['startDate'])." 06:00")+$timeLine);//daylight savings safe ?>
				</td><td rowspan="2" class="whiteSpace" width="5"></td>
			<?PHP } //if ($class=='timeRowDark')?>
			<?PHP for ($day=(($week-1)*7), $date=strtotime(strftime('%D',$period['startDate'])." + $day days"),$time=strtotime(strftime('%D',$period['startDate'])." + $day days 06:00")+$timeLine;$day<=((($week-1)*7)+6);$day++,$date=strtotime(strftime('%D',$period['startDate'])." + $day days"),$time=strtotime(strftime('%D',$period['startDate'])." + $day days 06:00")+$timeLine) {?>
				<td onMouseDown="Javascript: cellDown(this,'<?PHP echo strftime('%R',$time);?>','<?PHP echo strftime('%D',$date);?>');"
					onMouseUp="Javascript: cellUp(this,'<?PHP echo strftime('%R',$time+900);?>','<?PHP echo strftime('%D',$date);?>');"
					onMouseOver="Javascript: cellOver(this,'<?PHP echo strftime('%D',$date);?>');">

					<!-- onMouseDown="Javascript: MOUSEDOWN='<?PHP echo strftime('%R',$time);?>'; ORIGIN='<?PHP echo strftime('%D',$date);?>'; this.className='redCell';"-->
					<!-- onMouseUp="Javascript: MOUSEUP='<?PHP echo strftime('%R',$time+900);?>'; if (ORIGIN=='<?PHP echo strftime('%D',$date);?>') {setDown('<?PHP echo strftime('%D',$date);?>');} MOUSEDOWN=''; MOUSEUP=''; ORIGIN=''" -->
					<!--onMouseOver="Javascript:if(MOUSEDOWN != '' && ORIGIN=='<?PHP echo strftime('%D',$date);?>') this.className='redCell';"-->
					
					<td class="whiteSpace" width="5"></td>
				<input name="<?PHP echo strftime('%Y%m%d%H%M%S',$time);?>" type="hidden" value="<?PHP echo $timeMatrix[strftime('%Y%m%d%H%M%S',$time)];?>"><?PHP //echo $time." ".strftime('%R',$time); }?></td>
			<?PHP }//for ($day=(( ?>
			</tr>
		<?PHP 
		} //for ($timeLine=0;$timeLine<=57600;$timeLine+=900)
	   } //for ($week=1;$week<=2;$week++) ?>
	</table>


<?PHP } //end function

include "index_template.php";
?>