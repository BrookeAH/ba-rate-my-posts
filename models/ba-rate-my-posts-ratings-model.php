<?php
/**
 * Class Ba_Rate_My_Posts_Ratings_Model
 *
 * @since      1.0.0
 * @package    Ba_Rate_My_Posts
 * @subpackage Ba_Rate_My_Posts/includes
 * @author     Brooke Adrienne <brookeadriennepro@gmail.com>
 */
namespace Ba_Rate_My_Posts\Models;

class Ba_Rate_My_Posts_Ratings_Model {

    /**
     * Database class
     *
     * @since    1.0.0
     * @access   protected
     * @var      Wordpress wpdb abstract class
     */

    protected $wpdb;

    /**
     * @since    1.0.0
     * @access   protected
     * @var string
     */
    protected $table_name;

    /**
     * @since    1.0.0
     * @access   protected
     * @var
     */
    protected $charset_collate;

    /**
     * row id
     * @since    1.0.0
     * @access   public
     * @var
     */
    public $row_id;

    /**
     * @since    1.0.0
     * @access   public
     * @var
     */
    public $user_id;

    /**
     * @since    1.0.0
     * @access   public
     * @var
     */
    public $post_id;

    /**
     * rating
     * @since    1.0.0
     * @access   public
     * @var
     */
    public $user_rating;

    /**
     * Initialize the protected vars using global wpdb.
     * @since    1.0.0
     */
     public function __construct( $post_id, $user_id ) {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'ba_rate_my_posts_ratings';
        $this->charset_collate = $wpdb->get_charset_collate();
        $this->post_id = intval($post_id);
        $this->user_id = intval($user_id);
        $this->user_rating = $this->get_user_rating();
        $this->row_id = $this->get_row_id();
    }


    /**
     * Takes float rating and rounds to nearest partial - defaults to half
     * @since    1.0.0
     * @param $rating
     * @param $nume_partial_ratings int ( total number of parts per int, defaults to 2 for half ratings )
     * @return float|int
     */
    public static function round_rating( $rating, $num_partial_ratings = 2 ) {
        $r = $rating * $num_partial_ratings;
        $r = round($r);
        return number_format($r / $num_partial_ratings, '1', '.', ',') ;
    }

    /**
     * Retrieves user_rating and returns rounded float or null
     * @since    1.0.0
     * @param $post_id
     * @param $user_id
     * @return float|null
     */
    public function get_user_rating(){

        $result = $this->user_rating_row_for_post( $this->post_id, $this->user_id );

        return $result !== false ? Self::round_rating( floatval($result->rating) ) : null;
    }

    /**
     * Retrieves user_rating and returns rounded float or null
     * @since    1.0.0
     * @param $post_id
     * @param $user_id
     * @return float|null
     */
    public function get_row_id(){

        $result = $this->user_rating_row_for_post( $this->post_id, $this->user_id );

        return $result !== false ? $result->id : null;
    }

    /**
     * Retruns whether user has rated a post
     * @since    1.0.0
     * @param $post_id
     * @param $user_id
     * @return bool
     */
    public function has_rated_post(){

        $result = $this->user_rating_row_for_post( $this->post_id, $this->user_id );

        return $result !== false ? true : false;
    }

    /**
     * Retrieves average rating for a post and returns rounded float
     * @since    1.0.0
     * @param $post_id
     * @return float|null
     */
    public function avg_rating_for_post(){

        $sql = " SELECT AVG(rating) AS rating FROM " . $this->table_name . " WHERE post_id = %d";
        $vars = array( $this->post_id );
        $result = $this->wpdb->get_var(
            $this->wpdb->prepare( $sql , $vars )
        );
        return Self::round_rating( floatval($result) );
    }

    /**
     * Total number of ratings for a post
     * @since    1.0.0
     * @param $post_id
     * @return int
     */
    public function num_ratings_for_post(){

        $sql = " SELECT COUNT(rating) AS rating FROM " . $this->table_name . " WHERE post_id = %d";
        $vars = array( $this->post_id );
        $result = $this->wpdb->get_var(
            $this->wpdb->prepare( $sql , $vars )
        );
        return intval($result);
    }

    /**
     * Retrieves row of user rating for post or false
     * @since    1.0.0
     * @param $post_id
     * @param $user_id
     * @return obj|bool
     */
    public function user_rating_row_for_post(){

        $sql = " SELECT * FROM " . $this->table_name . " WHERE post_id = %d AND user_id = %d ORDER BY id DESC LIMIT 1";
        $vars = array(
            $this->post_id,
            $this->user_id,
        );
        $result = $this->wpdb->get_row(
            $this->wpdb->prepare( $sql , $vars )
        );
        return $result && !empty( $result ) ? $result : false;
    }

    /**
     * Inserts user rating for post or returns false
     * @since    1.0.0
     * @param $post_id int
     * @param $rating float`
     * @param $user_id int
     * @return false|int
     */
    public function insert( $new_rating ){
        $result = $this->wpdb->insert(
            $this->table_name,
            array(
                'post_id' => $this->post_id,
                'rating' => $new_rating,
                'user_id' => $this->user_id,
            ),
            array(
                '%d',
                '%d',
                '%d',
            )
        );
        return $result;
    }

    /**
     * Updates user rating for post or returns false
     * @since    1.0.0
     * @param $id int
     * @param $rating float
     * @return false|int
     */
    public function update( $new_rating ){

        $result = $this->wpdb->update(
            $this->table_name,
            array(
                'rating' => $new_rating,
            ),
            array(
                'id' => $this->row_id,
            )

        );
        return $result;
    }

}
