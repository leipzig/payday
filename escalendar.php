<?

// Easily Simple Calendar
// Version 3.3 (July 5, 2003)
// (c) 2001-2003 Brian E. Nash -- Easily Simple Scripts
// http://calendar.esscripts.com

// Post bugs, comments, questions, suggestions at
// http://www.esscripts.com/forum/

// THIS SCRIPT IS FREE ONLY FOR NON-COMMERCIAL USE
// If you intend to use this script on any website other than a persoanl, 
// non-commercial webiste or woule like to make a donation to the development
// of this script, visit http://calendar.esscripts.com/buyonline.php.

// Please take the time to vote for this script on HotScripts at the address below:
// http://www.hotscripts.com/Detailed/10540.html

// !!! Use this script at your own risk!
// !!! There are no warranties or guarantees of any kind.

// THIS SCRIPT IS NOT TO BE INCLUDED AND >>DISTRIBUTED<< IN YOUR OWN WORKS 
// UNLESS A COMMERCIAL LICENSE OR PERMISSION HAS BEEN OBTAINED FROM THE AUTHOR.

////////////////////////////////////////////////////////////////////////////////////
// EDIT ANY OF THE VARIABLES BELOW TO MEET YOUR NEEDS
// [See readme.htm for complete instructions]

//////////////////////////////////////////
//////////BEGIN CALENDAR CODE ////////////
//////////////////////////////////////////

// QUICK REFERENCE
// :: uc = Use Configuration File
// :: ms = Mark Style
// :: ds = Day Start
// :: ny = Not Display Year
// :: nt = Not Mark Today
// :: es = Event Start
// :: ee = Event End
// :: ot = Offset Time
// :: th = Table Height
// :: tw = Table Width
// :: cs = Cell Spacing
// :: cp = Cell Padding
// :: al = Cell Alignment
// :: nbc = Normal Numbers Background Color
// :: tfc = Todays Font Color
// :: mbc = Marked Numbers Background Color
// :: dtc = Day Names Table Background Color
// :: ntc = Blank Numbers Background Color
// :: mtc = Month and Year Table Background Color
// :: fsm = Font Fize Month
// :: fsd = Font Size Dates
// :: fsn = Font Size Numbers
// :: fwm = Font Weight Month Name
// :: fwd = Font Weight Day Names
// :: fwn = Font Weight Numbers

// LOAD CONFIGURATION FILE
// $uc=x :: Use the configuration file set as 'x'
// This option can be set here or overridden by the command-line option 'uc='
if (!$uc) $uc = "0"; // SET THIS TO A NUMBER TO USE THE CONFIGURATION FILE
if ($uc>0 && file_exists("esconfig.php")) {
require("esconfig.php");
$config = ereg_replace ("\?", "", $cfg[$uc]);
$config = explode ("&",$config);
foreach ($config as $values) {
$c0 = explode ("=",$values);
$$c0[0] = $c0[1];
}}

// CALENDAR MARKING STYLE
// $ms=1 :: Mark calendar dates using table background colors [Default]
// $ms=2 :: Mark calendar dates using graphics for background colors
// NOTE: If using Style 2, all 'stat' graphics should be in the same directory as this script
// You can edit the 'stat' graphics to meet your needs
if (!$ms) $ms = "1";

// CALENDAR MARKING COLORS
// Edit these colors to suit your needs
// These options change be changed here or by the command-line options 'nbc', 'mbc', or 'tfc'
if (!$nbc) $nbc = "EEEEEE"; // NORMAL BACKGROUND COLOR
if (!$mbc) $mbc = "EEBBBB"; // MARKED BACKGROUND COLOR
if (!$tfc) $tfc = "CF0000"; // TODAY'S FONT COLOR

// DAY NAMES
// Edit the calendar day name column headers below
$day[0]="S";
$day[1]="M";
$day[2]="T";
$day[3]="W";
$day[4]="T";
$day[5]="F";
$day[6]="S";

// MONTH NAMES
// Edit the calendar month names below
$mth[1]="January";
$mth[2]="February";
$mth[3]="March";
$mth[4]="April";
$mth[5]="May";
$mth[6]="June";
$mth[7]="July";
$mth[8]="August";
$mth[9]="September";
$mth[10]="October";
$mth[11]="November";
$mth[12]="December";

// ON WHAT DAY DOES THE WEEK START?
// 0=Sunday; 1=Monday; 2=Tuesday; 3=Wednesday; 4=Thursday; 5=Friday; 6=Saturday
// This option can be set here or overridden by the command-line option 'ds='
if (!$ds) $ds=6;

// PRINT YEAR?
// $ny=1 :: Don't display the year number after the month name
// This option can be set here or overridden by the command-line option 'ny='
if (!$ny) $ny=0;

