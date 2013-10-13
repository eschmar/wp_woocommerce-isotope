// Cache
var $container = jQuery('ul.products');
var $filter = jQuery('ul.isotope_filter');
var $select = jQuery('select.isotope_select_filter');

// Init isotope
$container.isotope({
	itemSelector: 'li',
	masonry: {
	    columnWidth: 300
	  }
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