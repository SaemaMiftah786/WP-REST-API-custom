<?php



add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}



function get_contributors($post_id){
	$contributor = array();
	$post_contributors = get_post_meta( $post_id, 'post_contributors',true );
	foreach ($post_contributors as $pc) {
		$user = get_user_by('id', $pc);
		$temp1 = array(
				'Contributors ID' => $pc,
				'Contributors Name' => $user->data->display_name,
				'Contributors Email' => $user->data->user_email
			);

		array_push($contributor, $temp1);
		$temp1 = array();
	}
	return $contributor;

}


add_action('rest_api_init', function () {
  register_rest_route( 'twentytwentyone-child/v1', 'latest-posts/',array(
                'methods'  => 'GET',
                'callback' => 'get_latest_posts_with_contributors'
      ));
});


function get_latest_posts_with_contributors($request) {
	
	$posts = get_posts();

	if (empty($posts)) {
    	return new WP_Error( 'empty_posts_contributors', 'There are no posts to display', array('status' => 404) );
	}

    $resp_arr = array();
    
    foreach ($posts as $p){

    	$contributor = get_contributors($p->ID);

    	$temp = array(
    			'Post ID' => $p->ID,
    			'Post Title' => $p->post_title,
    			'Contributors' => $contributor
    			);

    	array_push($resp_arr, $temp);
    	$temp = $contributor = array(); 
    }

    $response = new WP_REST_Response($resp_arr);

    $response->set_status(200);

    return $response;

}




add_action('rest_api_init', function () {
  register_rest_route( 'twentytwentyone-child/v1', 'contributors/(?P<contributor_name>\w+)',array(
                'methods'  => 'GET',
                'callback' => 'get_the_contributor_details'
      ));
});


function get_the_contributor_details($request){
	$contributor_name = $request['contributor_name'];

	$user = get_user_by('slug', $contributor_name);

	$response['Contributors ID'] = $user->data->ID;
	$response['Contributors Name'] = $user->data->display_name;
	$response['Contributors Email'] = $user->data->user_email;

	$posts = get_posts();
	$post_ids = '';
	foreach ($posts as $p) {
		$contributors = get_post_meta( $p->ID, 'post_contributors',true );
		$post_ids .= ( is_array( $contributors ) && in_array( $user->data->ID, $contributors ) ) ? $p->ID . ',' : '';
	}

	$response['Post ID'] = $post_ids;

	$response = new WP_REST_Response($response);

    $response->set_status(200);

    return $response;
}


add_action('rest_api_init', function () {
  register_rest_route( 'twentytwentyone-child/v1', 'create_posts/',array(
                'methods'  => 'POST',
                'callback' => 'create_posts'
      ));
});


function create_posts($request){

	//auth check
	$authenticated = add_filter( 'determine_current_user', 'json_basic_auth_handler', 20 );


	if(isset($authenticated['code'])){
		return $authenticated;
	}


	$contributors_name = $request['contributors_name'];
	$contributors_id = $request['contributors_id'];
	$contributors_email = $request['contributors_email'];
	$post_title = $request['post_title'];
	$post_content = $request['post_content'];

	//check if the contributor exists
	$user = new WP_User($contributors_id);

	if(!empty($user->ID)){

		//create post object
		$my_post = array(
		  'post_title'    => wp_strip_all_tags( $post_title ),
		  'post_content'  => $post_content,
		  'post_status'   => 'draft',
		  'post_author'   => get_user_by('slug', $_SERVER['PHP_AUTH_USER'])->data->ID
		);
		 
		// Insert the post into the database
		$post_id = wp_insert_post( $my_post );

		//now insert contributor
		$data = array($contributors_id);

		$meta_data = add_post_meta($post_id, 'post_contributors', $data);
		$meta_data = add_post_meta($post_id, 'post_approved', 1);

		
		$response['Post ID'] = $post_id;
		$response['Status'] = "Post Created Successfully";

		$response = new WP_REST_Response($response);

    	$response->set_status(200);

		return $response;

	}
	else{
		//no contributor as of now
		//we can create a contributor by using the email and username

		$response['Post ID'] = 0;
		$response['Error'] = 'Invalid Contributor Id';
		$response = new WP_REST_Response($response);

    	$response->set_status(200);

		return $response;
	}

}