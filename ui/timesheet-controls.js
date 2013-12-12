;
var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
var monthsNarrow = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
var timerOn = false;
var $editEntry = $( '<a class="ui-button edit-entry">Edit</a>' )
	.button({
		icons: {
			primary: "ui-icon-pencil"
		},
		text: false
	})
	.click( function( evt ) {
		$( '#add-ts-entry-modal' ).dialog( 'open' );
		
		evt.preventDefault();
	});



var $timerBtn = $( '<a class="ui-button timer">Start</a>' )
	.button({
		icons: {
			primary: "ui-icon-clock"
		}
	})
	.click( function( evt ) {
		var now = new Date();
		if ( $( this ).data( 'then' ) ) {
			console.log( "stop timer: " + now.getTime() );
			var then = parseInt( $( this ).data( 'then' ).time );
			var hours = numeral( $( this ).data( 'then' ).hours + ":00" );
			var diff = now.getTime() - then;
			hours.add( diff / 1000 );
			$( this ).siblings( 'span' ).text( decimalToTime( hours.value() / 60 / 60 ) );
			$( this ).data( 'timerOn', false );
			$( this ).data( 'then', null );
			saveTimesheet( $( '#timesheet-tasks-list' ) );
			$( this ).find( '.ui-button-text' )
				.text( "Start" );
			
		} else {
			console.log( "start timer: " + now.getTime() );
			$( this ).data( 'then', { time: now.getTime(), hours: $( this ).siblings( 'span' ).text() } );
			$( this ).data( 'timerOn', true );
			//console.log( $( this ) );
			$( this ).siblings( 'span' ).text( function( index, text ) {
				return text + ":00";
			})
			timer( $( this ) );
			$( this ).find( '.ui-button-text' )
				.text( "Stop" );
		}
		evt.preventDefault();
	});

function timer( elem ) {
	var $ticker = $( elem ).siblings( 'span' );
	if ( $( elem ).data( 'timerOn' ) ) {
		var uptick = numeral( $ticker.text() );
		uptick.add( 1 );
		$ticker.text( uptick.format( '00:00' ) )
		
		setTimeout( function() {
				timer( $( elem ) );
			}, 1000 );	
	}
}

function cancelTimer() {
	$( 'a.timer' ).data( 'timerOn', false );
	
}

var TimesheetItem = function( timesheetItemId, projectId, taskId, personId, timesheetDate, timesheetHours, timesheetNotes ) {
	var tsItem = {
		timesheet_item_id: timesheetItemId,
		project_id: projectId,
		task_id: taskId,
		person_id: personId,
		timesheet_date: timesheetDate,
		timesheet_hours: timesheetHours,
		timesheet_notes: timesheetNotes
	}
	
	return tsItem;
}

function getTimesheet( id, week ) {
	var timesheet = {};
	var startDate = week.start.getFullYear() + "-" + (week.start.getMonth() + 1) + "-" + week.start.getDate();
	var endDate = week.end.getFullYear() + "-" + (week.end.getMonth() + 1) + "-" + week.end.getDate();
	
	var getData = {
		func: "returnTimesheetJSON",
		id: id,
		collection: "person",
		startDate: startDate,
		endDate: endDate
	}
	
	$( "#timesheet-tasks-list" )
		.find( 'tbody' )
		.empty()
		.end()
		.find( 'tfoot td.total, tfoot td.week-total' )
		.text( "" );
	
	$.get( "returnJSON.php", getData )
		.done( function( data ) {
			//console.log( data );
			timesheet = $.parseJSON( data );
			$( "#timesheet-tasks-list" ).data( "timesheet_id", timesheet[0].timesheet_id );
			//console.log( "saved timesheet_id: " + $( "#timesheet-tasks-list" ).data( "timesheet_id" ) );
			var tsItems = timesheet[0].timesheet_items;
			
			if ( timesheet[0].timesheet_items.length > 0 ) {
				//console.log("we have items to display");
				
				for ( var i = 0; i < tsItems.length; i+=7 ) {
					var rowInfo = {
						timesheet_item_id: tsItems[i].timesheet_item_id,
						task_name: tsItems[i].task_name,
						task_id: tsItems[i].task_id,
						project_name: tsItems[i].project_name,
						project_id: tsItems[i].project_id,
						person_id: tsItems[i].person_id
					}
					var days = [];
					for ( var d = i; d < i + 7; d++ ) {
						days[d % 7] = {
							timesheet_date: tsItems[d].timesheet_date,
							timesheet_hours: tsItems[d].timesheet_hours,
							timesheet_notes: tsItems[d].timesheet_notes
						}
					}
					rowInfo.timesheet_days = days;
					addTimesheetRow( rowInfo );
					
				}
				
			} else {
				console.log("no items to display yet");
			}
			toggleTimesheetView( week.currentSelected );
			//console.log("done");
		})
		.fail( function( data ) {
			console.log("fail: " + data);
		});
		
		
	//calculateTotals();
	return timesheet;
}

