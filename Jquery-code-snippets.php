jQuery(document).ready(function ($) {
var maxLength = 150;
var $elementorDescriptions = jQuery('.elementor-icon-box-description');

$elementorDescriptions.each(function () {
var $paragraph = jQuery(this);
var content = $paragraph.text();

if (content.length > maxLength) {
var shortText = content.substr(0, maxLength);
var longText = content.substr(maxLength);

// Store the original content
$paragraph.data('original-text', content);

// Replace the content with the shortened version
$paragraph.html(shortText + '<span class="see-more-link">... <a href="#" class="see-more">See more</a></span>');

// Toggle between short and full versions
$paragraph.on('click', '.see-more', function (e) {
e.preventDefault();

if ($paragraph.hasClass('expanded')) {
$paragraph.html(shortText + '... <a href="#" class="see-more">See more</a>');
} else {
$paragraph.html(longText + ' <a href="#" class="see-less">See less</a>');
}

$paragraph.toggleClass('expanded');
});

// Toggle between full and short versions
$paragraph.on('click', '.see-less', function (e) {
e.preventDefault();
var originalText = $paragraph.data('original-text');
$paragraph.html(shortText + '... <a href="#" class="see-more">See more</a>');
$paragraph.removeClass('expanded');
});
}
});
});