// MARK TODAY?
// $nt=1 :: Don't mark today's date
// This option can be set here or overridden by the command-line option 'nt='
if (!$nt) $nt=0;

// HOURS TO OFFSET TIME +/-
// To add hours, enter a positive value. To substract hours, enter a "-" negative value
// This option can be set here or overridden by the command-line option 'ot='
if (!$ot) $ot = "-0";

// CALENDAR WIDTH AND HEIGHT
// Set $tw to the width of the calendar table
// Set $th to the height of the calendar table (does not include the month table which is additional)
// These options can be set here or overridden by the command-line options 'tw=' and 'ch='
if (!$tw) $tw="175"; // Table Width
if (!$th) $th="140"; // Table Height (with 6 rows of dates)
// NOTE: Table may be smaller than specified for months with only 5 rows of dates

// CALENDAR CELL SPACING AND PADDING
// Set $cs to the number of pixels of cell spacing (space between each cell)
// Set $cp to the number of pixels of cell padding (space around names and numbers inside cells)
// These options can be set here or overridden by the command-line options 'cs=' and 'cp='
if (!$cs) $cs=1; // IMPORTANT! To set cs to 0 from the command-line, you must use -0
if (!$cp) $cp=0;

// DATE NUMBER ALIGNMENT
// Set $algn to 0 [defult] to align the dates to the middle and center of table cells.
// Set $algn to 1 to align the dates to the upper-right corner of table cells.
// This option can be set here or overridden by the command-line option 'algn='
if (!$al) $al="0";

// FONT SIZES AND WEIGHT
// These options can be set in the script or overridden by command-line options of
// the same named variables. The sizes are in pixels.

if (!$fsm) $fsm="18"; // FONT SIZE MONTH
if (!$fsd) $fsd="11";  // FONT SIZE DAY NAMES
if (!$fsn) $fsn="11"; // FONT SIZE NUMBERS

if (!$fwm) $fwm="bold"; else $fwm="normal"; // FONT WEIGHT MONTH
if (!$fwd) $fwd="normal"; else $fwd="bold"; // FONT WEIGHT DAY NAMES
if (!$fwn) $fwn="normal"; else $fwn="bold"; // FONT WEIGHT NUMBERS

// ONLY EDIT THE STYLE SHEET BELOW IF YOU WANT TO CHANGE THE FONT FACE AND/OR COLOR

