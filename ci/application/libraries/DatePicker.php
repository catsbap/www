<?php


//this code is used to set up the previous and next buttons in the UI
class DatePicker{
	function show_picker() {
		$obj =& get_instance();
		$base = $obj->config->item('base_url');
		$obj->load->helper('url');
		$obj->load->library('session');
		//get out all of the variables from the URL, yes, they should be in segments.
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = "clients";
		}
		if (isset($_GET['fromdate'])) {
			$fromdate = $_GET['fromdate'];
		} else {
			$fromdate = "2014-1-1";
		}
		if (isset($_GET['todate'])) {
			$todate = $_GET['todate'];
		} else {
			$todate = "2014-1-1";
		}
		//begin type
		if (isset($_GET['type'])) {
			$type = $_GET['type'];
		} else {
			$type= "week";
		}
		//these values will be appended to the anchor if they are in the url,
		//indicating the user came from the individual report levels.
		$report_var = "";
		$report_val = "";
		
		if (isset($_GET['client_id'])) {
			$client_id = $_GET['client_id'];
			$report_var = '&client_id=';
			$report_val = $client_id;
		} else {
			$client_id = "";
		}
		if (isset($_GET['project_id'])) {
			$project_id = $_GET['project_id'];
			$report_var = '&project_id=';
			$report_val = $project_id;
		} else {
			$project_id = "";
		}
		if (isset($_GET['task_id'])) {
			$task_id = $_GET['task_id'];
			$report_var = '&task_id=';
			$report_val = $task_id;
		} else {
			$task_id = "";
		}
		if (isset($_GET['person_id'])) {
			$person_id = $_GET['person_id'];
			$report_var = '&person_id=';
			$report_val = $person_id;
		} else {
			$person_id = "";
		}
		//there are slicker ways to do this
		$controller = $obj->uri->segment(1); 
		$view = $obj->uri->segment(2);
		
