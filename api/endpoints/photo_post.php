<?php

function api_photo_post($request) {
  $user = wp_get_current_user();
  $user_id = $user->ID;
  
  if ($user_id === 0) {
    $response = new WP_Error('error', 'Usuário não possui permissão', ['status' => 401]);
    return rest_ensure_response($response);
  }

  $description = sanitize_text_field($request['description']);
  $files = $request->get_files_params();

  if (empty($files)) {
    $response = new WP_Error('error', 'Dados Incompletos', ['status' => 422]);
    return rest_ensure_response($response);
  }

  $response = [
    'post_author' => $user_id,
    'post_type' => 'post',
    'post_status' => 'publish',
    'post_title' => $description,
    'post_content' => $description,
    'meta_input' => [
      'acessos' => 0,
    ]
  ];

  $post_id = wp_insert_post($response);

  require_once ABSPATH . 'wp-admin/includes/image.php'; 
  require_once ABSPATH . 'wp-admin/includes/file.php'; 
  require_once ABSPATH . 'wp-admin/includes/media.php'; 

  $photo_id = media_handle_upload('img', $post_id);
  update_post_meta($post_id, 'img', $photo_id);
  
  return rest_ensure_response($response);
}

function register_api_photo_post() {
  register_rest_route('api', '/photo', [
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'api_photo_post',
  ]);
}
add_action('rest_api_init', 'register_api_photo_post');
?>