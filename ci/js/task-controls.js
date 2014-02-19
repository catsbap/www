;
var yesNoToggle = ["No", "Yes"];

var giveFeedbackMessage = function( content, messageType ) {
	$( '<p></p>' )
		.html( content )
		.addClass( messageType )
		.insertAfter( '.page-header' )
		.hide()
		.delay( 750 )
		.slideToggle( 'slow' )
		.delay( 1500 )
		.slideToggle( 'fast', function() {
			$( this )
				.removeClass()
				.remove();
			})
}

		
var updateRowCB = function( elem ) {
	var $new = $( elem ).find( '.new' );
	$new.children('td').css({ 
		"background-color": '#A9BFF5'
	})
	.animate({
		"background-color": '#FAF9FF'
	}, 1000)
	$new.removeClass( 'new' );
}

function saveData( dataObj, $editRow ) {
	//console.log(editRow);
	var sendData = {};
	for (item in dataObj) {
		sendData[item] = dataObj[item];
	}
	sendData["func"] = "processTask";
	
	//console.log(dataObj["task_archived"]);
	$.post( "display_task", sendData )
		.done( function( getData ) {
			//console.log("done");
		})
		.fail( function( getData ) {
			giveFeedbackMessage( "Error saving task '" + dataObj["task_name"] + ".'", 'error' )
			//console.log("fail");
		})
		.success( function( getData ) {
			var resort = true;
			
			if ( getData.indexOf( "update" ) && $editRow ) {
				if ( ( dataObj["task_archived"] == 1 ) && ( $( '.page-title' ).text().indexOf( 'Archive' ) < 0 ) ) {
					console.log("archive task");
					$editRow.fadeOut()
						.remove();
						
					$( "#tasks-list" )
						.trigger( "updateRow", [ resort, updateRowCB ]);
					giveFeedbackMessage( getData.replace( "updated.", "updated and archived." ), 'success' );
					
				} else {
					console.log("edit task");
					$( $editRow ).children( 'td:first-child' )
						.next( 'td' ).text( dataObj["task_name"] )
						.next( 'td' ).text( '$' + Number( dataObj["task_hourly_rate"] ).toFixed( 2 ) )
						.next( 'td' ).text( yesNoToggle[dataObj["task_bill_by_default"]] )
						.next( 'td' ).text( yesNoToggle[dataObj["task_common"]] );
					
					$( $editRow ).addClass( 'new' );
					
					$( "#tasks-list" )
						.trigger( "updateRow", [ resort, updateRowCB ]);
					updateRowCB( $( '#tasks-list' ) );
					giveFeedbackMessage( getData, 'success' );
					
				}

			} else {
				var taskId = dataObj["task_id"];
				var $row = $( '<tr>' )
					.data( "options", {
						task_id: taskId,
						task_name: dataObj["task_name"],
						task_hourly_rate: dataObj["task_hourly_rate"],
						task_bill_by_default: dataObj["task_bill_by_default"],
						task_common: dataObj["task_common"],
						task_archived: dataObj["task_archived"]
					})
					.addClass( 'new' )
					.append( '<td><a class="tasks-link" href="#" title="Edit task details">Edit</a>')
					.append( '<td>' + dataObj["task_name"] + '</td>' )
					.append( '<td>$' + Number(dataObj["task_hourly_rate"]).toFixed(2) + '</td>' )
					.append( '<td>' + yesNoToggle[dataObj["task_bill_by_default"]] + '</td>' )
					.append( '<td>' + yesNoToggle[dataObj["task_common"]] + '</td>' );
				$( '#tasks-list' )
					.find( 'tbody' )
					.append( $row )
					.trigger( 'addRows', [ $row, resort, updateRowCB ]);
				giveFeedbackMessage( "Task '" + dataObj["task_name"] + "' successfully added.", 'success' );
			}
			//console.log("task saved" +  );
		});
}