		if ($type == "semimonthly") {
			//turn the fromdate from url into object
			$date = new DateTime($fromdate);
			//get the current day
			$current_day = date_format(new DateTime($fromdate), 'd');
			//get the first day of the semi month
			$first_day_of_month = $date->modify('first day of this month');
			//get the last day of the semi month
			$date = new DateTime($fromdate);
			$last_day_of_month = $date->modify('last day of this month');
			//get the middle date, which will determine if we need to rewind
			//or fast forward the date.
			//never mind this, it's a business rule. Instead, make it the 16th.
			$middle_day = 16;
			//build up the middle date.
			$year_of_month = date_format($date->modify('last day of this month'), 'Y');
			$month_of_month = date_format($date->modify('last day of this month'), 'm');
			$day_of_month = date_format($date->modify('last day of this month'), 'd');
			//this code sets up the current semimonthly timeframe
			//the user is viewing.
			if ($current_day >= $middle_day) {
				//we are in the second half of the month. 				
				
				///CURRENT DATE
				//$date = new DateTime($fromdate);
				//$fromdate = date_format($date->modify('first day of this month'), 'Y-m-d');
				//echo "<br>current from date you're looking at here<br>";
				//echo $fromdate;
				$todate = $year_of_month . "-" . $month_of_month . "-" . ($middle_day);
				$fromdate = date_format($last_day_of_month, 'Y-m-d');
				//this is always the 15th of the month
				//$todate = $year_of_month . "-" . $month_of_month . "-" . ($middle_day-1);
				//echo "<br>current todate you're looking at<br>";
				//echo $todate;
				//echo $fromdate;
				
				
				//PREVIOUS DATE (rewind)
				$date = new DateTime($fromdate);
				//this is always the 1st of the month.
				$picker_fromdate = date_format($date->modify('first day of this month'), 'Y-m-d');
				//this is always the 15th of the month.
				$picker_todate = $year_of_month . "-" . $month_of_month . "-" . ($middle_day-1);
				//echo "<br>previous picker fromdate<br>";
				//echo $picker_fromdate;
				//echo "<br>previous picker todate<br>";
				//echo $picker_todate;
				$picker = anchor("$base/index.php/$controller/$view?fromdate=$picker_fromdate&todate=$picker_todate&page=$page&type=$type$report_var$report_val", "<<<<< Previous ||");
				
				
				
				//NEXT DATE
				$date = new DateTime($fromdate);
				//this is always the first day of next month
				$picker_fromdate = date_format($date->modify('first day of next month'), 'Y-m-d');
				$date = new DateTime($picker_fromdate);
				//this is always the 15th of next month, so add 15 days to the first day of next month.
				$picker_todate = date_format(date_add($date, date_interval_create_from_date_string($middle_day -2 . ' days')), 'Y-m-d');
				//echo "<br>next picker fromdate<br>";
				//echo $picker_fromdate;
				//echo "<br>next picker todate<br>";
				//echo $picker_todate;
				$picker .= anchor("$base/index.php/$controller/$view?fromdate=$picker_fromdate&todate=$picker_todate&page=$page&type=$type$report_var$report_val", " Next >>>>>>");	
			
			} else {
				//we are in the first half of the month. Rewind.
				//fromdate is now the last half of the previous month.
				
				///CURRENT DATE
				//$date = new DateTime($fromdate);
				//$fromdate = date_format($date->modify('first day of this month'), 'Y-m-d');
				//echo "<br>current from date you're looking at here<br>";
				//echo $fromdate;
				//this is always the 15th of the month
				//$todate = $year_of_month . "-" . $month_of_month . "-" . ($middle_day-1);
				//echo "<br>current todate you're looking at<br>";
				//echo $todate;
				
				//PREVIOUS DATE
				//this is always the last day of the previous month.
				$date = new DateTime($fromdate);
				//this is always the 15th day of last month
				$date = $date->modify('first day of last month');
				$picker_fromdate = date_format(date_add($date, date_interval_create_from_date_string($middle_day -1 . ' days')), 'Y-m-d');
				$date = new DateTime($picker_fromdate);
				//this is always the last day of last month.
				$picker_todate = date_format($date->modify('last day of this month'), 'Y-m-d');
				//echo "<br>previous picker fromdate<br>";
				//echo $picker_fromdate;
				//echo "<br>previous picker todate<br>";
				//echo $picker_todate;
				$picker = anchor("$base/index.php/$controller/$view?fromdate=$picker_fromdate&todate=$picker_todate&page=$page&type=$type$report_var$report_val", "<<<<< Previous ||");
				
				
				//NEXT DATE
				$date = new DateTime($fromdate);
				//this is always the 16th of this month.
				$date = $date->modify('first day of this month');
				$picker_fromdate = date_format(date_add($date, date_interval_create_from_date_string(($middle_day -1) . ' days')), 'Y-m-d');

				//this is always the last day of this month.
				$picker_todate = date_format($date->modify('last day of this month'), 'Y-m-d');
				//echo "<br>next picker fromdate<br>";
				//echo $picker_fromdate;
				//echo "<br>next picker todate<br>";
				//echo $picker_todate;
				$picker .= anchor("$base/index.php/$controller/$view?fromdate=$picker_fromdate&todate=$picker_todate&page=$page&type=$type$report_var$report_val", " Next >>>>>>");
			}
		} elseif ($type == 'month') {
			$date = new DateTime($todate);
			$date->modify('-1 month');
			$date = $date->format('Y-m-d');
			$todate = $date;
			$date = new DateTime($fromdate);
			$date->modify('-1 month');
			$date = $date->format('Y-m-d');
			$fromdate = $date;
			//these are the links for the previous week
			//echo $fromdate;
			//echo $todate;
		    $picker = anchor("$base/index.php/$controller/$view?fromdate=$fromdate&todate=$todate&page=$page&type=$type$report_var$report_val", "<<<<< Previous ||");
			$date = new DateTime($todate);
			$date->modify('+2 months');
			$date = $date->format('Y-m-d');
			$todate = $date;
			$date = new DateTime($fromdate);
			$date->modify('+2 months');
			$date = $date->format('Y-m-d');
			$fromdate = $date;
			$picker .= anchor("$base/index.php/$controller/$view?fromdate=$fromdate&todate=$todate&page=$page&type=$type$report_var$report_val", " Next >>>>>>");
		} elseif ($type == 'year') {
			$date = new DateTime($todate);
			$date->modify('-1 year');
			$date = $date->format('Y-m-d');
			$todate = $date;
			$date = new DateTime($fromdate);
			$date->modify('-1 year');
			$date = $date->format('Y-m-d');
			$fromdate = $date;
			//these are the links for the previous week
			//echo $fromdate;
			//echo $todate;
		    $picker = anchor("$base/index.php/$controller/$view?fromdate=$fromdate&todate=$todate&page=$page&type=$type$report_var$report_val", "<<<<< Previous ||");
			$date = new DateTime($todate);
			$date->modify('+2 years');
			$date = $date->format('Y-m-d');
			$todate = $date;
			$date = new DateTime($fromdate);
			$date->modify('+2 years');
			$date = $date->format('Y-m-d');
			$fromdate = $date;
			$picker .= anchor("$base/index.php/$controller/$view?fromdate=$fromdate&todate=$todate&page=$page&type=$type$report_var$report_val", " Next >>>>>>");
		} elseif ($type == 'quarter') { 
			//get the current quarter date and rewind it.
			$date = new DateTime($_GET['todate']);
			//get the month, which will tell us what quarter we're in.
			$month = $date->format('n') ;
			//previous quarter code
			if ($month < 4) {
				//echo "we're in the first quarter going to the fourth quarter";
                $todate = $date->modify('last day of december ' . $date->format('Y'));
                $todate->modify('-1 year');
                $todate = $todate->format('Y-m-d');
				$fromdate = $date->modify('first day of  october ' . $date->format('Y'));
	         } elseif ($month > 9) {
            	//echo "we're in the fourth going to third quarter";
            	$date = new DateTime($_GET['todate']);
				$todate = $date->modify('last day of september ' . $date->format('Y'));
                $todate = $todate->format('Y-m-d');
                $fromdate = $date->modify('first day of july ' . $date->format('Y'));
            } elseif ($month > 6 && $month < 10) {
            	//echo "we're in the third going to second quarter";
            	$date = new DateTime($_GET['todate']);
                $todate = $date->modify('last day of june ' . $date->format('Y'));
                $todate = $todate->format('Y-m-d');
                $fromdate = $date->modify('first day of april ' . $date->format('Y'));
            } elseif ($month > 3 && $month < 7) {
            	//echo "we're in the second going to first quarter";
            	$date = new DateTime($_GET['todate']);
                $todate = $date->modify('last day of march ' . $date->format('Y'));
                $todate = $todate->format('Y-m-d');
                $fromdate = $date->modify('first day of january ' . $date->format('Y'));
            }
			$fromdate = $fromdate->format('Y-m-d');
			$picker = anchor("$base/index.php/$controller/$view?fromdate=$fromdate&todate=$todate&page=$page&type=$type$report_var$report_val", "<<<<< Previous ||");
			//get the current quarter date and ff it.
			$date = new DateTime($_GET['todate']);
			//get the month, which will tell us what quarter we're in.
			$month = $date->format('n') ;
			//previous quarter code
			if ($month < 4) {
				//echo "we're in the first quarter going to the second quarter";
                $todate = $date->modify('last day of june ' . $date->format('Y'));
                $todate = $todate->format('Y-m-d');
				$fromdate = $date->modify('first day of  april ' . $date->format('Y'));
	         } elseif ($month > 9) {
            	//echo "we're in the fourth going to first quarter";
            	$date = new DateTime($_GET['todate']);
				$date = $date->modify('+1 year');
				$todate = $date->modify('last day of march ' . $date->format('Y'));
                $todate = $todate->format('Y-m-d');
                $fromdate = $date->modify('first day of  january' . $date->format('Y'));
            } elseif ($month > 6 && $month < 10) {
            	//echo "we're in the third going to fourth quarter";
            	$date = new DateTime($_GET['todate']);
                $todate = $date->modify('last day of december ' . $date->format('Y'));
                $todate = $todate->format('Y-m-d');
                $fromdate = $date->modify('first day of october ' . $date->format('Y'));
            } elseif ($month > 3 && $month < 7) {
            	//echo "we're in the second going to third quarter";
            	$date = new DateTime($_GET['todate']);
                $todate = $date->modify('last day of september ' . $date->format('Y'));
                $todate = $todate->format('Y-m-d');
                $fromdate = $date->modify('first day of july ' . $date->format('Y'));
            }
			$fromdate = $fromdate->format('Y-m-d');
			$picker .= anchor("$base/index.php/$controller/$view?fromdate=$fromdate&todate=$todate&page=$page&type=$type$report_var$report_val", " Next >>>>>>");
		//default to week
		} else {
			$date = new DateTime($todate);
			$date->modify('-1 week');
			$date = $date->format('Y-m-d');
			$todate = $date;
			$date = new DateTime($fromdate);
			$date->modify('-1 week');
			$date = $date->format('Y-m-d');
			$fromdate = $date;
			//these are the links for the previous week
			//echo $fromdate;
			//echo $todate;
			$picker = anchor("$base/index.php/$controller/$view?fromdate=$fromdate&todate=$todate&page=$page&type=$type$report_var$report_val", "<<<<< Previous ||");
			$date = new DateTime($todate);
			$date->modify('+2 week');
			$date = $date->format('Y-m-d');
			$todate = $date;
			$date = new DateTime($fromdate);
			$date->modify('+2 week');
			$date = $date->format('Y-m-d');
			$fromdate = $date;
			$picker .= anchor("$base/index.php/$controller/$view?fromdate=$fromdate&todate=$todate&page=$page&type=$type$report_var$report_val", " Next >>>>>>");
		}
		//add the client_id, project_id, task_id or person_id to the picker if they are in the url.
		//error_log($picker);
		return $picker;
	}
}
?>