function saveTimesheet( elem, deleteRow ) {
	var $tsTable = $( elem ).find( 'tbody' );
	var tsId = $( "#timesheet-tasks-list" ).data( "timesheet_id" );
	console.log("saving to timesheet_id: " + tsId )
	var personId = $( "#timesheet-tasks-list" ).data( "person_id" );
	var dates = [];
	var thisWeek = getWeekBookends( $( "#timesheet-tasks-list" ).data( "timesheet_start" ) );
	for ( var d = 0; d < 7; d++ ) {
		dates[d] = new Date();
		dates[d].setDate( thisWeek.start.getDate() + d );
	}
	//console.log(dates);
	var tsItems = [];
	
	$tsTable.find( 'input' ).not( '.remove' )
		.each( function( index, elem ) {
			var taskData = $( elem ).attr( "name" ).split( "_" );
			var projId = taskData[0].substring(1);
			var taskId = taskData[1].substring(1);
			var day = taskData[2].substring(1); //may need to force conversion to number
			var itemDate = dates[taskData[2].substring(1)].getFullYear() + "-" + (dates[taskData[2].substring(1)].getMonth() + 1) + "-" + dates[taskData[2].substring(1)].getDate();
			tsItems.push( new TimesheetItem(
				tsId,
				taskData[0].substring(1),
				taskData[1].substring(1),
				personId,
				itemDate,
				timeToDecimal( $( elem ).val() ),
				""
			)); //need to do something about timesheet notes. Where are they saved? sending empty string for now.
		})
	console.log(tsItems);
	if ( deleteRow ) {
		var deleteItems = [];
		$( deleteRow ).find( 'input' )
			.each( function( index, elem ) {
				var taskData = $( elem ).attr( "name" ).split( "_" );
				var projId = taskData[0].substring(1);
				var taskId = taskData[1].substring(1);
				var day = taskData[2].substring(1); //may need to force conversion to number
				var itemDate = dates[taskData[2].substring(1)].getFullYear() + "-" + (dates[taskData[2].substring(1)].getMonth() + 1) + "-" + dates[taskData[2].substring(1)].getDate();
					deleteItems.push( new TimesheetItem(
						tsId,
						taskData[0].substring(1),
						taskData[1].substring(1),
						personId,
						itemDate,
						timeToDecimal( $( elem ).val() ),
						""
					)); //need to do something about timesheet notes. Where are they saved? sending empty string for now.
			})
	} else {
		var deleteItems = 0;
	}
	//console.log(deleteItems);
	
	$.post( "timesheet.php", {
			func: "saveTimesheet",
			proc_type: "A",
			timesheetItems: JSON.stringify( tsItems ),
			deleteItems: JSON.stringify( deleteItems )
		})
		.done( function( data ) {
			console.log("done: " + data);
		})
		.fail( function( data ) {
			console.log("fail: " + data);
		});
	//console.log(tsItems);
	
}

function getTasksForProject( id ) {
	$.get( "returnJSON.php", {
			func: "returnTasksJSON" ,
			id: id,
			collection: "project"
		})
		.done( function( data ) {
			//console.log("done: " + data);
		})
		.fail( function( data ) {
			console.log("fail: " + data);
		})
		.success( function( data ) {
			var $taskSelect = $( "#task-name" )
				.empty();
			
			data = $.parseJSON( data );
			//console.log(data.length);
			
			for ( var i = 0; i < data.length; i++ ) {
				//console.log(data[i].task_name);
				$taskSelect.append( function() {
					return $( "<option>" )
						.val( data[i].task_id )
						.text( data[i].task_name );
				})
			}
		})
}

