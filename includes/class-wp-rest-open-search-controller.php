<?php
/**
 * Class WP_REST_Open_Search_Controller
 */
final class WP_REST_Open_Search_Controller {
	/**
	 * Register the API routes.
	 */
	public static function register_routes() {
		register_rest_route( 'opensearch/1.1', '/document', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( 'WP_REST_Open_Search_Controller', 'get_document' ),
			),
		) );

		register_rest_route( 'opensearch/1.1', '/suggestions', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( 'WP_REST_Open_Search_Controller', 'get_suggestions' ),
				'args' => array(
					's' => array(
						'sanitize_callback' => 'sanitize_key',
					),
				),
			),
		) );
	}

	/**
	 * Hooks into the REST API output to output alternatives to JSON.
	 *
	 * @access private
	 * @since 0.1.0
	 *
	 * @param bool                      $served  Whether the request has already been served.
	 * @param WP_HTTP_ResponseInterface $result  Result to send to the client. Usually a WP_REST_Response.
	 * @param WP_REST_Request           $request Request used to generate the response.
	 * @param WP_REST_Server            $server  Server instance.
	 * @return true
	 */
	public static function serve_request( $served, $result, $request, $server ) {
		if ( '/opensearch/1.1/document' !== $request->get_route() ) {
			return $served;
		}
		if ( 'GET' !== $request->get_method() ) {
			return $served;
		}
		// If someone tries to poll the webmention endpoint return a webmention form.
		if ( ! headers_sent() ) {
			header( 'Access-Control-Allow-Origin: *' );
			header( 'Content-Type: application/opensearchdescription+xml; charset=' . get_bloginfo( 'charset' ), true );
		}

		load_template( dirname( __FILE__ ) . '/../templates/open-search-document.php' );

		return true;
	}

	/**
	 * Callback for our API endpoint.
	 *
	 * Returns the JSON object for the post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public static function get_document( $request ) {
		return true;
	}

	/**
	 * Callback for our API endpoint.
	 *
	 * Returns the JSON object for the post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public static function get_suggestions( $request ) {
		$tags = array();
		$suggestions = array();

		if ( ! isset( $request['s'] ) ) {
			return new WP_Error( 'no_query', __( 'Missing search query', 'open-search-document' ), array( 'status' => 400 ) );
		}

		if ( ! headers_sent() ) {
			header( 'Access-Control-Allow-Origin: *' );
			header( 'Content-Type: application/json; charset=' . get_bloginfo( 'charset' ), true );
		}

		foreach ( get_tags( 'search=' . $request['s'] ) as $tag ) {
			$tags[] = $tag->name;
		}

		$suggestions[] = $request['s'];
		$suggestions[] = $tags;

		return apply_filters( 'open_search_document_suggestions', $suggestions, $request['s'] );
	}
}
