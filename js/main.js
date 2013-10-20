// Cache
var $container = jQuery('ul.products');
var $filter = jQuery('ul.isotope_filter');
var $select = jQuery('select.isotope_select_filter');

// Init isotope
$container.isotope({
	itemSelector: 'li',
	resizable: false,
	masonry: { columnWidth: $container.width() / 5 },
	filter: '*'
});

// update columnWidth to a percentage of container width
jQuery(window).smartresize(function(){
  $container.isotope({
    masonry: { columnWidth: $container.width() / 5 }
  });
});

// Click event on filter links
$filter.find('a').click(function(){
	$filter.children('li').removeClass('active');
	jQuery(this).parent().addClass('active');

	var selector = jQuery(this).attr('data-filter');
	$container.isotope({ filter: selector });
	return false;
});

$select.change(function(){
	var selector = jQuery(this).val();
	$container.isotope({ filter: selector });
	return false;
});