$( function() {
	$( '#add-task-modal' ).dialog({
		autoOpen: false,
		resizable: false,
		height: 360,
		width: 775,
		modal: true,
		buttons: {
			"+ Save Task": function() {
				var task = {};
				if ( $( this ).data( 'edit' ) ) {
					//console.log("edit task");
					var taskId = $( this ).data( 'edit' );
										
					task["task_id"] = taskId;
					task["proc_type"] = 'E' //$( '#proc-type' ).val()
				} else {
					task["task_id"] = "";					
					task["proc_type"] = 'A'; //$( '#proc-type' ).val()
				}
				task["task_name"] = $( '#task-name' ).val();
				task["task_hourly_rate"] = $( '#task-hourly-rate' ).val();
				task["task_bill_by_default"] = $( '#task-billable' ).prop( 'checked' ) ? $( '#task-billable' ).val() : 0;
				task["task_common"] = $( '#task-common' ).prop( 'checked' ) ? $( '#task-common' ).val() : 0;
				task["task_archived"] = $( '#task-archived' ).prop( 'checked' ) ? $( '#task-archived' ).val() : 0;
				
				saveData( task, $( this ).data( 'row' ) );
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
	
	function openEditModal( evt ) {
		var $row = $( this ).parents( 'tr' );
		var data = $row.data( "options" );
		if ( $( evt.currentTarget ).is( '#add-task-btn' ) ) {
			
			$( '#add-task-modal' )
				.find( '#task-name' ).val( "" ).end()
				.find( '#task-hourly-rate' ).val( "" ).end()
				.find( '#task-billable' ).prop( "checked", false ).end()
				.find( '#task-common' ).prop( "checked", false ).end()
				.find( '#task-archived' ).prop( "checked", false );

		} else {
			console.log($( '#task-archived' ).prop( "checked" ));
			console.log(parseInt(data["task_archived"]));
			if (parseInt(data["task_archived"])) {
				console.log("true");
			} else {
				console.log("false");
			}
			
			$( '#add-task-modal' )
				.find( '#task-name' ).val( data["task_name"] ).end()
				.find( '#task-hourly-rate' ).val( data["task_hourly_rate"] ).end()
				.find( '#task-billable' ).prop( "checked", parseInt( data["task_bill_by_default"] ) ).end()
				.find( '#task-common' ).prop( "checked", parseInt( data["task_common"] ) ).end()
				.find( '#task-archived' ).prop( "checked", parseInt( data["task_archived"] ) );
			
			$( '#add-task-modal' )
				.data( 'edit', data["task_id"] )
				.data( 'row', $( this ).parents( 'tr' ) )
				.dialog( "option", "title", "Edit Task" );
				
		}
		$( '#add-task-modal' ).dialog( 'open' );

		evt.preventDefault();
	}
		
	$( '.task-link' ).click( openEditModal );
	$( '#add-task-btn' ).click( openEditModal );
	
	var $viewArchives = $( '.view-archive-link' );
	var $viewActive = $viewArchives.clone()
		.text( "View Active Tasks" )
		.removeClass( "view-archive-link" )
		.addClass( "view-active-link" )
		.attr( 'href', 'display_task' );
		
		
	$viewArchives.click( function( evt ) {
		var $taskList = $( '#tasks-list' );
		var tasks;
		var resort = true;
		$.tablesorter.clearTableBody( $taskList );
		//$.get( "../../application/libraries/returnJSON.php", {
		$.get( "/time_tracker/ci/index.php/data_controller/returnJSON", {
			func: "returnTasksJSON" ,
			id: "",
			collection: "tasks",
			archiveFlag: 1
		}).done( function( data ) {
				console.log("done: " + data);
			})
			.fail( function( data ) {
				//console.log("fail: " + data);
			})
			.success( function( data ) {
				tasks = $.parseJSON(data);
				console.log("XXXX");
				for ( var i = 0; i < tasks.length; i++ ) {
					
					var taskId = tasks[i]["task_id"];
					console.log("task id is" + taskId);
					var $row = $( '<tr>' )
						.data( "options", {
							task_id: taskId,
							task_name: tasks[i]["task_name"],
							task_hourly_rate: tasks[i]["task_hourly_rate"],
							task_bill_by_default: tasks[i]["task_bill_by_default"],
							task_common: tasks[i]["task_common"],
							task_archived: tasks[i]["task_archived"]
						})
						.append( function( elem ) {
							var $a = $( '<a class="task-link" href="#" title="Edit task details">Edit</a>' )
								.click( openEditModal );
							return $( '<td>' )	
								.append( $a );
							})
						.append( '<td>' + tasks[i]["task_name"] + '</td>' )
						.append( '<td>$' + Number(tasks[i]["task_hourly_rate"]).toFixed(2) + '</td>' )
						.append( '<td>' + yesNoToggle[tasks[i]["task_bill_by_default"]] + '</td>' )
						.append( '<td>' + yesNoToggle[tasks[i]["task_common"]] + '</td>' );
						
						console.log($row.data( "options" ));
						
					$( '#tasks-list' )
						.find( 'tbody' )
						.append( $row );
				}
				$( '.page-title' ).text( "View Archived Tasks" );
				$( '#tasks-list' ).trigger( 'update', [ resort ]);
			});
		
		$( this ).replaceWith( $viewActive );
		$taskList.trigger( "update" );
		evt.preventDefault();
	});
	
	$viewActive.click( function( evt ) {
		$( this ).replaceWith( $viewArchives );
		location.href = "display_task";
	});
	
});

$( function() { //table display/sorter with filter/search for projects.php
	$( "#tasks-list" ).tablesorter({
		sortList: [[3,1], [1,0]],
		widgets: ["filter"],
		widgetOptions : {
			
			// If there are child rows in the table (rows with class name from "cssChildRow" option)
			// and this option is true and a match is found anywhere in the child row, then it will make that row
			// visible; default is false
			filter_childRows : false,
			
			// if true, a filter will be added to the top of each table column;
			// disabled by using -> headers: { 1: { filter: false } } OR add class="filter-false"
			// if you set this to false, make sure you perform a search using the second method below
			filter_columnFilters : true,
			
			// extra css class applied to the table row containing the filters & the inputs within that row
			filter_cssFilter : '',
			
			// class added to filtered rows (rows that are not showing); needed by pager plugin
			filter_filteredRow   : 'filtered',
			
			// add custom filter elements to the filter row
			// see the filter formatter demos for more specifics
			filter_formatter : null,
			
			// add custom filter functions using this option
			// see the filter widget custom demo for more specifics on how to use this option
			filter_functions : null,
			
			// if true, filters are collapsed initially, but can be revealed by hovering over the grey bar immediately
			// below the header row. Additionally, tabbing through the document will open the filter row when an input gets focus
			filter_hideFilters : false,
			
			// Set this option to false to make the searches case sensitive
			filter_ignoreCase : true,
			
			// if true, search column content while the user types (with a delay)
			filter_liveSearch : true,
			
			// jQuery selector string of an element used to reset the filters
			filter_reset : 'button.reset',
			
			// Delay in milliseconds before the filter widget starts searching; This option prevents searching for
			// every character while typing and should make searching large tables faster.
			filter_searchDelay : 300,
			
			// if true, server-side filtering should be performed because client-side filtering will be disabled, but
			// the ui and events will still be used.
			filter_serversideFiltering: false,
			
			// Set this option to true to use the filter to find text from the start of the column
			// So typing in "a" will find "albert" but not "frank", both have a's; default is false
			filter_startsWith : false,
			
			// Filter using parsed content for ALL columns
			// be careful on using this on date columns as the date is parsed and stored as time in seconds
			filter_useParsedData : false
			
		}
	});
});