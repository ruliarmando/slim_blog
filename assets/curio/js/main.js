//document.write('<script src="includes/jquery.cookie.js" type="text/javascript"></script><script src="includes/custom_config.js" type="text/javascript"></script><script src="includes/custom.js" type="text/javascript"></script><link href="includes/custom.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" media="screen" type="text/css" href="includes/colorpicker/colorpicker.css" /><script type="text/javascript" src="includes/colorpicker/colorpicker.js"></script><link id="g_font" href="http://fonts.googleapis.com/css?family=Droid+Sans&v2" rel="stylesheet" type="text/css">');


$(document).ready(function(){
	var under_c = $('.under_construction').is(':visible');

	$('.color_det').click(function(e){
		var color_change = $('.color_change');
		if(color_change.data('animated') != 'true'){
			color_change.data('animated' , 'true');
			var color = $('.color');
			if(color.is(':visible')){
				color.animate( {
					height : 'hide'
				} , 500 , function(){
					$('.color_change').animate( {
						left : '-176px'
					} , 500 , function(){
						color_change.data('animated' , 'false');
						
					});
				});
			}else{			
				$('.color_change').animate( {
					left : '0'
				} , 500 , function(){
					color.animate({
						height : 'show'
					} , 500 , function(){
						color_change.data('animated' , 'false');				
					});				
				});
			}
		}
		e.preventDefault();
	});
	
	
	$('.bg_const a').click(function(e){
		var item = $(this);
		$('body').removeClass('sky yellow black');
		$('body').addClass(item.attr('title'));
		e.preventDefault();
	});
	$('.bg_col a').click(function(e){
		var item = $(this);
		for( col_name in the_colors){
			$('body').removeClass(col_name);
		}
		$('body').addClass(item.attr('title'));
		e.preventDefault();
	});
});

function slidedown_element(el , class1 , class2){
	var parent = el.parent();
	var item = parent.next();
	if(item.hasClass(class1)){
		item.animate({height : 'hide'} , 500 , function(){
			item.removeClass(class1);
			parent.removeClass(class2);
		})
	}else{
		parent.addClass(class2);
		item.animate({height : 'show'} , 500 , function(){
			item.addClass(class1);
		})
	}
}

// Under construction timeout function
function get_the_timeout(){
	var today = new Date();
	var _diff = target_date - today;
	var time_diff_seconds = _diff / 1000;
	var time_diff_minutes = time_diff_seconds / 60;
	var time_diff_hours   = time_diff_minutes / 60;
	var D = {};
	D.weeks  = Math.floor(time_diff_hours / 168);
	D.days  = Math.floor((time_diff_hours / 24) - (D.weeks * 7));
	var d24 = D.days * 24;
	D.hours = Math.floor(time_diff_hours - d24);
	D.minutes = Math.floor(time_diff_minutes - ((D.hours + d24) * 60));
	D.seconds = Math.floor((time_diff_seconds - (D.hours + d24) * 3600) - (D.minutes * 60));
	for( p in D ){
		var the_value = D[p];
		if(the_value >= 0 && the_value < 10) D[p] = '0' + the_value;
	}
	return D;
}
// Under construction timeout function END

$(function () {
	// Under construction timeout init
	if(window.target_date){
		var _weeks   = $('#weeks');
		var _days    = $('#days');
		var _hours   = $('#hours');
		var _minutes = $('#minutes');
		var _seconds = $('#seconds');
		
		var t = setInterval( function(){
			var D = get_the_timeout();
			_days.text(D.days);
			_hours.text(D.hours);
			_minutes.text(D.minutes);
			_seconds.text(D.seconds);
			_weeks.text(D.weeks);
		} , 1000);// Under construction timeout init END
	}	
	$('.menu ul:eq(0)').children().addClass('top_menu');
	$('.top_menu:has(ul.children)').addClass('has_menu_p').append('<div class="has_menu"></div>');	
});

$('.log').live('click', function(e){
	var item = $(this), cl_name = 'log_active';
	if(item.hasClass(cl_name)){
		$('.log_hide, .log_hide .center').animate({
			height : 6
		}, 1000 , function(){
			item.removeClass(cl_name);	
		});
		$('.login_container').animate({
			height : 'hide'
		}, 1000);
	}else{
		$('.log_hide, .log_hide .center').animate({
			height : 115
		}, 1000 , function(){
			item.addClass(cl_name);	
		});
		$('.login_container').animate({
			height : 'show'
		}, 1000);
		
	}
	e.preventDefault();
});


// Menu hover
var t, t1;
$('.page-item')
	.live('mouseenter', function(){
		clearTimeout(t);
		$('ul.children').hide();
		$(this).find('ul.children:eq(0)').show();
	}).live('mouseleave', function(){
		t = setTimeout(function(){
			$('ul.children').hide();
		}, 250)
	});
$('.top_menu .page_item')
	.live('mouseenter', function(e){
		clearTimeout(t);
		$(this).closest('.children').find('ul.children').hide()
		$(this).find('ul.children:eq(0)').show();
	})
	.live('mouseleave', function(){
		
	})

// Menu hover END

// Code show
$('.show_code a').live('click' , function(e){
	var item = $(this).parent();
	
	if(item.hasClass('show_code_active')){//hide code
		item.removeClass('show_code_active').next('.code_block_cont').animate({
			height : 'hide'
		} , 700);
	}else{// show code
		item.addClass('show_code_active').next('.code_block_cont').animate({
			height : 'show'
		} , 700);	
	}
	e.preventDefault();
});

// Code show END

// table interaction
$('.white_table td, .white_table th')
	.live('mouseenter' , function(){
		var the_parent = $('.white_table');
		the_parent.find('*').removeClass('border_blue');
		var index = $(this).index();
		the_parent.find('tr').each(function(i){
			var item = $(this);
			if(item.hasClass('grey')){
				item.find('th:eq('+index+') , td:eq('+index+')').addClass('w_table_l_grey');
			}else{
				item.find('th:eq('+index+') , td:eq('+index+')').addClass('w_table_d_grey');
			}
		});
	})
	.live('mouseleave' , function(){
		$('.white_table').find('*').removeClass('w_table_d_grey').removeClass('w_table_l_grey');
	});
	
$('.black_table td, .black_table th')
	.live('mouseenter' , function(){
		var the_parent = $('.black_table');
		
		the_parent.find('*').removeClass('pricing_blue_btn');
		var index = $(this).index();
		the_parent.find('tr').each(function(i){
			var item = $(this);
			if(item.hasClass('grey')){
				item.find('th:eq('+index+') , td:eq('+index+')').addClass('d_table_l_grey');
			}else{
				item.find('th:eq('+index+') , td:eq('+index+')').addClass('d_table_d_grey');
			}
		});
	})
	.live('mouseleave' , function(){
		$('.black_table').find('*').removeClass('d_table_l_grey').removeClass('d_table_d_grey');
	});
// table interaction END 


// IE css3 init
	$(function() {
		if (window.PIE) {
			function addPie(){
				$('#show_hide, #customization, .menu ul .current_page_item.top_menu a:first, .the_button, ').each(function() {
					PIE.attach(this);
				});
			}
			function checkPie(){
				//alert($('.menu ul .current_page_item a').attr('class'));
				
				if($('#show_hide').attr('class') == undefined){
					setTimeout(function(){
						 checkPie();
					} , 100);
				}else{
					addPie();
				}	
			
			}
			checkPie();
		}
	});
// IE css3 init end
