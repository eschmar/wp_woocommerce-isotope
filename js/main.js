// Cache
var $container = jQuery('ul.products');

// Init isotope
$container.isotope({
	itemSelector: 'li',
	masonry: {
	    columnWidth: 300
	  }
});

// Click event on filter links
jQuery('ul.isotope_filter a').click(function(){
  var selector = jQuery(this).attr('data-filter');
  $container.isotope({ filter: selector });
  return false;
});