function addTimesheetRow( row ) {
	//console.log(row.timesheet_days.length);
	$timeInput = $( '<input type="text" class="time-entry-input" />' );
	$deleteRow = $( '<a href="#" class="ui-button delete">x</a>' );
	$table = $( "#timesheet-tasks-list tbody" );
	rowTotal = 0;
	$newRow = $( '<tr>' )
		.append( "<td>" + row.project_name + "<br />" + row.task_name + "</td>" )
		.append( function() {
			var weekTDs = [];
			for ( var i = 0; i < 7; i++ ) {
				weekTDs[i] = $( '<td class="day">' )
					.append( function() {
						var inputName = "p" + row.project_id + "_t" + row.task_id + "_d" + i;
						return $timeInput.clone()
							.attr( "name", inputName )
							.val( function() {
								if ( row.timesheet_days ) {
									rowTotal += Number( row.timesheet_days[i].timesheet_hours );
									return decimalToTime( row.timesheet_days[i].timesheet_hours );
								} else {
									return 0;
								}
							})
							.change( function( evt ) {
								calculateTotals( $( this ) );
							})
							.blur( function( evt ) {
								$( this ).val( function( val ) {
									return decimalToTime( $( this ).val() );
								});
								calculateTotals( $( this ) );
							});
					})
			}
			return weekTDs;
		})
		.append( '<td class="total">' + decimalToTime( rowTotal ) + '</td>' )
		.append( function() {
			return $deleteRow
				.clone( true )
				.button({
					icons: {
						primary: "ui-icon-close"
					},
					text: false
				})
				.click(function( evt ) {
					$( this ).parents( 'tr' ).find( 'input' ).addClass( 'remove' );
					saveTimesheet( $( '#timesheet-tasks-list' ),  $( this ).parents( 'tr' ) );
					$( this ).parents( 'tr' )
						.remove();
					
					evt.preventDefault();
				})
		});
		
	$table
		.append( $newRow );
	calculateTotals( );
		
}

function decimalToTime( dec ) {
	if ( (typeof dec == "string" ) && ( dec.indexOf( ":" ) >= 0 ) ) {
		var seconds = numeral( dec + ":00" ).value()
	} else {
		var seconds = parseFloat( dec ) * 60 * 60;
	}
	time = numeral( seconds ).format( "00:00" );
	return time.substring( 0, time.lastIndexOf( ":" ) );
}

function timeToDecimal( time ) {
	console.log( time );
	if ( (typeof time == "string" ) && ( time.indexOf( ":" ) >= 0 ) ) {
		var num = numeral( time + ":00" ).value();
		num = num / 60 / 60;
	} else {
		var num = time;
	}
	console.log("time to dec: " + num + " type " + typeof num );
	return num;
}


function calculateTotals( ) {
	var rowTotal = 0;
	var colTotals = [0, 0, 0, 0, 0, 0, 0];
	var grandTotal = 0
	
	$( "#timesheet-tasks-list" )
		.find( 'tbody tr' )
		.each( function( index, elem ){
			$( elem ).find( 'input' )
				.each( function( index, elem ){
					rowTotal += numeral( $( elem ).val() + ":00" ).value();
					colTotals[ index ] += numeral( $( elem ).val() + ":00" ).value();
				})
			.end()
			.find( '.total' )
			.text( function() {
				var weekTotal = numeral( rowTotal ).format( "00:00" );
				grandTotal += rowTotal;
				rowTotal = 0;
				return weekTotal.substring( 0, weekTotal.lastIndexOf( ":00" ) );
			})
		})
		.end()
		.find( "tfoot td.day" )
		.each( function( index, elem ){
			$( elem ).text( function() {
				var dayTotal = numeral( colTotals[index] ).format( "00:00" )
				return dayTotal.substring( 0, dayTotal.lastIndexOf( ":00" ) );
			})
		})
		.end()
		.find( '.week-total' )
		.text( function() {
			var gTot = numeral( grandTotal ).format( "00:00" )
			return gTot.substring( 0, gTot.lastIndexOf( ":00" ) );
		});
}

function getWeekBookends( date ) {
	var bookends = {};
	var workWeekStart = 1; //Work week starts on Monday
	var workWeekLength = 6; //Last day of the week (0..6);
	var weekStart = new Date();
	var weekEnd = new Date();

	if ( !date ) {
		var date = new Date();
	} else {
		date = new Date( date );
	}
	
	if ( date.getDay() == 1 ) {
		weekStart = date;
	} else if ( date.getDay() == 0 ) {
		weekStart.setDate( date.getDate() - ( date.getDay() + 7 ) + workWeekStart ); //compensating for Monday start of work week.
	} else {
		weekStart = new Date( date.getFullYear(), date.getMonth(), ( date.getDate() - date.getDay() + workWeekStart) );
	}
	weekEnd = new Date(weekStart);
	weekEnd.setDate( weekEnd.getDate() + workWeekLength );

	bookends = {
		start: weekStart,
		end: weekEnd,
		currentSelected: date
	}
	
	updatePageHeaderDate( bookends, "week" );
	
	//add dates to table header
	var today = new Date();
	today.setHours( 0, 0, 0, 0 );
	$( "#timesheet-tasks-list th.day" ).not( '.total' )
		.each( function( index ) {
			$( this ).find( 'span' ).remove();
			var thdate = new Date(bookends.start);
			thdate.setDate( bookends.start.getDate() + index );
			if ( thdate.getTime() == today.getTime() ) {
				$( this ).addClass( 'today' );
			} else {
				$( this ).removeClass( "today" );
			}
			$( this ).data( "display_date", thdate );
			$( this ).append( "<span>" + monthsNarrow[thdate.getMonth()] + " " + thdate.getDate() + "</span>" );
		})

	
	$( "#timesheet-tasks-list" ).data( "timesheet_start", bookends.start.toDateString() );
	
	return bookends;
}

