<html>
<head>
<title>Payday Enter Timesheet</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="styles.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
var MOUSEDOWN='';
var MOUSEUP='';
var ORIGIN='';
var placeholder='';

function columnDown(element,down)
{
	MOUSEDOWN=down;
	ORIGIN='leftmostColumn';
	//element.className='redCell';
	cells = document.getElementsByName(element.name);
	for(j = 0; j < cells.length; j++) cells[j].style.backgroundColor = '#ff0000';
	
	//do the other row of the 2 row span
	nextRow=element.name.substring(0,3);
	tailend=element.name.substring(3,5);
	if (tailend=='00')
	nextRow=nextRow+'15';
	else nextRow=nextRow+'45';
	cells = document.getElementsByName(nextRow);
	for(j = 0; j < cells.length; j++) cells[j].style.backgroundColor = '#ff0000';
}

function columnUp(element,up)
{
	MOUSEUP=up; 
	if (ORIGIN=='leftmostColumn') {hitAllDates();}
	MOUSEDOWN='';
	MOUSEUP='';
	ORIGIN='';
}

function columnOver(element)
{
	if(MOUSEDOWN != '' && ORIGIN=='leftmostColumn')
	{
		cells = document.getElementsByName(element.name);
		for(j = 0; j < cells.length; j++) cells[j].style.backgroundColor = '#ff0000';
		
		//do the other row of the 2 row span
		nextRow=element.name.substring(0,3);
		tailend=element.name.substring(3,5);
		if (tailend=='00')
		nextRow=nextRow+'15';
		else nextRow=nextRow+'45';
		cells = document.getElementsByName(nextRow);
		for(j = 0; j < cells.length; j++) cells[j].style.backgroundColor = '#ff0000';
	}
		//element.className='redCell';
}

function cellDown(element,down,cellOrigin)
{
	MOUSEDOWN=down; 
	ORIGIN=cellOrigin;
	element.style.backgroundColor = '#ff0000';
}

function cellUp(element,up,ifOrigin)
{
	MOUSEUP=up;
	if (ORIGIN==ifOrigin) {setDown(ifOrigin);}
	MOUSEDOWN='';
	MOUSEUP='';
	ORIGIN='';
}

function cellOver(element,ifOrigin)
{
	if(MOUSEDOWN != '' && ORIGIN==ifOrigin) element.style.backgroundColor = '#ff0000';
}

function hitAllDates()
{
	if (MOUSEDOWN>MOUSEUP)
	{
		placeholder=MOUSEDOWN;
		MOUSEDOWN=MOUSEUP;
		MOUSEUP=placeholder;
	}	
	with (document.timeSheet) {
		var fieldName='';
		var oldFieldName='';
		for (var i=0; i < elements.length; i++) {
			fieldName=elements[i].id;
			fieldType=fieldName.substring(8,9);
			if (elements[i].type == 'text' && fieldType=='S' && elements[i].value == '' && fieldName !== oldFieldName)
			{
				elements[i].value = MOUSEDOWN;
				elements[i+1].value = MOUSEUP; //assume its the next field
				oldFieldName=fieldName;
			}
		}
	}
}


function setDown (theDate)
{
  //alert('mousedown:' + MOUSEDOWN + 'mouseup:' + MOUSEUP + ' on ' + theDate);
  if (MOUSEDOWN>MOUSEUP)
  {
  	placeholder=MOUSEDOWN;
	MOUSEDOWN=MOUSEUP;
	MOUSEUP=placeholder;
  }	
  var startField=theDate+'S';
  var finishField=theDate+'F';
  var emptyNotFound=true;
  with (document.timeSheet) {
		for (var i=0; i < elements.length && emptyNotFound; i++) {
			if (elements[i].type == 'text' && elements[i].id == startField && elements[i].value == '')
			{
					emptyNotFound=false;
					elements[i].value = MOUSEDOWN;
					elements[i+1].value = MOUSEUP; //assume its the next field
			}
		}
		if (emptyNotFound)
		{
			alert ('You have no more time slots remaining for this day');
			return (false);
		}
		//MOUSEDOWN='';
		//MOUSEUP='';
  }
}
function resetDate(theDate)
{
	 with (document.timeSheet) {
		for (var i=0; i < elements.length; i++) {
		    var startDate=theDate+'S';
			var finishDate=theDate+'F';
			if (elements[i].type == 'text' && elements[i].id == startDate || elements[i].id == finishDate)
			{
				elements[i].value='';
			}
		}
	}
	//alert('looking for: ' + theDate);
	var colCells = document.getElementsByName(theDate);
	for(j = 0; j < colCells.length; j++)
	{
		colCells[j].className = ''; //gets rid of filled cells
		colCells[j].style.backgroundColor = ''; //get rids of hot cells
	}
}

