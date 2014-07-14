/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * @copyright  Küstenschmiede GmbH Software & Design 2012
 * @author     Jürgen Witte <http://www.kuestenschmiede.de> 
 * @package    con4gis 
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// use local namespace with single execution function
(function($) {	

	// id for generated DIVs
	var 
		nextId = 1;		
	 
	
	// Extend jQuery, so c4gGui can be used with $(...).c4gGui(...)	
	$.fn.c4gGui = function(options) {

		options = $.extend({
			ajaxUrlPrefix : 'ajax.php',
			navPanel : true,
			jquiBreadcrumb : true,
			jquiButtons : true,
			embedDialogs : false,
			jquiEmbeddedDialogs : true,
			breadcrumbDelim : '',
			width : '',
			height : ''
		}, options);

		var buttonDiv = null;
	    var pushingState = false;
		

		var History = null;
		if ((typeof(window.History)!='undefined') && window.History.enabled) {
			History = window.History;
		}	
		
		var fnHistoryPush = function( state ) {
			if (History!=null) { 
				pushingState = true;
				History.pushState(null, document.title, "?state="+state);
				
				// strange workaround for Opera >= 11.60 bug
				if (typeof(document.getElement)!='undefined') {
					var head = document.getElement("head");
					if (typeof(head)=='object') {
						var base = head.getElement('base');
						if (typeof(base)=='object') {
							base.href = base.href;
						}	
					}
				}	
					
				pushingState = false;
			}
			
		};
		
		
		// -----------------------------------
		// handle Ajax response
		// -----------------------------------
		var fnHandleAjaxResponse = function(data, internalId) {
			// TODO: Request Token Handling
			var navDiv = '#c4gGuiNavigation'+internalId;
			var contentWrapperDiv = '#c4gGuiContentWrapper'+internalId;
			var contentDiv = '#c4gGuiContent'+internalId;
			var dialogDiv = '#c4gGuiDialog'+internalId;
			var breadcrumbDiv = '#c4gGuiBreadcrumb'+internalId;
			var headlineDiv = '#c4gGuiHeadline'+internalId;

			var fnExecAjax = function(ajaxMethod, ajaxUrl, ajaxData) {
				$.ajax({
					  internalId : internalId,
					  url: ajaxUrl,
					  data: ajaxData,
					  dataType: "json",
					  type: ajaxMethod,
					  success: function( data ) {
						  fnHandleAjaxResponse(data, this.internalId);
					  },
				  	  error: function(jqXHR, textStatus, errorThrown) {
				  		fnInitContentDiv();  
				  		$(this.contentDiv).text('Error: '+errorThrown);
				  	  }
					});				
			};

			var fnExecAjaxGet = function(ajaxData) {
				fnExecAjax( "GET", options.ajaxUrl, ajaxData );
			};

			var fnExecAjaxPut = function(ajaxUrl,ajaxData) {
				fnExecAjax( "PUT", ajaxUrl, ajaxData );
			};

            var fnJumpToLink = function(linkUrl,newwindow) {
            	if (navigator.appName == 'Microsoft Internet Explorer') {
            		if ((linkUrl.indexOf(':')<0) && (linkUrl[0]!='/')) {
        	        	linkUrl = linkUrl.replace('index.php/', "");
        	    	}	
        		}
        		if (newwindow)
					window.open(linkUrl, 'Window')
				else
           			window.location = linkUrl;
            };

            var fnAddTooltip = function(element){
				if (typeof(jQuery.fn.tooltip) == 'function') {
					element
						.find('.c4gGuiTooltip')
						.tooltip({track: false, delay: 0, extraClass: "c4gGuiTooltipComponent ui-corner-all ui-widget-content", showURL: true});

				}	            	
            };
            
            var fnAddScrollpane = function(element){
            	//TODO add Switch
            	//TODO disable autoReinitialise and do it manually everytime the dialog resizes
            	if (typeof(jQuery.fn.jScrollPane) == 'function') {
					element
						.jScrollPane({autoReinitialise:true, autoReinitialiseDelay:100});
				}	            	
            };
            
            var fnAddAccordion = function(element){
				if (typeof(jQuery.fn.accordion) == 'function') {
					element
						.find('.c4gGuiAccordion')
						.accordion({active: false, autoHeight: false, collapsible: true});
				}	
            };
            
            var fnMakeCollapsible = function(element){
            	element
            		.find('.c4gGuiCollapsible_hide')
            		.slideUp(0);
				element
					.find('.c4gGuiCollapsible_trigger')
					.click(function()
					{	
						$(this).children('.c4gGuiCollapsible_trigger_target').slideToggle(250);
						$(this).nextUntil('.c4gGuiCollapsible_trigger','.c4gGuiCollapsible_target').slideToggle(250); //.addClass('sub_active');
					});
            };

            var fnEnableWswgEditor = function(){
            	if (typeof(wswgEditor) != 'undefined') {
					if( $('#editor').length > 0 ){
						wswgEditor.initEditor('editor');
					}
					$('.BBCode-Area').each( function() {
						$(this).html( wswgEditor.parseBBCode( $(this).html() ) ); 
					});

				}
            };

            var fnAddButton = function(element){
				if (typeof(jQuery.fn.button) == 'function') {
					element
						.find('.c4gGuiButton')
						.button();
				}	
            };
            
            var fnDialogClose = function(element) {
            	if (options.embedDialogs) {
            		$(element).hide();
            		var state = 
            			$(element).prev().show()
            			.attr('data-state');
					if ((typeof(state)=="undefined") || (state=="")) {
						state = $(contentDiv).attr('data-state');						
					}
					if ($(element).prev().hasClass('c4gGuiContent')) {
						$(buttonDiv).show();
					}	

					if ((state!="") && (History!=null)) {
						fnHistoryPush(state);
					}
            		$(element).remove();
            	} else {	
            		$(element).parent().find(".ui-dialog-titlebar-close").trigger('click');
            	}	
            };	
                        

			var content = data.content;
			if (typeof(content)!='object') {
				content = $.parseJSON( content );
			}
			if (content==null) { 
				return;
			}			
			
			if (typeof(content.title) != "undefined") {
				$('#c4gGuiTitle').html(content.title);
			};

			if (typeof(content.subtitle) != "undefined") {
				$('#c4gGuiSubtitle').html(content.subtitle);
			} else {
				if (typeof(content.title) != "undefined") {
					$('#c4gGuiSubtitle').html("");
				}	
			};

			var currentState = "";

	        var fnInitContentDiv = function() {
				$(contentDiv).empty();
				$(contentWrapperDiv+' div:not('+contentDiv+')').remove();
				$(contentDiv).show();            	
	        };

	        if (History!=null) {
				if (typeof(content.state)!='undefined')
				{
					currentState = content.state;					
				}	
				if (typeof(content.dialogstate)!='undefined')
				{
					currentState = content.dialogstate;					
				}	
				if (currentState != "")  {
					fnHistoryPush( currentState );
				}
			}
						
			// Set headline
	        if (typeof(content.headline)!='undefined') {
				$(headlineDiv).html(content.headline);
			}

			// create breadcrumb
			if ($.isArray(content.breadcrumb)) {
				$(breadcrumbDiv).empty();
				$.each( content.breadcrumb, function(index, value){
					if (index>0) {
						if (options.breadcrumbDelim!='') {
							$(breadcrumbDiv).append('<span class="c4gGuiBreadcrumbDelim">'+options.breadcrumbDelim+'</span>');
						}
						
					}
					var aButton = $("<a />")
				    	.attr('href','#')
				    	.attr('class','c4gGuiBreadcrumbItem')
				    	.html(value['text'])
				    	.click(function() {
				    		if (value['url']) {
				    			fnJumpToLink(value['url']);
				    		} else {
				    			if (value['id']) {
				    				fnExecAjaxGet(options.ajaxData+'&req='+value['id']);
				    			}	
				    		}	
				    		return false; 
				    	})
				    	.appendTo(breadcrumbDiv);
					if (options.jquiBreadcrumb) {
				    	aButton.button();
					}	
				});
			}
			
			// create buttons
			if ($.isArray(content.buttons)) {
				$(buttonDiv).empty();
				$(buttonDiv).hide();
				$.each( content.buttons, function(index, value){
					var aButton = $("<a />")
				    	.attr('href','#')
				    	.html(value['text'])
				    	.click(function() {
				    		if (value['tableSelection']) {
								if ((typeof(oDataTable) != 'undefined') && (oDataTable!=null)) {
									var formdata=new Object();
									oDataTable.$('tr.row_selected').each(function(index,value){
										formdata['action'+index] = value.attributes['data-action'].value;
									});
									fnExecAjaxPut( 
										options.ajaxUrl+'?'+options.ajaxData+'&req='+value['id'], 
										formdata);

								}	
								return false;

				    		}
				    		fnExecAjaxGet(options.ajaxData+'&req='+value['id']);
				    		return false; 
				    	})
				    	.appendTo(buttonDiv);
					if (options.jquiButtons) {
				    	aButton.button();
					}	
					$(buttonDiv).show();
				});
			}
			
			
			
			
			if (typeof(content.treedata)!='undefined') {
				// populate tree with data when treedata is available

				$(navDiv).empty();

				if (typeof ($.fn.dynatree) == 'undefined') {				
					$(navDiv).html('<h1>jQuery.dynatree missing</h1>');
				} else {

					var treeDiv = $('<div />')
				  		.attr('id','c4gGuiDynatree'+internalId)
				  		.attr('class','c4gGuiTree')
				  		.attr('width',$(navDiv).width())
				  		.attr('height',$(navDiv).height())
				  		.appendTo($(navDiv));
					
					// TODO: Error handling
					var treedata = $.extend(
							{
								onActivate: function(node) {
									if ((typeof(node.data.href)!='undefined') && (node.data.href!='') && (node.data.href!==null)) {
										if (node.data.href_newwindow) {
											fnJumpToLink(node.data.href,true);
										}
										else {
											fnJumpToLink(node.data.href);											
										}	

									}
									else {
										fnExecAjaxGet(options.ajaxData+'&req='+node.data.key);
									}	
								}, 
								onFocus: function(node) {
									// Auto-activate focused node after 2 seconds
									node.scheduleAction("activate", 2000);
								}
							}, content.treedata
						);  
					$(treeDiv).dynatree(treedata);
					$(navDiv).resizable( "destroy" );
					$(navDiv+" .dynatree-container").width($(navDiv).width()-8);
					$(navDiv).resizable({
						//animate: true,
						alsoResize: navDiv+" .dynatree-container, "+navDiv+" .c4gGuiTree",
						resize: function(event,ui) {
							var newWidth = $(contentWrapperDiv).parent().width() - ui.size.width - 5;
							$(contentWrapperDiv).width( newWidth );
							$(contentWrapperDiv).height( ui.size.height);
						},
						stop: function() {
							if ((typeof(oDataTable) != 'undefined') && (oDataTable!=null)) {
								oDataTable.fnDraw(true);								
							}							
						}
					});		
					fnAddTooltip($(navDiv));
				}
			}
			
			// TODO: Error handling
			if ((typeof(content.contentdata)!='undefined') || ($.isArray(content.contents))) {
                // populate dataTable
				fnInitContentDiv();

				var newWidth = '100%';
				var newHeight = '100%';
				if (options.navPanel) {
					newWidth = $(contentWrapperDiv).parent().width()
							- $(navDiv).width() - 5;
					newHeight = $(navDiv).height();
				}
				$(contentWrapperDiv).width(newWidth);
				$(contentWrapperDiv).height(newHeight);
				if (typeof (content.state) != 'undefined') {
					$(contentDiv).attr('data-state', content.state);
				}

				//var fnAddContent = function(contenttype,contentoptions,contentdata) {
				var fnAddContent = function(content) {
					var contenttype = content.contenttype;
					var contentoptions = content.contentoptions;
					var contentdata = content.contentdata;
					if ((contenttype == 'datatable')
							&& (typeof (contentdata) != 'undefined')) {

						if (typeof ($.fn.dataTable) == 'undefined') {
							$(contentDiv).html('<h1>jQuery.dataTable missing</h1>');
						} else {
		
							var tableDiv = $( '<table />')
								.attr('id','c4gGuiDataTable:' + content.state)
								// .attr('id','c4gGuiDataTable'+internalId)
								.attr('cellpadding','0')
								.attr('cellspacing','0')
								.attr('border','0')
								.attr('class','display c4gGuiDataTable')
								.appendTo(contentDiv);
											
							var actioncol=-1;
							var tooltipcol=-1;
							var selectOnHover = false;
							var clickAction = false;
							var multiSelect = false;
							
							    
							if (typeof(contentoptions)!='undefined') {
								if (typeof(contentoptions.actioncol)!='undefined') {
									actioncol = contentoptions.actioncol;
								}
								if (typeof(contentoptions.tooltipcol)!='undefined') {
									tooltipcol = contentoptions.tooltipcol;
								}
								if (typeof(contentoptions.selectOnHover)!='undefined') {
									selectOnHover = contentoptions.selectOnHover;
								}
								if (typeof(contentoptions.clickAction)!='undefined') {
									clickAction = contentoptions.clickAction;
								}
								if (typeof(contentoptions.multiSelect)!='undefined') {
									multiSelect = contentoptions.multiSelect;
								}
							}
							var contentdata = $.extend(	{
									"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) 
									  	{
									    	if (actioncol!=-1) {
									    		$(nRow).attr( 'data-action', aData[actioncol] );
									    	}
									    	if ((tooltipcol!=-1) && (typeof(jQuery.fn.tooltip) == 'function')) {
									    		if (aData[tooltipcol]) {
									    			$(nRow).attr('data-tooltip',aData[tooltipcol]);
									    			$(nRow).tooltip( {
									    			    	bodyHandler: function() { 
									    			    		return $(nRow).attr('data-tooltip'); 
									    			    	},
									    					extraClass: "c4gGuiTooltipComponent c4gGuiTooltipInTable"								    					
									    				}
									    			);
									    		}
									    	}
									    	return nRow;
									  	},
									"fnDrawCallback": function() {
											$(tableDiv).find('tr')
											.unbind('hover')
											.unbind('click')
											.hover(function() {
												if (selectOnHover) {
													$(this).addClass('row_selected');
												}
												if (clickAction || multiSelect) {
													$(this).addClass('cursor_pointer');
												}	
											}, function() {
												if (selectOnHover) {
													$(this).removeClass('row_selected');
												}
												if (clickAction || multiSelect) {
													$(this).removeClass('cursor_pointer');
												}	
											})
											.click(function() {
												if (multiSelect) {
													$(this).toggleClass('row_selected');
												}	
												if ((clickAction) && (typeof($(this).attr('data-action'))!='undefined')) {
													fnExecAjaxGet(options.ajaxData+'&req='+$(this).attr('data-action'));
													return false;
												}	
											});									 
										}
							}, contentdata);
							
		                    oDataTable = $(tableDiv).dataTable(contentdata);
		                    fnDataTableColumnVis(oDataTable);
						}
	                    
					}

					if (contenttype=='html')  {
						var aClass = 'c4gGuiHtml';
						if (typeof(contentoptions)!='undefined') {
							if (contentoptions.scrollable) {
								aClass = aClass + ' c4gGuiScrollable';
							}
						}
						var aHtmlDiv = $('<div />')
							.attr('id', 'c4gGuiHtml'+internalId)
							.attr('class',aClass)
							.appendTo(contentDiv)
							.html(contentdata);
						
						aHtmlDiv
							.find('.c4gGuiAction')
							.hover(function(){
								if ($(this).attr('data-hoverclass')!='undefined') {
									if ($(this).attr('data-hoverclass')) {
										$(this).addClass($(this).attr('data-hoverclass'));								
									}	
								}
							}, function() {
								if ($(this).attr('data-hoverclass')!='undefined') {
									if ($(this).attr('data-hoverclass')) {
										$(this).removeClass($(this).attr('data-hoverclass'));																
									}
								}
								
							})
							.click(function() {
		                    	if (typeof($(this).attr('data-href'))!='undefined') {
									if ($(this).attr('data-href_newwindow')) {
								    	fnJumpToLink($(this).attr('data-href'), true);
								    }
								    else {
								    	fnJumpToLink($(this).attr('data-href'));							    	
								    }	
		                    		return false;
		                    	}	

	   							if ($(this).hasClass('c4gGuiSend')) {
									var formdata = new Object();
									$(contentDiv).find('.formdata').each(function(index,element) {
										if ($(element).attr('type')=='checkbox') {
											formdata[$(element).attr('name')] = ($(element).attr('checked') == 'checked');
										} else {
											formdata[$(element).attr('name')] = $(element).val();
										}	
									});
									
									if (typeof($(this).attr('data-action'))!='undefined') {
										fnExecAjaxPut( 
											options.ajaxUrl+'?'+options.ajaxData+'&req='+$(this).attr('data-action'), 
											formdata);
									}
									return false;
								}	
		                    	else if (typeof($(this).attr('data-action'))!='undefined') {
						    		fnExecAjaxGet(options.ajaxData+'&req='+$(this).attr('data-action'));
								    return false;
		                    	}	
								
						});

						fnAddButton(aHtmlDiv);
						fnAddTooltip(aHtmlDiv);
						fnAddAccordion(aHtmlDiv);
						fnMakeCollapsible(aHtmlDiv);
					}


					
				}  // function fnAddContent

				// call function to add the contents				
				if ($.isArray(content.contents)) {
					for (var i = 0; i < content.contents.length; i++) {
						// fnAddContent(
						// 	content.contents[i].contenttype,
						// 	content.contents[i].contentoptions,
						// 	content.contents[i].contentdata);
						fnAddContent( content.contents[i] );
					};
				}
				else {
					//fnAddContent(content.contenttype,content.contentoptions,content.contentdata);
					fnAddContent( content );
				}

				$('.c4gGuiCenterDiv').each(function(i,element){
					fnCenterDiv(element);
				});
				
				if (typeof(content.precontent)!='undefined') {
					$(contentDiv).prepend(
							$('<div />').attr('class','c4gGuiPreContent').html(content.precontent));
				}

				if (typeof(content.postcontent)!='undefined') {					
					$('<div />')
					.attr('class','c4gGuiPostContent')
					.html(content.postcontent)
					.appendTo(contentDiv);
				}
			}

			if (typeof(content.dialogclose)!='undefined') {
				if (typeof(content.dialogclose)=='string') {
					fnDialogClose('#c4gGuiDialog'+content.dialogclose);
				}
				else {
					$.each(content.dialogclose,function(index,value){
						fnDialogClose('#c4gGuiDialog'+value);
					});
				}	
			}

			if (content.dialogcloseall) {
				$('.c4gGuiDialog').parent().find(".ui-dialog-titlebar-close").trigger('click');				
			}
			
			if (typeof(content.dialogdata)!='undefined') {
				var dialogoptions = new Object();
				if (typeof(content.dialogoptions)!='undefined')
				{
					dialogoptions = content.dialogoptions;
				}	
				var dialogid = internalId;
				if (typeof(content.dialogid)!='undefined') {
					dialogid = content.dialogid;
				}
				dialogoptions.dialogClass = 'c4gGuiDialogWrapper';
				dialogoptions.position = [
                  $(contentWrapperDiv).parent().offset().left,
                  $(contentWrapperDiv).parent().offset().top
                ];
				if (typeof(dialogoptions.width)=='undefined') {
					dialogoptions.width = $(contentDiv).parent().width();					
				}	
				if (typeof(dialogoptions.height)=='undefined') {
					if ($(contentWrapperDiv).parent().height() < 300) {
						dialogoptions.height = 300;
					} 
					else {
						dialogoptions.height = $(contentWrapperDiv).parent().height();						
					}
				}	

				dialogoptions.close = function() {
					$('#c4gGuiDialog'+dialogid).remove();	
					var state = $(contentDiv).attr('data-state');
					if ((state!="") && (History!=null)) {
						fnHistoryPush(state);
					}
					return true;
				};

				// dialog buttons
				if (typeof(content.dialogbuttons)!='undefined') {
					dialogoptions.buttons = new Array();					
					$(content.dialogbuttons).each(function(index,value){
						var aClass = (value['class'] ? value['class'] : '');
						dialogoptions.buttons.push( {cssClass: aClass,text: value.text,click:function() { 
							if (value.type == 'send') {
								var formdata = new Object();
								wswgEditor.doCheck();
								$('#c4gGuiDialog'+dialogid).find('.formdata').each(function(index,element) {
									$(element).trigger('c4g_before_save');
									if ($(element).attr('type')=='checkbox') {
										formdata[$(element).attr('name')] = ($(element).attr('checked') == 'checked');
									} else {
										formdata[$(element).attr('name')] = $(element).val();
									}	
								});
								
								if (typeof(value.action)!='undefined') {
									fnExecAjaxPut( 
										options.ajaxUrl+'?'+options.ajaxData+'&req='+value.action, 
										formdata);
								}
								return false;
							}
							if (value.type == 'submit') {
								$('#c4gGuiDialog'+dialogid).find('.formlink').each(function(index,element) {
									$(element).trigger('c4g_before_save');
									var newValue = '';
									if (value.action!='clear') {
										newValue = $(element).val();
									}
									var trg = $(element).attr('data-target'); 
									if (trg) {
										var trgAttr = $(element).attr('data-trgattr');
										if (!trgAttr) {
											$(trg).html(newValue);
										}
										else {
											$(trg).attr(trgAttr, newValue);								
										}
									}										
								});									
				
								// close dialog
								fnDialogClose('#c4gGuiDialog'+dialogid);
								
							}
							if (value.type == 'get') {
								if (typeof(value.action)!='undefined') {
									fnExecAjaxGet(options.ajaxData+'&req='+value.action);										
								}
							} 
							return false;								
						}});
					});
				}
				

				var
				   tmpDialogDiv = null,
				   dialogClass = "";
	
				// remove already existing dialogs if any
				$('#c4gGuiDialog'+dialogid).remove();	
							
				if (content.dialogtype=='html')  {
					dialogClass = 'c4gGuiHtml';
					if (typeof(content.usedialog)!='undefined') {
						tmpDialogDiv = $('#c4gGuiDialog'+content.usedialog)
							.attr('id', 'c4gGuiDialog'+dialogid);
						
					} else {
						tmpDialogDiv = $('<div />')
							.attr('id', 'c4gGuiDialog'+dialogid);
					}
				}

				if (content.dialogtype=='form')  {
					dialogClass = 'c4gGuiForm';
					tmpDialogDiv = $('<div />')
						.attr('id', 'c4gGuiDialog'+dialogid);
				}
				
				if (tmpDialogDiv != null) {

					if (options.embedDialogs) {
						if (typeof(content.usedialog)=='undefined') {
							$(contentWrapperDiv).children().last().hide();
							$(buttonDiv).hide();
						}	
						else {
							$(tmpDialogDiv).empty();
						}
						var dialogContentDiv = $('<div />')
							.attr('id', 'c4gGuiDialogContent'+dialogid)
							.html(content.dialogdata)
							.appendTo(tmpDialogDiv);
						
						//TODO use JScrollPane
						//fnAddScrollpane(tmpDialogDiv);
						
						if (options.jquiEmbeddedDialogs) {
							$(dialogContentDiv).attr('class', 'c4gGuiDialogContent c4gGuiDialogContentJqui');
						} else {								
							$(dialogContentDiv).attr('class', 'c4gGuiDialogContent c4gGuiDialogContentNoJqui');
						}
						
						$(tmpDialogDiv)
							.attr('class',dialogClass+' c4gGuiDialog')
							.appendTo(contentWrapperDiv);

						if (typeof(dialogoptions.title)!='undefined') {
							var titleDiv;
							if (options.jquiEmbeddedDialogs) {
								titleDiv = $('<div>').attr('class','c4gGuiDialogTitle c4gGuiDialogTitleJqui ui-widget ui-widget-header ui-corner-all');
								titleDiv.html(dialogoptions.title);
							} else {								
								titleDiv = $('<div>')
									.attr('class','c4gGuiDialogTitle c4gGuiDialogTitleNoJqui')
									.append($('<h1>').html(dialogoptions.title));																
							}
							$(tmpDialogDiv).prepend(titleDiv);							
						}	
						
						var buttonDivClass;
						if (options.jquiEmbeddedDialogs) {
							buttonDivClass = 'c4gGuiDialogButtons c4gGuiDialogButtonsJqui';
						} else {
							buttonDivClass = 'c4gGuiDialogButtons c4gGuiDialogButtonsNoJqui';							
						}
						var dialogButtonDiv = $('<div>').attr('class',buttonDivClass);
						$.each(dialogoptions.buttons,function(index,value) {
							var aLink = $('<a>')
								.attr('href','#')
								.attr('class',value.cssClass)
								.html(value.text)
								.click(value.click)
								.appendTo(dialogButtonDiv);
							if (options.jquiEmbeddedDialogs) {
								aLink.button();
							}	
						});
						$(tmpDialogDiv).append(dialogButtonDiv);

						
					} else {	

						$(tmpDialogDiv).html(content.dialogdata);

						dialogClass = dialogClass + ' c4gGuiScrollable c4gGuiDialog';
						if (typeof(content.usedialog)=='undefined') {
							$(tmpDialogDiv)
								.appendTo(dialogDiv);
						}
						
						//use JScrollPane
						fnAddScrollpane(tmpDialogDiv);
						
						$(tmpDialogDiv)
							.attr('class',dialogClass)
							.dialog(dialogoptions)
							.dialog( { 
								focus: function(event,ui) {
									var state = $(this).attr('data-state');
									if ((state!="") && (History!=null)) {
										fnHistoryPush(state);
									}
								}
						});

					}
					
					$(tmpDialogDiv)
						.attr('data-state', currentState);					
					
					// convert links with class c4gGuiButton to Buttons
					fnAddButton(tmpDialogDiv);					
					
					// toggle checkbox dependent fields on click when data-togglevis attribute is available
					$(tmpDialogDiv)
					.find('input[type="checkbox"]')
					.click(function(){
						var toggle = $(this).attr('data-togglevis');
						if (typeof(toggle)!='undefined') {
							if ($(this).is(':checked'))	{
						  		$(toggle).show();
							} else {
								$(toggle).hide();
							}
						}							  
					});
					
					// ENTER key performs clicks on elements with class "c4gGuiDefaultAction"
					$(tmpDialogDiv).keypress(function(event){
						if (event.which===13) {
							$('#c4gGuiDialog'+dialogid).parent().find('.c4gGuiDefaultAction').each(function(index,element) {
								element.click();
							});
						}
					});
					
					$(tmpDialogDiv)					
					.find('a.c4gGuiAction')
					.click(function() {
						if ($(this).hasClass('c4gGuiSend')) {
							var formdata = new Object();
							$('#c4gGuiDialog'+dialogid).find('.formdata').each(function(index,element) {
								if ($(element).attr('type')=='checkbox') {
									formdata[$(element).attr('name')] = ($(element).attr('checked') == 'checked');
								} else {
									formdata[$(element).attr('name')] = $(element).val();
								}	
							});
							
							if (typeof($(this).attr('data-action'))!='undefined') {
								fnExecAjaxPut( 
									options.ajaxUrl+'?'+options.ajaxData+'&req='+$(this).attr('data-action'), 
									formdata);
							}
							return false;
						} else {							
							if ($(this).hasClass('c4gGuiAction')) {
								if (typeof($(this).attr('data-action'))!='undefined') {
									if (typeof(wswgEditor) != 'undefined') {
										wswgEditor.doCheck();
									}
									fnExecAjaxGet(options.ajaxData+'&req='+$(this).attr('data-action'));										
								}
								return false;
							} else {
								// default processing of link
							}	
						}
					});
					
					if (typeof($.fn.button) == 'function') {	
						$(tmpDialogDiv)
						.find('a.c4gGuiButtonDisabled')
						.button({disabled:true});
					}	

					// get source data for linked fields
					$(tmpDialogDiv)
					.find('.formlink').each(function(index,element) {
						var src = $(element).attr('data-source'); 
						if (src) {
							var srcAttr = $(element).attr('data-srcattr');
							if (!srcAttr) {
								$(element).attr('value', $(src).html());
							}
							else {
								$(element).attr('value', $(src).attr(srcAttr));								
							}
						}
					});		
					fnAddTooltip(tmpDialogDiv);
					fnAddAccordion(tmpDialogDiv);
					fnMakeCollapsible(tmpDialogDiv);
					fnEnableWswgEditor();					
				}
				
			}

			// User Message
			if (typeof(content.usermessage)!='undefined') {
				alert(content.usermessage);
			}
			
			// additional action to be performed via ajax
			if (typeof(content.performaction)!='undefined') {
				fnExecAjaxGet(options.ajaxData+'&req='+content.performaction);														
			}
			
			// show map
			if (typeof(content.mapdata)!='undefined') {
				if (typeof(content.mapdata.width)!='undefined') {
					$('#'+content.mapdata.div).width(content.mapdata.width);					
				}
				if (typeof(content.mapdata.height)!='undefined') {
					$('#'+content.mapdata.div).height(content.mapdata.height);					
				}
				if (content.mapdata.pickGeo) {
					var pickGeo_xCoord = content.mapdata.pickGeo_xCoord;
					var pickGeo_yCoord = content.mapdata.pickGeo_yCoord;					
					content.mapdata.onPickGeo = function(geox, geoy) {
						$(pickGeo_xCoord).attr('value', geox);
						$(pickGeo_yCoord).attr('value', geoy);
					};
					
					content.mapdata.pickGeo_init_xCoord = $(pickGeo_xCoord).attr('value');
					content.mapdata.pickGeo_init_yCoord = $(pickGeo_yCoord).attr('value');
					if ((content.mapdata.pickGeo_init_xCoord) && 
						(content.mapdata.pickGeo_init_yCoord) &&
					    (content.mapdata.pickGeo_initzoom)) {
							// center map on given initial coordinates when pickGeo_initzoom is set
							content.mapdata.calc_extent = 'CENTERZOOM';
							content.mapdata.center_geox = content.mapdata.pickGeo_init_xCoord;
							content.mapdata.center_geoy = content.mapdata.pickGeo_init_yCoord;
							content.mapdata.zoom = content.mapdata.pickGeo_initzoom;						
					}					
					
				}
				if (content.mapdata.editor) {
					content.mapdata.editor_input = 
						$(content.mapdata.editor_field).attr('value');
					var mapEditor = null;
					$(content.mapdata.editor_field).bind('c4g_before_save',function() {
						if (mapEditor != null) {
							var format = new OpenLayers.Format.GeoJSON();
							mapEditor.stopEditMode();							
							$(this).attr('value', format.write(mapEditor.editLayer.features));
						}	
					});
					content.mapdata.onCreateEditor = function(editor) {
						mapEditor = editor;
					};					
				}

				if (typeof(C4GMaps)=='function') {
					C4GMaps(content.mapdata);
					if (options.jquiButtons) {
						if (typeof(jQuery.fn.button) == 'function') {
							$('a.c4gMapsGeoSearchLink').button();
							$('div.c4gMapsGeoSearch').height(30);
							$('input.c4gMapsGeoSearchInput').css('margin-top',4);
							$('a.c4gMapsSearchLink').button();
							$('div.c4gMapsSearch').height(30);
							$('input.c4gMapsSearchInput').css('margin-top',4);
						}
						$('input.c4gMapsGeoSearchInput').addClass('ui-corner-all');
						$('input.c4gMapsSearchInput').addClass('ui-corner-all');
					}	
				}	
			}
			
			if ((typeof(content.cronexec)!='undefined') && (content.cronexec!=null)) {
				var cronexec = content.cronexec;
				if (typeof(cronexec)=='string') {
					cronexec =  new Array(cronexec);
				}
				$.each(cronexec,function(index,element){
					$.ajax({
						  url: options.ajaxUrl,
						  data: options.ajaxData+'&req=cron:'+element,
						  success: function() {},
						  global: false
						});
				})
			}
			
			if (typeof(content.jump_to_url)!='undefined'){
				fnJumpToLink(content.jump_to_url);
			}
			
		};
	
		var fnCenterDiv = function(element) {
			var pWidth = $(element).parent().width();
			var divWidth = 0;
			$(element).children().each(function(i,element) {
				divWidth += $(element).outerWidth(true);
				if ( divWidth > pWidth) {
					divWidth -= $(element).outerWidth(true); 
					return false;
				}
			});
			if (divWidth > 0) {
			    $(element).css({
			    	margin: '0 auto',
			    	width: divWidth+'px'
			    });
			}    
		};
				
		var fnDataTableColumnVis = function(dataTable) {
			if ((typeof(dataTable) != 'undefined') && (dataTable!=null)) {
				var settings = dataTable.fnSettings();
				if (settings!=null) {
					$(settings.aoColumns).each(function(i,element){
						if (typeof(element.c4gMinTableSize)!='undefined') {
							dataTable.fnSetColumnVis(i,dataTable.width()>=element.c4gMinTableSize, false);
						}
						if (typeof(element.c4gMinTableSizeWidths)!='undefined') {
							// set table size dependant column widths
							$(element.c4gMinTableSizeWidths).each(function(tabIndex,tabElement) {
								if (dataTable.width()>=tabElement.tsize) {
									if (element.sWidthOrig != tabElement.width) {
										element.sWidthOrig = tabElement.width;
									}	
									return false;								
								}	
							});
						}					
					});				
				}
				// if ($.browser.msie  && parseInt($.browser.version, 10) === 8) {
				if ($('#top').hasClass('ie8')) {
					// fnAdjustColumnSizing() produces an infinite loop in MSIE 8 (!), so workaround the problem
					dataTable.fnDraw();				
				}
				else {
					dataTable.fnAdjustColumnSizing();
				}	
			}			
		};
		
		// -----------------------------------
		// $.fn.c4gGui()
		// -----------------------------------
		$(window).resize(function(){
			$('.c4gGuiCenterDiv').each(function(i,element){
				fnCenterDiv(element);
			});
			fnDataTableColumnVis(oDataTable);			
		});	

		var oDataTable = null;	// TODO enable more than one
		var _that = this;
		return this.each(function() {

			// if no ID is provided then initialize internal ID for DIVs
			if (typeof(options.id)=='undefined') {				
				options.id = nextId;
			}

			if (options.jquiBreadcrumb || options.jquiButtons || options.jquiEmbeddedDialogs) {
				if (typeof jQuery.ui == 'undefined') {
					  $(this).html('jQuery UI missing!');
					  return;
				}
			}	

			// set height and width if provided
			if (options.height!='') {
				$(this).height(options.height);
			}
			if (options.width!='') {
				$(this).width(options.width);
			}

			// add c4gGui class 
			$(this).attr('class', function(i, val) {
				if (typeof(val)=='undefined') {
					return 'c4gGui';
				} else {
					return val + ' c4gGui';
				}	
			});

			if (typeof(options.titel) != 'undefined') {
				$('<h1 id="c4gGuiTitle">'+options.title+'</h1>').appendTo($(this));
			}	
			$('<h3 id="c4gGuiSubtitle"> </h3>').appendTo($(this));
			

			
			// add Breadcrumb Area
			$('<div />')
				.attr('id', 'c4gGuiBreadcrumb'+options.id)
				.attr('class', 'c4gGuiBreadcrumb')
				.appendTo($(this));

			// add Headline Area
			$('<div />')
				.attr('id', 'c4gGuiHeadline'+options.id)
				.attr('class', 'c4gGuiHeadline')
				.appendTo($(this));

			// add Buttons Area
			buttonDiv = $('<div />')
				.attr('id', 'c4gGuiButtons'+options.id)
				.attr('class', 'c4gGuiButtons')
				.appendTo($(this));
			$(buttonDiv).hide();
			
			// add navigation
			if (options.navPanel) {
				$('<div />')
			  		.attr('id','c4gGuiNavigation'+options.id)
			  		.attr('class','c4gGuiNavigation')
			  		.appendTo($(this));
			}	

			// create DIV for ajax Message
			$(this).append('<div class="c4gGuiAjaxBusyImage"><p><img src="system/modules/con4gis_common/assets/images/loading.gif"></p></div>');
			$(document).ajaxStart(function(){
				$('.c4gGui,.c4gGuiDialog').addClass('c4gGuiAjaxBusy');
				$('.c4gGuiAjaxBusyImage').show();
			}).ajaxStop(function(){
				$('.c4gGui,.c4gGuiDialog').removeClass('c4gGuiAjaxBusy');
				$('.c4gGuiAjaxBusyImage').hide();							
			});

			// create DIV for content
			$('<div />')
				.attr('id','c4gGuiContent'+options.id)
				.attr('class','c4gGuiContent')
				.appendTo(
						$('<div />')
						.attr('id','c4gGuiContentWrapper'+options.id)
						.attr('class','c4gGuiContentWrapper')
						.appendTo(this));

			// create DIV for dialogs
			$('<div />')
				.attr('id','c4gGuiDialog'+options.id)
				.attr('class','c4gGuiDialog')
				.appendTo(this);
			
			if (options.initData) {
				// initialization data exists
				var initData = Object();
				initData.content = options.initData;
				fnHandleAjaxResponse( initData, options.id );
			} else {
				// request initialization from server
				$.ajax({
					  internalId: options.id,
					  url: options.ajaxUrl,
					  data: options.ajaxData+'&req=initnav',
					  dataType: "json",
					  type: "GET",
					  success: function( data ) {
						fnHandleAjaxResponse( data, this.internalId );  
					  },
				  	  error: function(jqXHR, textStatus, errorThrown) {
				  		fnInitContentDiv();
				  		$(this.contentDiv).text('Error: '+errorThrown);
				  	  }
					});
			}
			if (History != null) {
				var internalId = options.id;
			    History.Adapter.bind(window,'statechange',function(){
			    	if (!pushingState) {
				        var State = History.getState(); 
						$.ajax({
							  internalId: internalId,
							  url: options.ajaxUrl,
							  data: options.ajaxData+'&historyreq='+decodeURI(
								        (RegExp('state=(.+?)(&|$)').exec(State.url)||[,null])[1]
							    	),
							  dataType: "json",
							  type: "GET",
							  success: function( data ) {
								fnHandleAjaxResponse( data, this.internalId );  
							  },
						  	  error: function(jqXHR, textStatus, errorThrown) {						  		  
						  		$(this.contentDiv).text('Error: '+errorThrown);
						  	  }
							});
			    	}	
			    });				
			}
						
			// set next Id in case there is more than one element to be set
			options.id++;
			nextId = options.id;
		});
	};

	
})(jQuery);  // single execution function

