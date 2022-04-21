<?php

/**
 * Class Ba_Rate_My_Posts_Rest_Controller
 *
 * All logic necessary for route: ba-rate-my-posts/v1/ratings
 *
 * @since      1.0.0
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/includes
 * @author     Brooke Adrienne <brookeadriennepro@gmail.com>
 */

namespace Ba_Rate_My_Posts;

use Ba_Rate_My_Posts\Models\Ba_Rate_My_Posts_Ratings_Model;

class Ba_Rate_My_Posts_Rest_Controller{

    /**
     * @since    1.0.0
     * @var string
     */
    const rest_namespace = '/ba-rate-my-posts/v1';

    /**
     * @since    1.0.0
     * @var string
     */
    const resource_name = 'ratings';


    /**
     * Register Route for /ratings to accept both GET and POST methods
     * Sets callbacks, arguments, and permissions callbacks for each
     * @since    1.0.0
     */
    public static function register_routes() {

        register_rest_route( Self::rest_namespace, '/' . Self::resource_name , array(
                array(
                    'methods'   => 'GET',
                    'callback'  => __CLASS__ . '::get_rating_for_post' ,
                    'args' => array(
                        'post_id' => array(
                            'validate_callback' => function($param, $request, $key) {
                                return is_numeric( $param );
                            }
                        ),
                    ),
                    'permission_callback' => '__return_true',
                ),
                array(
                    'methods'   => 'POST',
                    'callback'  => __CLASS__ . '::update_user_rating_for_post' ,
                    'args' => array(
                        'post_id' => array(
                            'validate_callback' => function($param, $request, $key) {
                                return is_numeric( $param );
                            }
                        ),
                        'rating' => array(
                            'validate_callback' => function($param, $request, $key) {
                                return Self::validate_rating(intval($param) );
                            }
                        ),
                    ),
                    'permission_callback' => function () {
                        return current_user_can( 'read' );
                    }
                ),

            )
        );

    }

    /**
     * Prettifies text - replaces hyphens (-) with spaces ( ) and capitalizes first letters of words.
     * @since    1.0.0
     * @param $icon string
     * @return string
     */
    public static function get_icon_text( $icon ){

        $icon_text = $icon === 'star-filled' ? ucwords( 'star' ) : ucwords( preg_replace( '/-/', ' ', $icon ) );
        return $icon_text;
    }

    /**
     * Retrieves all information required for front end display of post rating
     * Expects array with at least one element: id (int)
     * Returns Average Rating, Number of Ratings, User's Rating if user is logged in, Icon class, and Icon as text
     * @param WP_REST_Request $request
     * @since    1.0.0
     * @return array ( avg_rating (float), num_ratings (int), user_rating(float|null), icon_class (string), icon_text (string) )
     */
    public static function get_rating_for_post( \WP_REST_Request $request ){


        $post_id = isset($request['post_id']) ? $request['post_id'] : 0 ;
        $user_id = get_current_user_id();

        $model = new Ba_Rate_My_Posts_Ratings_Model( $post_id, $user_id );

        $avg_rating = $model->avg_rating_for_post();
        $num_ratings = $model->num_ratings_for_post();
        $user_rating = $model->user_rating;

        $icon = Ba_Rate_My_Posts::get_icon();
        
        $return_data = array(
            'avg_rating' => $avg_rating,
            'num_ratings' => $num_ratings,
            'user_rating' => $user_rating,
            'icon_class' => 'dashicons dashicons-' . $icon,
            'icon_text' => Self::get_icon_text( $icon ),
        );
        
        return $return_data;
    }

    /**
     * Creates or Updates User rating for a post
     * Expects array with at least two elements: id (int), rating (int)
     * @since    1.0.0
     * @param WP_REST_Request $request
     * @return array ( 'message' (string) | 'error' (string) )
     */
    public static function update_user_rating_for_post( \WP_REST_Request $request ){

        $post_id = isset($request['post_id']) ? $request['post_id'] : 0 ;
        $user_id = get_current_user_id();

        $model = new Ba_Rate_My_Posts_Ratings_Model( $post_id, $user_id );

        $rating = $request['rating'];
        
        if( $model->user_rating === null ){
            
            $result = $model->insert( $rating );
            $message = $result !== false ? 'Rating created.' : 'Error creating rating.';

        }else if($model->user_rating !== $rating){

            $result = $model->update( intval($rating) );
            $message = $result !== 0 ? 'Rating updated.' : 'Error updating rating.';

        }else{
            
            $message = $result = 'Rating unchanged.';
            
        }
        
        $return_data = $result !== false ? array( 'message' => $message ) : array( 'error' => $message );

        return $return_data;

    }

    /**
     * Checks Rating
     * Expects int 1 through 5 or returns false
     * @since    1.0.0
     * @param $rating
     * @return bool
     */
    public static function validate_rating( $rating ) {

        $is_valid = false;

        if( is_numeric( $rating ) && $rating >= 1 && $rating <= 5){
            $is_valid = true;
        }

        return $is_valid;
    }

}
