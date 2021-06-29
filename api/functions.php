<?php 

//remove_action('rest_api_init', 'create_initial_rest_routes', 99)

//Endpoints
$dirbase = get_template_directory();

//User Route
require_once $dirbase . '/endpoints/user_post.php';
require_once $dirbase . '/endpoints/user_get.php';

//Photo Route
require_once $dirbase . '/endpoints/photo_post.php';
require_once $dirbase . '/endpoints/photo_delete.php';
require_once $dirbase . '/endpoints/photo_get.php';

//Comment Route
require_once $dirbase . '/endpoints/comment_post.php';
require_once $dirbase . '/endpoints/comment_get.php';

//Update Options for img size
update_option('large_size_w', 1000);
update_option('large_size_h', 1000);
update_option('large_crop', 1);

function change_api() {
  return 'json';
}
add_filter('rest_url_prefix', 'change_api');

function expire_token() {
  return time() + (60 * 60 * 48);
}
add_action('jwt_auth_expire', 'expire_token');

?>