function checkTimes ()
{
	var goodSheet=true;
	var badValue=false;
	with (document.timeSheet) {
		if (elements[0].type == 'text'  && elements[0].value != '' && TestString(elements[0].value)==false)
		{
			//gotta do the first one here
			//alert ('bad value');
			//elements[0].style.backgroundColor = '#ff0000';
			elements[0].className = 'redCell';
			goodSheet=false;
			badValue=true;
		}
		else
		{
			elements[0].style.backgroundColor = '';
		}
			
		for (var i=1; i < elements.length; i++) 
		{
			//for making sure they are in teh same day when comparing for overlap
			//this is looking for starting times that are earlier than preceeding finish times in the same day
			//by transitive property you do not have to compare the third slot with the first
			var prevID=elements[i-1].id;
			var nextID=elements[i].id;
			if (elements[i].type == 'text'  && elements[i].value != '' && TestString(elements[i].value)==false && elements[i].name != 'acct1' && elements[i].name != 'acct2')
			{
				//gotta do the first one here
				//alert ('bad value');
				//elements[i].style.backgroundColor = '#ff0000';
				elements[i].className = 'redCell';
				goodSheet=false;
				badValue=true;
			}
			if ((elements[i].type == 'text'  && elements[i].value != '') && ( (elements[i].value < elements[i-1].value && prevID.substring(0,8) == nextID.substring(0,8)) ))
			{
				if (elements[i-2].value < elements[i+1].value)
				{
					alert ("Overlapping time: Offending start time is highlighted");
					//elements[i].style.backgroundColor = '#ff0000';
					elements[i].className = 'redCell';
					goodSheet=false;
				}
			}
			else
			{
				elements[i].style.backgroundColor = '';
			}
			if ((elements[i].type == 'text'  && elements[i-1].type == 'text' && prevID.substring(8,9)=='S' && nextID.substring(8,9)=='F') && ((elements[i].value == '' && elements[i-1].value != '') || (elements[i].value != '' &&  elements[i-1].value == '')))
			{
				alert ("Missing element. Box is highlighted");
				if (elements[i].value == '') {
				elements[i].style.backgroundColor = '#ff0000';
				elements[i].className = 'redCell';
				}
				else {
					elements[i-1].style.backgroundColor = '#ff0000';
					elements[i-1].className = 'redCell';
					}
				goodSheet=false;
			}
		}	
	}
	if (goodSheet==true)
	{
		//alert('returning true');\
		
		return true;
	}
	else
	{
		if (badValue==true) { alert('Bad value'); }
		return false;
	}
}

function TestString(S) {
    return /^(24):(00)|([01][0-9]|2[0-3]):(00|15|30|45)$/.test(S);
	//http://www.merlyn.demon.co.uk/js-date4.htm
}

</script>

</head>