function updatePageHeaderDate( date, view ) {
	$( '.page-title' ).text( function() {
		var dateTitle = "";
		if ( view == "week" ) {
			dateTitle += months[date.start.getMonth()] + " " + date.start.getDate();
			dateTitle += " - ";
			if ( date.start.getMonth() != date.end.getMonth() ) {
				dateTitle += months[date.end.getMonth()];
			}
			dateTitle += " " + date.end.getDate();			
			dateTitle += ", " + date.end.getFullYear();
		} else if ( view == "day" ) {
			dateTitle =  days[date.getDay()] + ", " + months[date.getMonth()] + " " + date.getDate() + ", " + date.getFullYear();
		}
		return dateTitle;
	});	
}

function toggleTimesheetView( date ) {
	var view = $( "#timesheet-tasks-list" ).data( "timesheet_view" );
	var $tsDays = $( '#timesheet-tasks-list tbody' ).find( 'td.day' );
	var date = new Date( date );
	console.log("view: " + view);
	$( '#timesheet-tasks-list' ).find( 'th' )
		.removeClass( 'current' );
	
	if ( view == "week" ) {
		$tsDays.each( function( index, elem ) {
			if ( $( this ).attr( "colspan" ) ) {
				$( this ).attr( "colspan", 0 );
			}
			$( this ).show();
			swapInputText( $( this ).find( 'span.time-entry' ) );
			$( this )
				.find( 'a' )
				.each( function( index, elem ) {
					$( this ).remove();
				})
		});
		updatePageHeaderDate( getWeekBookends( date ), view );
		$( '.new-time-entry .ui-button-text' ).text( "+ Add Row" );
	} else if ( view == "day" ) {
		$tsDays.each( function( index, elem ) {
			if ( $( this ).index() % 7 == date.getDay() ) {
				$( this ).attr( "colspan", 7 )
					.show();
				$( '#timesheet-tasks-list' ).find( 'th' ).eq( $( this ).index() )
					.addClass( 'current' );
				swapInputText( $( this ).find( 'input' ) );
				$timerBtn.clone( true )
					.appendTo( $( this ) );
				$editEntry.clone( true )
					.appendTo( $( this ) );
			} else {
				$( this ).hide()
					.removeClass( 'current' )
					.find( 'a' )
					.each( function( index, elem ) {
						$( this ).remove();
					})
			}
		});
		$( '.new-time-entry .ui-button-text' ).text( "+ Add Entry" );
		updatePageHeaderDate( date, view );

	}
}

function swapInputText( elem ) {
	if ( $( elem ).is( 'input' ) ) {
		$( '<span class="time-entry">' )
			.attr( 'id', ( $( elem ).attr( 'name' ) ) )
			.text( $( elem ).val() )
			.insertBefore( $( elem ) );
		$( elem ).hide();
		
	} else if ( $( elem ).is( 'span' ) ) {
		$( elem ).filter( '.time-entry' );
		console.log( $( elem ) );
		console.log( $('input[name="' + $( elem ).attr( 'id' ) + '"]') );
		$( elem ).next( 'input[name="' + $( elem ).attr( 'id' ) + '"]' )
			.val( $( elem ).text() )
			.show();
		$( elem ).remove();
	}
}


