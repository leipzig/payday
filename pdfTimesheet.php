<?PHP
				//produce pdf
		include ('class.ezpdf.php');
		$pdf =& new Cezpdf();
		$pdf->selectFont('./fonts/Helvetica-Bold.afm');
		$ypos=$pdf->ezText('North Carolina State University Electrical and Computer Engineering - Temporary Employee Time Record', 10, array('justification'=>'center'));
		//$pdf->ezTable($pdfdata,'',"Search criteria: $argString  Sort by: $ordString",array('width'=>550,'fontSize'=>7,'showLines'=>2,'lineCol'=>array(1,1,1),'shaded'=>2,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.97,.97,.97),'cols'=>array('Last, First Name'=>array('width'=>70),'GRE'=>array('width'=>28))));
		$pdf->ezSetY($ypos-10);
		$firstcol[]=array('first'=>"Name\n");
		$firstcol[]=array('first'=>"SS#\n");
		$secondcol[]=array('second'=>$viewInfo['lastname'].", ".$viewInfo['firstname']."\n");
		$secondcol[]=array('second'=>$viewInfo['ssn']."\n");
		$thirdcol[]=array('third'=>"Work Period\nBeginning");
		$thirdcol[]=array('third'=>"Work Period\nEnding");
		$fourthcol[]=array('fourth'=>strftime('%D',$workPeriod->startDate)."\n");
		$fourthcol[]=array('fourth'=>strftime('%D',$workPeriod->endDate)."\n");
		$fifthcol[]=array('fifth'=>"PRID\n");
		$fifthcol[]=array('fifth'=>"Dept./Box#\n");
		$sixthcol[]=array('sixth'=>"20042R08\n");
		$sixthcol[]=array('sixth'=>"ECE, 7243\n");
		$seventhcol[]=array('seventh'=>"Time Sheet\nDue Date");
		$seventhcol[]=array('seventh'=>"Pay Day\n");
		$eighthcol[]=array('eighth'=>strftime('%D',$workPeriod->dueDate)."\n");
		$eighthcol[]=array('eighth'=>strftime('%D',$workPeriod->payday)."\n");
		$pdf->ezTable($firstcol,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>50,'shaded'=>2,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>75,'xOrientation'=>'left'));
		$pdf->ezSetY($ypos-10);
		$pdf->ezTable($secondcol,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>115,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>190,'xOrientation'=>'left'));
		$pdf->ezSetY($ypos-10);
		$pdf->ezTable($thirdcol,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>75,'shaded'=>2,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>265,'xOrientation'=>'left'));
		$pdf->ezSetY($ypos-10);
		$pdf->ezTable($fourthcol,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>50,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>315,'xOrientation'=>'left'));
		$pdf->ezSetY($ypos-10);
		$pdf->ezTable($fifthcol,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>65,'shaded'=>2,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>380,'xOrientation'=>'left'));
		$pdf->ezSetY($ypos-10);
		$pdf->ezTable($sixthcol,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>60,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>440,'xOrientation'=>'left'));
		$pdf->ezSetY($ypos-10);
		$pdf->ezTable($seventhcol,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>85,'shaded'=>2,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>525,'xOrientation'=>'left'));
		$pdf->ezSetY($ypos-10);
		$pdf->ezTable($eighthcol,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>50,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>575,'xOrientation'=>'left'));
		$pdf->ezSetY($ypos-100);
		//saturday,sunday
		$dayheaders[]=array('1'=>'Saturday','2'=>'Sunday','3'=>'Monday','4'=>'Tuesday','5'=>'Wednesday','6'=>'Thursday','7'=>'Friday');
		$pdf->ezTable($dayheaders,'','',array('showLines'=>0,'showHeadings'=>0,'width'=>550,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>25,'xOrientation'=>'right',
					   'cols'=>array('1'=>array('width'=>78.56,'justification'=>'center'),'2'=>array('width'=>78.56,'justification'=>'center'),'3'=>array('width'=>78.56,'justification'=>'center'),'4'=>array('width'=>78.56,'justification'=>'center'),'5'=>array('width'=>78.56,'justification'=>'center'),'6'=>array('width'=>78.56,'justification'=>'center'),
					   '7'=>array('width'=>78.56,'justification'=>'center')) ));
		
		$hourheaders[]=array('1'=>'IN','2'=>'OUT','3'=>'IN','4'=>'OUT','5'=>'IN','6'=>'OUT','7'=>'IN','8'=>'OUT','9'=>'IN','10'=>'OUT','11'=>'IN','12'=>'OUT','13'=>'IN','14'=>'OUT');
		
		
		$ypos=$pdf->ezTable($hourheaders,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>550,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>25,'xOrientation'=>'right',
					   'cols'=>array('1'=>array('width'=>39.28,'justification'=>'center'),'2'=>array('width'=>39.28,'justification'=>'center'),'3'=>array('width'=>39.28,'justification'=>'center'),'4'=>array('width'=>39.28,'justification'=>'center'),'5'=>array('width'=>39.28,'justification'=>'center'),'6'=>array('width'=>39.28,'justification'=>'center'),
					   '7'=>array('width'=>39.28,'justification'=>'center'),'8'=>array('width'=>39.28,'justification'=>'center'),'9'=>array('width'=>39.28,'justification'=>'center'),'10'=>array('width'=>39.28,'justification'=>'center'),'11'=>array('width'=>39.28,'justification'=>'center'),'12'=>array('width'=>39.28,'justification'=>'center'),'13'=>array('width'=>39.28,'justification'=>'center'),
					   '14'=>array('width'=>39.28,'justification'=>'center')) ));
					   
		for ($day=0, $date=strtotime(strftime('%D',$workPeriod->startDate)." + $day days"),$xpos=25;$day<=6;$day++,$date=strtotime(strftime('%D',$workPeriod->startDate)." + $day days"),$xpos+=78.56)
   		{
			$pdf->ezSetY($ypos);
			$hourbody=NULL;
			if ($timeMatrix->entryExists(strftime('%D',$date),0))
        		$hourbody[]=array('1'=>$timeMatrix->showEntry12Hour(strftime('%D',$date),0,'S'),'2'=>$timeMatrix->showEntry12Hour(strftime('%D',$date),0,'F'));
			else $hourbody[]=array('1'=>" ",'2'=>" ");
			if ($timeMatrix->entryExists(strftime('%D',$date),1))
				$hourbody[]=array('1'=>$timeMatrix->showEntry12Hour(strftime('%D',$date),1,'S'),'2'=>$timeMatrix->showEntry12Hour(strftime('%D',$date),1,'F'));
			else $hourbody[]=array('1'=>" ",'2'=>" ");
			if ($timeMatrix->entryExists(strftime('%D',$date),2))
				$hourbody[]=array('1'=>$timeMatrix->showEntry12Hour(strftime('%D',$date),2,'S'),'2'=>$timeMatrix->showEntry12Hour(strftime('%D',$date),2,'F'));
			else $hourbody[]=array('1'=>" ",'2'=>" ");
			$altypos=$pdf->ezTable($hourbody,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>78.56,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>$xpos,'xOrientation'=>'right',
					   'cols'=>array('1'=>array('width'=>39.28,'justification'=>'right'),'2'=>array('width'=>39.28,'justification'=>'right')) ));
		
			$totalrowheaders1[]= number_format($timeMatrix->showDailyTotal(strftime('%D',$date)),2);
		}
		$pdf->ezSetY($altypos-5);
		$pdf->selectFont('./fonts/Helvetica.afm');
		$ypos=$pdf->ezText('Daily hrs:', 10, array('justification'=>'left'));
		$pdf->ezSetY($ypos+13.5);
		$pdf->selectFont('./fonts/Helvetica-Bold.afm');
		$totalheaders[]=$totalrowheaders1;
		//$totalheaders[]=array('1'=>'Daily Hrs:     ','2'=>'     ','3'=>'     ','4'=>'     ','5'=>'     ','6'=>'     ','7'=>'     ');
		$ypos=$pdf->ezTable($totalheaders,'','',array('showLines'=>0,'showHeadings'=>0,'width'=>550,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>25,'xOrientation'=>'right',
					   'cols'=>array('0'=>array('width'=>78.56,'justification'=>'right'),'1'=>array('width'=>78.56,'justification'=>'right'),'2'=>array('width'=>78.56,'justification'=>'right'),'3'=>array('width'=>78.56,'justification'=>'right'),'4'=>array('width'=>78.56,'justification'=>'right'),'5'=>array('width'=>78.56,'justification'=>'right'),'6'=>array('width'=>78.56,'justification'=>'right'),
					   ) ));
		$pdf->ezSetY($ypos-5);
		$ypos=$pdf->ezText('Total hours worked for the last week ending: ', 10, array('justification'=>'left','aleft'=>22));
		$pdf->ezSetY($ypos+14);
		$pdf->selectFont('./fonts/Helvetica-Bold.afm');
		$ypos=$pdf->ezText(number_format($timeMatrix->showWeeklyTotal($workPeriod->week+0),2), 12, array('justification'=>'left','aleft'=>235));
		
		$pdf->ezSetY($ypos-25);
		//week 2
		$pdf->ezTable($dayheaders,'','',array('showLines'=>0,'showHeadings'=>0,'width'=>550,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>25,'xOrientation'=>'right',
					   'cols'=>array('1'=>array('width'=>78.56,'justification'=>'center'),'2'=>array('width'=>78.56,'justification'=>'center'),'3'=>array('width'=>78.56,'justification'=>'center'),'4'=>array('width'=>78.56,'justification'=>'center'),'5'=>array('width'=>78.56,'justification'=>'center'),'6'=>array('width'=>78.56,'justification'=>'center'),
					   '7'=>array('width'=>78.56,'justification'=>'center')) ));
		$ypos=$pdf->ezTable($hourheaders,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>550,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>25,'xOrientation'=>'right',
					   'cols'=>array('1'=>array('width'=>39.28,'justification'=>'center'),'2'=>array('width'=>39.28,'justification'=>'center'),'3'=>array('width'=>39.28,'justification'=>'center'),'4'=>array('width'=>39.28,'justification'=>'center'),'5'=>array('width'=>39.28,'justification'=>'center'),'6'=>array('width'=>39.28,'justification'=>'center'),
					   '7'=>array('width'=>39.28,'justification'=>'center'),'8'=>array('width'=>39.28,'justification'=>'center'),'9'=>array('width'=>39.28,'justification'=>'center'),'10'=>array('width'=>39.28,'justification'=>'center'),'11'=>array('width'=>39.28,'justification'=>'center'),'12'=>array('width'=>39.28,'justification'=>'center'),'13'=>array('width'=>39.28,'justification'=>'center'),
					   '14'=>array('width'=>39.28,'justification'=>'center')) ));
		for ($day=7, $date=strtotime(strftime('%D',$workPeriod->startDate)." + $day days"),$xpos=25;$day<=13;$day++,$date=strtotime(strftime('%D',$workPeriod->startDate)." + $day days"),$xpos+=78.56)
   		{
			$pdf->ezSetY($ypos);
			$hourbody=NULL;
			if ($timeMatrix->entryExists(strftime('%D',$date),0))
        		$hourbody[]=array('1'=>$timeMatrix->showEntry12Hour(strftime('%D',$date),0,'S'),'2'=>$timeMatrix->showEntry12Hour(strftime('%D',$date),0,'F'));
			else $hourbody[]=array('1'=>" ",'2'=>" ");
			if ($timeMatrix->entryExists(strftime('%D',$date),1))
				$hourbody[]=array('1'=>$timeMatrix->showEntry12Hour(strftime('%D',$date),1,'S'),'2'=>$timeMatrix->showEntry12Hour(strftime('%D',$date),1,'F'));
			else $hourbody[]=array('1'=>" ",'2'=>" ");
			if ($timeMatrix->entryExists(strftime('%D',$date),2))
				$hourbody[]=array('1'=>$timeMatrix->showEntry12Hour(strftime('%D',$date),2,'S'),'2'=>$timeMatrix->showEntry12Hour(strftime('%D',$date),2,'F'));
			else $hourbody[]=array('1'=>" ",'2'=>" ");
			$altypos=$pdf->ezTable($hourbody,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>78.56,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>$xpos,'xOrientation'=>'right',
					   'cols'=>array('1'=>array('width'=>39.28,'justification'=>'right'),'2'=>array('width'=>39.28,'justification'=>'right')) ));
		
			$totalrowheaders2[]= number_format($timeMatrix->showDailyTotal(strftime('%D',$date)),2);
		}
		$pdf->ezSetY($altypos-5);
		$pdf->selectFont('./fonts/Helvetica.afm');
		$ypos=$pdf->ezText('Daily hrs:', 10, array('justification'=>'left'));
		$pdf->ezSetY($ypos+13.5);
		$pdf->selectFont('./fonts/Helvetica-Bold.afm');
		$totalheaders2[]=$totalrowheaders2;
		$ypos=$pdf->ezTable($totalheaders2,'','',array('showLines'=>0,'showHeadings'=>0,'width'=>550,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>25,'xOrientation'=>'right',
					   'cols'=>array('0'=>array('width'=>78.56,'justification'=>'right'),'1'=>array('width'=>78.56,'justification'=>'right'),'2'=>array('width'=>78.56,'justification'=>'right'),'3'=>array('width'=>78.56,'justification'=>'right'),'4'=>array('width'=>78.56,'justification'=>'right'),'5'=>array('width'=>78.56,'justification'=>'right'),'6'=>array('width'=>78.56,'justification'=>'right'),
					   ) ));
		$pdf->ezSetY($ypos-5);
		$ypos=$pdf->ezText('Total hours worked for the last week ending: ', 10, array('justification'=>'left','aleft'=>22));
		$pdf->ezSetY($ypos+14);
		$pdf->selectFont('./fonts/Helvetica-Bold.afm');
		$ypos=$pdf->ezText(number_format($timeMatrix->showWeeklyTotal($workPeriod->week+1),2), 12, array('justification'=>'left','aleft'=>235));
		$pdf->ezSetY($ypos+13.5);
		$pdf->selectFont('./fonts/Helvetica.afm');
		$ypos=$pdf->ezText("<b>Total hours worked:</b>", 10, array('justification'=>'right','aright'=>530));
		$pdf->ezSetY($ypos+14.0);
		$pdf->selectFont('./fonts/Helvetica-Bold.afm');
		$ypos=$pdf->ezText(number_format($timeMatrix->showPeriodTotal(),2), 12, array('justification'=>'right','aright'=>565));

		
		$pdf->ezSetY($ypos-35);
		$sumheaders[]=array('1'=>'Hours Worked','2'=>'Hourly Pay','3'=>"Account Number\n(FAS)",'4'=>'Labor Object #','5'=>'Project #','6'=>"Work Against\nPosition #");
		
		$pdf->ezTable($sumheaders,'','',array('showLines'=>1,'showHeadings'=>0,'width'=>550,'shaded'=>2,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>25,'xOrientation'=>'right',
					   'cols'=>array('1'=>array('width'=>91.66,'justification'=>'center'),'2'=>array('width'=>91.66,'justification'=>'center'),'3'=>array('width'=>91.66,'justification'=>'center'),'4'=>array('width'=>91.66,'justification'=>'center'),'5'=>array('width'=>91.66,'justification'=>'center'),'6'=>array('width'=>91.66,'justification'=>'center')) ));
		$blankheaders[]=array('1'=>number_format($timeMatrix->showPeriodTotal(),2),'2'=>"\n",'3'=>"\n",'4'=>"\n",'5'=>"\n",'6'=>"\n");
		$blankheaders[]=array('1'=>"\n",'2'=>"\n",'3'=>"\n",'4'=>"\n",'5'=>"\n",'6'=>"\n");
		$pdf->ezTable($blankheaders,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>550,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>25,'xOrientation'=>'right',
					   'cols'=>array('1'=>array('width'=>91.66,'justification'=>'center'),'2'=>array('width'=>91.66,'justification'=>'center'),'3'=>array('width'=>91.66,'justification'=>'center'),'4'=>array('width'=>91.66,'justification'=>'center'),'5'=>array('width'=>91.66,'justification'=>'center'),'6'=>array('width'=>91.66,'justification'=>'center')) ));
		$pdf->selectFont('./fonts/Helvetica.afm');
		$boxquote="<b>Signatures</b>\nHave you worked for any other University department or State government agency during this pay period? <u>     </u>yes <u>     </u>no\n";
		$boxquote.="If yes, indicate department/agency <u>                                                            </u>\n\n";
		$boxquote.="I certify that all hours/flat rate amounts have been recorded accurately.\n\n";
		$boxquote.="<u>                                                                          </u> Date:<u>               </u>  <u>                                                                     </u>Date:<u>              </u>\n";
		$boxquote.="  Supervisor's Signature                                                                Employee's Signature";
		$bigbox[]=array('1'=>$boxquote);
		$pdf->ezTable($bigbox,'','',array('showLines'=>2,'showHeadings'=>0,'width'=>550,'shaded'=>0,'shadeCol'=>array(.8,.8,.8),'shadeCol2'=>array(.8,.8,.8),'xPos'=>25,'xOrientation'=>'right'));
		$pdf->selectFont('./fonts/Helvetica-Bold.afm');
		$ypos=$pdf->ezText('Records must be maintained in the department for four years. Do not forward to Payroll. Please make any necessary corrections or changes.', 8, array('justification'=>'left','aleft'=>22));
		$pdf->ezSetY($ypos-10);
		$ypos=$pdf->ezText('Special Notes',10,array('justification'=>'center'));
		$pdf->selectFont('./fonts/Helvetica.afm');
		$notestring="1.\tPaychecks may be picked up on payday between 8:00 a.m. to 5:00 p.m.\n";
		$notestring.="2.\tAll checks not picked up on payday will be kept in our office for 30 days.  If you wish to have your check mailed out, please supply us with a self-addressed stamped envelope.\n";
		$notestring.="3.\tWritten authorization is required if you want someone else to pick up your paycheck.\n";
		$notestring.="4.\tTime-sheets must be turned into the ECE Main office by 5:00 p.m. the day after payday or you may not be paid until the following pay date.\n";
		$notestring.="    <b>Please note:  This time may change due to Payroll deadlines and University Holidays/closings.</b>\n";
		$notestring.="5.\tAll partial hours worked must be rounded off to the closest ¼ hour.  For example: 8 hours 10 minutes = 8.25 not 8.1; similarly, 8 hours 5 minutes = 8.00 not 8.05.\n";
		$notestring.="6.\tDo not send your time-sheet by courier/campus mail.\n";
		$notestring.="7.\tUse black or blue ink to complete your time-sheet.  Time-sheets in pencil are unacceptable.\n";
		$notestring.="8.\tTime-sheets must be signed by you and your supervisor.\n";
		$notestring.="9.\tTime-sheets with missing signatures will not be processed.  You must submit original time-sheets as copies will not be accepted.\n";
		$notestring.="10.\t<b>Any and all errors, scratchouts, etc. must be corrected and initialed by both you and your supervisor.\n      For example: if you make two errors, both errors must be corrected and initialed by both you and your supervisor.</b>";
		$ypos=$pdf->ezText($notestring, 6.5, array('justification'=>'left','aleft'=>22));
				
		
		$pdf->ezStream();
		return;
?>