<body bgcolor="#ECE9D8">
<table border="0" cellspacing="3" cellpadding="3">
  <tr class="datesHeaders"> 
    <td rowspan="2" valign="top"> <?PHP echo $viewInfo['lastname'].",".$viewInfo['firstname']."<br>".$viewInfo['ssn']."</td>";?> 
    <td rowspan="2" valign="top">Groups: 
      <?PHP 
	  			foreach ($viewGroups as $group) echo $group."<br>";?>
    </td>
    <td width="100%" align="right" valign="bottom"> 
      <?PHP if ($myRights->showUsers(NULL)) {?>
      User schedules: 
      <?PHP }?>
    </td>
    <td width="0%" align="right" valign="bottom"><a href="guide.htm">Help</a> 
      <?PHP if ($myRights->showUsers(NULL)) {?><br><form action="index.php" method="get" name="bigswitch" id="bigswitch" ><select name="userid" onChange="document.bigswitch.submit();"><?PHP $myRights->showUsersAsSelect($viewUserid);?></select><input type="hidden" name="id2" value="<?PHP echo $id;?>"></form><?PHP } ?>
    </td>
  </tr>
  <tr valign="top"> 
    <td width="100%" align="right" class="datesHeaders">
      <?PHP if ($myRights->showUsers(NULL)) {?>
      Multiple user schedules:
      <?PHP }?>
    </td>
    <td width="0%" align="right" class="datesHeaders"> 
      <?PHP if ($myRights->showUsers(NULL)) {?>
      <form action="index.php" method="post" name="multiple" id="multiple" >
        <span class="users"> 
        <select name="userids[]" size="5" multiple>
          <?PHP $myRights->showUsersAsSelect($viewUserids);?>
        </select>
        </span> 
        <input type="hidden" name="stage" value="multiple">
        <input type="hidden" name="id" value="<?PHP echo $id;?>">
        <input name="imageField" type="image" src="images/go.gif" width="25" height="18" border="0">
      </form>
      <?PHP } ?>
    </td>
  </tr>
  <tr> 
    <td valign="top"> 
      <?PHP $yr=date("Y"); for($mo=6;$mo<=12;$mo++) include "calendar.php";?>
    </td>
    <td valign="top"> 
      <?PHP $yr++; for($mo=1;$mo<=5;$mo++) include "calendar.php";?>
    </td>
    <td colspan="2" align="center" valign="top"> <form action="index.php" method="post" name="timeSheet" id="timeSheet" onSubmit="return checkTimes();">
        <input type="hidden" name="userid" value="<?PHP echo $viewUserid;?>">
        <input type="hidden" name="id" value="<?PHP echo $id;?>">
        <input type="hidden" name="stage" value="<?PHP //if ($stage=='new' || $stage=='edit') echo "confirm"; else if (($stage=='view' || $stage=='lock') && ($timeMatrix->isLocked($id,$viewUserid)==false || $myRights->showRights($viewUserid)=='W')) echo "edit"; else if ($stage=='confirm') echo "lock";?>">
        <?PHP displayPeriodInfo($workPeriod);?>
        <br>
        <?PHP if ($stage=='multiple') 
		{ 
			displayTimePeriod ($workPeriod,$stage,$timeMatrices,$viewInfo['acct'],$viewInfo['rate'],false);
		}
		else
		{
			if ($myRights->showRights($viewUserid)=='W')
				displayTimePeriod ($workPeriod,$stage,$timeMatrix,$viewInfo['acct'],$viewInfo['rate'],true);
			else
				displayTimePeriod ($workPeriod,$stage,$timeMatrix,$viewInfo['acct'],$viewInfo['rate'],false);
		} ?>
        <br>
        <?PHP   if ($stage=='new' || $stage=='edit') {?>
        <input type="image" src="images/submit.gif" onMouseDown="Javascript:document.timeSheet.stage.value='confirm';">
        <?PHP } 
		else if ($timeMatrix->isLocked($id,$viewUserid)==false || $myRights->showRights($viewUserid)=='W') {?>
        <input type="image" src="images/edit.gif" onMouseDown="Javascript:document.timeSheet.stage.value='edit';">
        <?PHP } 
			 
		if (($stage=='lock' || $stage=='view') ) {
			
			if ($timeMatrix->isLocked($id,$viewUserid)==true) {
				if ($myRights->showRights($viewUserid)=='W') { ?>
        <input type="image" src="images/unlock.gif" onMouseDown="Javascript:document.timeSheet.stage.value='unlock';">
        <?PHP } ?>
        <input type="image" src="images/print.gif" onMouseDown="Javascript:document.timeSheet.stage.value='print';">
        <?PHP }
		
		} 
		
		if ($stage=='confirm' || $stage=='view' || $stage=='unlock') {?>
        <?PHP if ($timeMatrix->isLocked($id,$viewUserid)==false) {?>
        <input type="image" src="images/confirm.gif" onMouseDown="Javascript:document.timeSheet.stage.value='lock';">
        <?PHP }}
		if (debug) echo "<br>---".$stage."---"; ?>
      </form></td>
  </tr>
</table>


</body>
</html>