$( function() {
	console.log("start");
	var thisWeek = getWeekBookends(); //leave blank to get current week
	var timesheetData = getTimesheet( $( "#timesheet-tasks-list" ).data( "person_id" ), thisWeek );

	getTasksForProject( $( "#project-name" ).val() );
	$( "#project-name" )
		.change( function( evt ) {
			getTasksForProject( $( this ).val() );
		})
		
	$( '#add-ts-entry-modal' )
		.dialog({
			autoOpen: false,
			resizable: false,
			height: 360,
			width: 600,
			modal: true,
			buttons: {
				"+ Add Row": function() {
					var rowInfo = {
						task_id: $( "#task-name" ).val(),
						task_name: $( "#task-name option:selected" ).text(),
						project_id: $( "#project-name" ).val(),
						project_name: $( "#project-name option:selected" ).text()
					}
					addTimesheetRow( rowInfo );
					$( this ).dialog( "close" );
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				
		        //console.log("close modal");
		    }
		});
	
	$( '.new-time-entry' ).click( function ( evt ) {
		
		if ( $( evt.currentTarget ).is( '.add-row' ) ) {
			
			//$( '#add-task-modal' )
				

		} else {
			
				
		}
		$( '#add-ts-entry-modal' ).dialog( 'open' );

		
		evt.preventDefault();
	});
	
});


$( function() {
	$( "#timesheet-tasks-list thead th.day")
		.click( function( evt ) {
			$( "#timesheet-tasks-list" ).data( "timesheet_view", "day" );
			
			toggleTimesheetView( $( this ).data( "display_date" ) );
			
			evt.preventDefault();
		});

	$( ".ui-button" )
		.button()
		.click( function( evt ) {
			
			evt.preventDefault();
		});
		
	$( ".ui-button.save" )
		.button( "option", "disabled", false )
		.click( function( evt ) {
			saveTimesheet( $( '#timesheet-tasks-list' ) );
			
			evt.preventDefault();
		});
	
	$( "#time-display" )
		.buttonset()
		.find( "#day-view" )
		.click( function( evt ) {
			$( "#timesheet-tasks-list" ).data( "timesheet_view", "day" );
			var bookends = getWeekBookends( new Date( $( "#timesheet-tasks-list" ).data( "timesheet_start" ) ) );
			var today = new Date();
			if ( ( today >= bookends.start ) && ( today <= bookends.end ) ) {
				toggleTimesheetView( today );
			} else {
				toggleTimesheetView( bookends.start );
			}
			
			evt.preventDefault();
		})
		.end()
		.find( "#week-view" )
		.click( function( evt ) {
			$( "#timesheet-tasks-list" ).data( "timesheet_view", "week" );
			var date = new Date( $( "#timesheet-tasks-list" ).data( "timesheet_start" ) );
			toggleTimesheetView( date );
			evt.preventDefault();
		});
		
	$( "#time-period" )
		.buttonset()
		.find( ".previous-date" )
		.button({
			icons: {
				primary: "ui-icon-triangle-1-w"
			},
			text: false
		})
		.click( function( evt ) {
			saveTimesheet( $( '#timesheet-tasks-list' ) );
			var date = new Date( $( "#timesheet-tasks-list" ).data( "timesheet_start" ) );
			console.log("date when prev clicked: " + date);
			date.setDate( new Date(date).getDate() - 7 );
			console.log("date after setting for prev week: " + date);
			var prevWeek = getWeekBookends( date );
			var timesheetData = getTimesheet( $( "#timesheet-tasks-list" ).data( "person_id" ), prevWeek );
			
			evt.preventDefault();
		})
		.end()
		.find( ".next-date" )
		.button({
			icons: {
			primary: "ui-icon-triangle-1-e"
		},
			text: false
		})
		.click( function( evt ) {
			saveTimesheet( $( '#timesheet-tasks-list' ) );
			var date = new Date( $( "#timesheet-tasks-list" ).data( "timesheet_start" ) );
			console.log("date when next clicked: " + date);
			date.setDate( new Date(date).getDate() + 7 );
			console.log("date after setting for next week: " + date);
			var nextWeek = getWeekBookends( date );
			var timesheetData = getTimesheet( $( "#timesheet-tasks-list" ).data( "person_id" ), nextWeek );
			
			evt.preventDefault();
		})
		.end()
		.find( ".current-date" )
		.click( function( evt ) {
			saveTimesheet( $( '#timesheet-tasks-list' ) );
			var thisWeek = getWeekBookends( );
			var timesheetData = getTimesheet( $( "#timesheet-tasks-list" ).data( "person_id" ), thisWeek );
			
			evt.preventDefault();
		});
		
			
	$( "#date-picker" )
		.datepicker({
			onSelect: function( date ) {
				saveTimesheet( $( '#timesheet-tasks-list' ) );
				var date = date;
				var showWeek = getWeekBookends( date );
				var timesheetData = getTimesheet( $( "#timesheet-tasks-list" ).data( "person_id" ), showWeek );
				console.log(timesheetData);
				toggleTimesheetView( date );
				
			}
		})
		.button();
		
	/*
$( ".date-picker-btn" )
		.button({
	 		icons: {
	     		primary: "ui-icon-calendar"
	 		},
	 		text: false
		})
		.click( function( evt ) {
			$( "#date-picker" ).datepicker({ showAnim: "slideDown" });
			evt.preventDefault();
		});
*/
});
