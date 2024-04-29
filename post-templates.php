Post Template - CODE SNIPPETS

-Single-Page template call by individually or Specific post single page. Filter Hook. [ Post ]

function custom_single_template($single_template) {
global $post;

// Check if the post type is 'post' and modify the template file
if ($post->post_type == 'post') {
$single_template = locate_template(array("single-custom.php"));

// If the custom template file doesn't exist, fall back to the default single.php
if (!$single_template) {
$single_template = locate_template(array("single.php"));
}
}

return $single_template;
}

add_filter('single_template', 'custom_single_template');

_________
For Specific post


function custom_single_template($single_template) {
global $post;

// Check if the post type is 'post' and the post ID is for the specific post
if ($post->post_type == 'post' && $post->ID == YOUR_POST_ID) {
$single_template = locate_template(array("single-custom.php"));

// If the custom template file doesn't exist, fall back to the default single.php
if (!$single_template) {
$single_template = locate_template(array("single.php"));
}
}

return $single_template;
}

add_filter('single_template', 'custom_single_template');



—-----------------------------------------------------


Archive template override by Category
Default page. Filter Hook. [ Post ]


function custom_archive_template($archive_template) {
if (is_category('your_category_slug')) { // Replace 'your_category_slug' with the actual category slug
$archive_template = locate_template(array("archive-custom.php"));

// If the custom template file doesn't exist, fall back to the default archive.php
if (!$archive_template) {
$archive_template = locate_template(array("archive.php"));
}
}

return $archive_template;
}

add_filter('template_include', 'custom_archive_template');