?> 
<style TYPE="text/css">
<!--
.monthyear<?echo$uc?> {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: <?echo$fsm?>px; font-weight: <?echo$fwm?>; color: #000000}
.daynames<?echo$uc?> {  font-family: Arial, Helvetica, sans-serif; font-size: <?echo$fsd?>px; font-weight: <?echo$fwd?>; color: #000000}
.dates<?echo$uc?> {  font-family: Arial, Helvetica, sans-serif; font-size: <?echo$fsn?>px; font-weight: <?echo$fwn?>; color: #000000}
-->
</style>

<?

////////////////////////////////////////
///// DO NOT EDIT BELOW THIS POINT /////
////////////////////////////////////////

// DETERMINE AND SET DATE NUMBER CELL ALIGNMENT
if ($al==1) $al="align=\"right\" valign=\"top\"";
elseif ($al==2) $al="align=\"left\" valign=\"top\"";
elseif ($al!=1 && $al!=2) $al="align=\"center\" valign=\"middle\"";

// DETERMINE TODAYS DAY NUMBER
$ot = $ot*3600;
$tmo = date("m", time()+$ot);
$tda = date("j", time()+$ot);
$tyr = date("Y", time()+$ot);
$tnum = (intval((date ("U", mktime(0,0,0,$tmo,$tda,$tyr)-(intval(date("O", mktime(0,0,0,$tmo,$tda,$tyr)))*36))/86400))); // TODAY'S DAY NUMBER

// CHECK FOR COMMAND LINE DATE VARIABLES
if (!$mo) $mo=$tmo;
if (!$yr) $yr=$tyr;

$daycount = (intval((date ("U", mktime(0,0,0,$mo,1,$yr)-(intval(date("O", mktime(0,0,0,$mo,1,$yr)))*36))/86400)))-$ds; // FIRST OF MONTH DAY NUMBER
$daycount=$daycount+$ds; // ADJUST FOR DAY START VARIABLE

$mo=intval($mo);
$mn = $mth[$mo]; // SET MONTH NAME
if ($ny!=1) {$mn = $mn." ".$yr;} // ADD YEAR TO MONTH NAME?

// ON WHAT DAY DOES THE FIRST FALL
$sd = date ("w", mktime(0,0,0,$mo,1-$ds,$yr));
$cd = 1-$sd;

// NUMBER OF DAYS IN MONTH
$nd = mktime (0,0,0,$mo+1,0,$yr);
$nd = (strftime ("%d",$nd))+1;

////////////////////////////////////////
// PROCESS DAY MARKING /////////////////
////////////////////////////////////////

if ($es) {

$es = explode ("x",$es);
$smc = count ($es);
$ee = explode ("x",$ee);
$emc = count ($ee);

if ($smc==1) {
$es[1]="3000-01-01";
$ee[1]="3000-01-01";
}
}

$i=0;

while ($i < $smc) {

$es[$i] = ereg_replace('-','/', $es[$i]);
$ee[$i] = ereg_replace('-','/', $ee[$i]);
$start = intval((strtotime($es[$i])+(date("O", strtotime($es[$i]))*36))/86400);
$end = intval((strtotime($ee[$i])+(date("O", strtotime($ee[$i]))*36))/86400);

if (!$ee[$i]) $end=$start; // MARK SINGLE DAY WITH ONLY ES VARIABLE

if (!$bgc[$start]) {$bgc[$start]=1;} else {$bgc[$start]=4;}
$bgc[$end]=3;
for ($n = ($start+1); $n < $end; $n++) {
$bgc[$n] = 2;}
$i++;
}

////////////////////////////////////////////
// DISPLAY CALENDAR ////////////////////////
////////////////////////////////////////////

// ADJUST TABLE HEIGHT FOR 5 ROW MONTHS
$checksize=$fsd+$cs+$cp;
$checkrows=$ds+$nd-$cd;
if ($checkrows<36) $th=$th-intval(($th-$checksize)/6);

?>
<table WIDTH="<?echo$tw?>" BORDER="0" CELLSPACING="<?echo$cs?>" CELLPADDING="<?echo$cp?>">
 <tr> 
  <td CLASS="monthyear<?echo$uc?>" <?if($mtc) {echo " BGCOLOR=\"#$mtc\"";}?>> 
   <div ALIGN="center"><?echo "$mn";?></div>
  </td>
 </tr>
</table>

<table WIDTH="<?echo$tw?>" HEIGHT="<?echo$th?>" BORDER="0" CELLSPACING="<?echo$cs?>" CELLPADDING="<?echo$cp?>">

 <tr ALIGN="center" HEIGHT="<?echo$fsd+$cs+$cp?>" CLASS="daynames<?echo$uc?>"<?if($dtc) {echo " BGCOLOR=\"#$dtc\"";}?>>
<?
for ($I=0;$I<7;$I++) {
  $dayprint=$ds+$I;
  if ($dayprint>6) $dayprint=$dayprint-7;
  echo"  <td WIDTH=$cw>$day[$dayprint]</td>\n";
  }
?>
 </tr>
 
<?
// PRINT CALENDAR USING TABLE BACKGROUND COLORS [CALENDAR STYLE 1]
if ($ms==1) { 
for ($i = 1; $i<7; $i++) { 
if ($cd>=$nd) break;
?>
 <tr <? echo $al ?> CLASS="dates<? echo $uc?>"<? if($ntc) {echo " BGCOLOR=\"#$ntc\"";}?>> 
<?
for ($prow = 1; $prow<8; $prow++) { 
if ($daycount==$tnum && $nt!="1" && $cd>0 && $cd<$nd) {echo "  <td bgcolor=\"#";if ($bgc[$daycount]) {echo $mbc;} else {echo $nbc;} echo "\"><font color=\"#$tfc\">$cd</font></td>\n";$daycount++;$cd++;}
else { ?>
  <td<? if ($cd>0 && $cd<$nd) {echo " bgcolor=\"#";if ($bgc[$daycount]) {echo $mbc;} else {echo $nbc;} echo "\">$cd";$daycount++;} else {echo ">";} $cd++;?></td>
<? }} ?>
 </tr>
<?
}
} // END [CALENDAR STYLE 1]

// PRINT CALENDAR USING GRAPHICS BACKGROUNDS [CALENDAR STYLE 2]
if ($ms==2) { 

for ($i = 1; $i<7; $i++) { 
if ($cd>=$nd) break;
?>
 <tr <? echo $al ?> CLASS="dates<? echo $uc ?>"<? if($ntc) {echo " BGCOLOR=\"#$ntc\"";}?>> 
<?
for ($prow = 1; $prow<8; $prow++) { 
if ($daycount==$tnum && $nt!="1" && $cd>0 && $cd<$nd) {echo "  <td background=\"stat$bgc[$daycount].gif\"><font color=\"#$tfc\">$cd</font></td>\n";$daycount++;$cd++;}
else { ?>
  <td<? if ($cd>0 && $cd<$nd) {echo " background=\"stat$bgc[$daycount].gif\">$cd";$daycount++;} else {echo ">";} $cd++;?></td>
<? }} ?>
 </tr>
<?
}
} // END [CALENDAR STYLE 2]

?>
</table>