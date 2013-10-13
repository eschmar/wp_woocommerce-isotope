// Cache
var $container = jQuery('ul.products');
var $filter = jQuery('ul.isotope_filter');

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