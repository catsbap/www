;
$(document).ready( function() {
	var $saveBtn = $( '<a id="save-info-btn" class="" href="#">Save Changes</a>' );
	var $cancelLink = $( '<a id="cancel-link" class="" href="#">Cancel</a>' );
	var $label = $( '<label class="entity-details-label"></label>' )
	var $inputText = $( '<input type="text" />' );
	var $inputCheckbox = $( '<input type="checkbox" />' );
	var $textarea = $( '<textarea class="entity-details-block"></textarea>' )
	var $editBtn;
	
	var projectId = $.url().param("project_id"); //parse current URL and return the project_id. Uses the Purl.js plugin
	
	var projectData = {};
	
	$.get( "/time_tracker/ci/index.php/data_controller/returnJSON", {
			func: "returnProjectJSON" ,
			id: projectId,
			collection: "project"
		}).done( function( data ) {
				console.log("done: " + data);
			})
			.fail( function( data ) {
				console.log("fail: " + data);
			})
			.success( function( data ) {
				projectData.project = $.parseJSON(data);
				console.log(projectData.project);
			});
	
	$.get( "/time_tracker/ci/index.php/data_controller/returnJSON", {
			func: "returnPeopleJSON" ,
			id: projectId,
			collection: "project"
		}).done( function( data ) {
				console.log("done: " + data);
			})
			.fail( function( data ) {
				console.log("fail: " + data);
			})
			.success( function( data ) {
				projectData.team = $.parseJSON(data);
				projectData.team.removed = [];
				projectData.team.added = [];
				//console.log(projectData.team);
			});
	$.get( "/time_tracker/ci/index.php/data_controller/returnJSON", {
			func: "returnTasksJSON" ,
			id: projectId,
			collection: "project"
		}).done( function( data ) {
				//console.log("done: " + data);
			})
			.fail( function( data ) {
				console.log("fail: " + data);
			})
			.success( function( data ) {
				projectData.tasks = $.parseJSON(data);
				projectData.tasks.removed = [];
				projectData.tasks.added = [];
				//console.log(projectData.tasks);
			});
	
	
	function saveData( dataObj ) {
		var sendData = {};
		for (item in dataObj) {
			sendData[item] = dataObj[item];
		}
		sendData["func"] = "editProject";
		console.log(sendData);
		$.post( "project-edit.php", sendData )
			.done( function( getData ) {
				console.log("done");
			})
			.fail( function( getData ) {
				console.log("fail");
			})
			.success( function( getData ) {
				$( 'h1.page-title' ).text( 'Project: ' + projectData.project[0]['project_name'] );
				$( 'h2.page-sub-title a' )
					.attr( 'href', 'client-detail.php?client_id=' + projectData.project[0]['client_id'] )
					.attr( 'title', "View client's details" )
					.text( $( 'span.client_id' ).text() );
			});
	}
	

	$saveBtn.click( function( evt ) {
		var isGood = true; //Data innocent until proven guilty
		var $container = $( this ).parents( 'section.entity-detail' );//.prev();
		
		/*
$container.find( '.required' )
			.each( function( index, elem ) {
				if ( $( elem ).val() == '' ) {
					isGood = false;
					$( elem ).addClass( 'error empty' );
				} else if ( $( elem ).hasClass( 'error empty' ) && $( elem ).val != '' ) {
					$( elem ).removeClass( 'error empty' );
					isGood = true;
				}
			});
*/

		console.log($(this).parents( 'ul' ).prev().find( 'input' ));
		if ( isGood ) {
			$container.find( 'input' )
				.each( function( index, elem ) {
					console.log(elem);
					if ( $( elem ).is ( '[type="text"]' ) ) {
						projectData.project[0][$( elem ).attr( 'name' )] = $( elem ).val();
						swapInputText( elem );
					} else if ( $( elem ).is ( '[type="checkbox"]' ) ) {
						if ( $( elem ).prop( "checked" ) ) {
							projectData.project[0][$( elem ).attr( 'name' )] = $( elem ).val();
						} else {
							projectData.project[0][$( elem ).attr( 'name' )] = 0;
						}
						swapInputCheckbox( elem );
					}
					
				});
			$container.find( 'select' )
				.each( function( index, elem ) {
					projectData.project[0][$( elem ).attr( 'name' )] = $( elem ).val();
					console.log(projectData.project[0][$( elem ).attr( 'name' )]);
					swapSelect( elem );
				});
			$container.find( 'textarea' )
				.each( function( index, elem ) {
					projectData.project[0][$( elem ).attr( 'name' )] = $( elem ).val();
					console.log(elem);
					swapTextArea( elem );
				});
			saveData( projectData.project[0] );
			$editBtn.appendTo( $( this ).parent() );
			$editBtn = null;
			$( this ).detach();
		} else {
			
		}
		evt.preventDefault();
	});

	function swapInputText( elem ) {
		var required = '';
		if ( $( elem ).hasClass( 'required' ) || $( elem ).prev( 'label' ).hasClass( 'required' ) ) {
			required = " required";
		}
		if ( $( elem ).is( 'input' ) ) {
			var useName = $( elem ).prev( 'label' ).attr( 'for' );
			var useLabel = $( elem ).prev( 'label' ).text().split(":")[0];
			$( elem ).parent()
				.empty()
				.text( useLabel + ": " )
				.append( '<span class="edit ' + useName + required + '">' + projectData.project[0][useName] + '</span>' );
				
		} else {
			var useName = $( elem ).attr( 'class' ).split(' ')[1];
			var useLabel = $( elem ).parent().text().split(':')[0];
			$( elem ).parent()
				.empty()
				.append( function() {
					return $label.clone() 
						.attr( 'for', useName )
						.text( useLabel + ": " )
						.addClass( required );
				})
				.append( function() {
					return $inputText.clone() 
						.val( projectData.project[0][useName] )
						.attr( 'name', useName )
						.blur( function( evt ) {
							if ( $( this ).prev().hasClass( 'required' ) ) {
								console.log(evt.currentTarget);
								if ( $( this ).val() == '' ) {
									//isGood = false;
									$( this ).prev().addClass( 'error empty' );
								} else if ( $( this ).prev().hasClass( 'error empty' ) && $( this ).val != '' ) {
									$( this ).prev().removeClass( 'error empty' );
									//isGood = true;
								}
							}
						});
				});
		}
	}
	
	function swapInputCheckbox( elem ) {
		console.log( elem );
		var required = '';
		var statusToggle = $( elem ).data( "status-toggle" ).split( ":" );
		
		if ( $( elem ).hasClass( 'required' ) ) {
			required = " required";
		}
		if ( $( elem ).is( 'input' ) ) {
			var useName = $( elem ).prev( 'label' ).attr( 'for' );
			var useLabel = $( elem ).prev( 'label' ).text().split(":")[0];
			$( elem ).parent()
				.empty()
				.text( useLabel + ": " )
				.append( '<span class="checkbox ' + useName + required + '" data-status-toggle="' + statusToggle.join(":") + '">' + statusToggle[projectData.project[0][useName]] + '</span>' );
				
		} else {
			var useName = $( elem ).attr( 'class' ).split(' ')[1];
			var useLabel = $( elem ).parent().text().split(':')[0];
			$( elem ).parent()
				.empty()
				.append( function() {
					return $label.clone() 
						.attr( 'for', useName )
						.text( useLabel + ": " )
						.addClass( required );
				})
				.append( function() {
					console.log(projectData.project[0][useName]);
					return $inputCheckbox.clone() 
						.val( 1 )
						.prop( 'checked', function() {
							if ( projectData.project[0][useName] == 1 ) {
								return true;
							} else {
								return false;
							}
						})
						.attr( 'name', useName )
						.data( 'status-toggle', $( elem ).data( 'status-toggle' ) );
				})
				.append( ' Archive project?' );
		}
	}
	
	function swapSelect( elem, list ) {
		var required = '';
		if ( $( elem ).hasClass( 'required' ) ) {
			required = " required";
		}
		//console.log($( elem ));
		
		if ( $( elem ).is( 'select' ) ) {
			//console.log($( elem ).find( 'option:selected' ).text());
			var useName = $( elem ).prev( 'label' ).attr( 'for' );
			var useLabel = $( elem ).prev( 'label' ).text().split(":")[0];
			$( elem ).parent()
				.empty()
				.text( useLabel + ": " )
				.append( '<span class="select ' + useName + required + '">' + $( elem ).find( 'option:selected' ).text() + '</span>' );

		} else {
			var useName = $( elem ).attr( 'class' ).split( ' ' )[1];
			var useLabel = $( elem ).parent().text().split(':')[0];

			var $select = $( '<select name="' + useName + '" id="project-client-select" size="1"></select>' );
			//console.log("LIST" + list);
			if ( list == "client" ) {
				var useName = $( elem ).attr( 'class' ).split( ' ' )[1];
				$.get( "/time_tracker/ci/index.php/data_controller/returnJSON", {
						func: "returnClientJSON",
						id: "",
						collection: ""
					})
					.done( function( getData ) {
						console.log("done");
					})
					.fail( function( getData ) {
						console.log("fail");
					})
					.success( function( getData ) {
						var data = $.parseJSON(getData);
						for ( var i=0; i < data.length; i++ ) {
							//console.log("success: " + data[i]["client_name"]);
							var $opt = makeOption( data[i], elem )
								.appendTo( $select );
						}
						//console.log($select);
						$( elem ).parent()
							.empty()
							.append( function() {
								return $label.clone() 
									.attr( 'for', useName )
									.text( useLabel + ": " )
									.addClass( required );
								})
							.append( $select );
					});
			} else if ( list == "invoice-methods") {
				
				
			}
			
		}
	}
	
	function makeOption( data, elem ) {
		return $( '<option>' )
			.val( data["client_id"] )
			.text( data["client_name"] )
			.prop( "selected" , function() {
				if ( data["client_name"] == $( elem ).text() ) {
					return true;
				} else {
					return false;
				}
			})
	}
	
	function swapTextArea( elem ) {
		var required = '';
		if ( $( elem ).hasClass( 'required' ) ) {
			required = " required";
		}
		if ( $( elem ).is( "textarea" ) ) {
			var useName = $( elem ).prev( 'label' ).attr( 'for' );
			var useLabel = $( elem ).prev( 'label' ).text().split(":")[0];
			console.log( $( elem ) );
			$( elem ).prev( 'label' )
				.before( '<p class="entity-list entity-details-block textarea ' + useName + required + '">' + projectData.project[0][useName] + '</span>' );
			$( elem ).prev( 'label' )
				.remove();
			$( elem )
				.remove();
				
		} else {
			var useName = $( elem ).attr( 'class' ).split(' ');
			useName = useName[useName.length - 1];
			var useLabel = $( elem ).next().text();
			
			//var useName = useName.pop();
			$( elem ).prev()
				.after( function() {
					return $textarea.clone() 
						.val( projectData.project[0][useName] )
						.attr( 'name', useName );
				})
				.after( function() {
					return $label.clone() 
						.attr( 'for', useName )
						.text( useLabel + ": " )
						.addClass( required );
				});
			$( elem )
				.remove();
		}
	}

	
	$( '#edit-project-info-btn' ).click( function( evt ) {
		$( '#project-info .edit' )
			.each( function( index, elem ) {
				swapInputText( elem );
			});
		$( '#project-info .select' )
			.each( function( index, elem ) {
				swapSelect( elem, "client" );
			});
		$( '#project-info .checkbox' )
			.each( function( index, elem ) {
				swapInputCheckbox( elem );
			});
		$saveBtn.appendTo( $( this ).parent() );
		$editBtn = $( this ).detach();
		//console.log($projectInfoEdit);
		evt.preventDefault();
	});
	
	$( '#edit-project-notes-btn' ).click( function( evt ) {
		$( '#project-notes .textarea' )
			.each( function( index, elem ) {
				swapTextArea( elem );
			});
		$saveBtn.appendTo( $( this ).parent() );
		$editBtn = $( this ).detach();
		//console.log($projectInfoEdit);
		evt.preventDefault();
	});

	/*
$( '#edit-invoicing-btn' ).click( function( evt ) {
		$( '#project-invoicing .edit' )
			.each( function( index, elem ) {
				swapInputText( elem );
			});
		$( '#project-invoicing .select' )
			.each( function( index, elem ) {
				swapSelect( elem, "invoice-methods" );
			});
		$( '#project-invoicing .checkbox' )
			.each( function( index, elem ) {
				swapInputCheckbox( elem );
			});
		$saveBtn.appendTo( $( this ).parent() );
		$editBtn = $( this ).detach();
		//console.log($projectInfoEdit);
		evt.preventDefault();
	});
*/


	$(function() { //tabs interface for project-detail.php
		$( ".tabs" ).tabs();
	});

	$(function(){ //table display/sorter with filter/search for projects.php
		$( "#project-list" ).tablesorter({
			sortList: [[2,0], [3,0]],
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
		$( "#task-list" ).tablesorter({
			sortList: [[0,0], [1,0], [0, 0]],
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
		$( "#people-list" ).tablesorter({
			sortList: [[0,0], [1,0], [0, 0]],
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
	
	
});