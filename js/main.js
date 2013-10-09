/*
// Initiate Masonry
var $container = jQuery('ul.products');
$container.masonry({
	itemSelector: 'li',
	columnWidth: 300
});

// Masonry instance
var msnry = $container.data('masonry');
*/


jQuery('ul.products').isotope({
	itemSelector: 'li',
	layoutMode: 'cellsByColumn'
});