/**
 * Public JS 
 * v 1.0.0 
 */
(function( $ ) {
	'use strict';

    /**
	 * Create Ba Rate My Posts Rating HTML
	 *
     * @since      1.0.0
     * @param obj { avg_rating, num_ratings, user_rating, icon_class, icon_text }
     */
    function ba_rate_my_posts_create_rating_html( obj ){
    	
        let rating_html = '';
        
        for( let i = 1, current_rating = obj.avg_rating; i <= 5; i++ ){
        	
        	let current_icon_class = current_rating >= i ? obj.icon_class : obj.icon_class + " empty-rating ";
        	
			rating_html += "<span class='" + current_icon_class + "'></span>";
        }
        
        rating_html += '<div class="ba-rate-my-posts-ratings-num-ratings">( ' + obj.num_ratings + ' ratings )</div>';
        
        return rating_html;
    }


    /**
     * Creates html for user's rating.
	 * 
	 * @since      1.0.0
     * @param obj { avg_rating, num_ratings, user_rating, icon_class, icon_text }
	 * @return string
     */
    function ba_rate_my_posts_create_user_rating_div( obj ){
    	
        let rating_html = '';
        
        for( let i = 5, current_rating = obj.user_rating; i >= 1; i-- ) {
        	
            let current_icon_class = current_rating >= i ? obj.icon_class : obj.icon_class + " empty-rating ";

            rating_html += "<span class='" + current_icon_class + "' data-value='" + i + "'></span>";
            
        }

        let title = obj.user_rating === null ? 'No Rating' : '';
        let user_rating = obj.user_rating === null ? '' : obj.user_rating;

        let user_rating_div = '<div class="ba-rate-my-posts-ratings-user-rating ba-rate-my-posts-rating-icons" title="' + title + ' - Click to change">' + rating_html + '</div>';
        user_rating_div += '<div class="ba-rate-my-posts-ratings-user-rating">( Your rating: <span class="user-rating-text">' + user_rating + '</span> )</div>';

        return user_rating_div;
    }

    /**
     * Ajax call for post ratings info
     * then call ba_rate_my_posts_create_user_rating_div and insert html to div
     * if logged in, call ba_rate_my_posts_create_user_rating_div and insert html
     *
     * @since      1.0.0
     * @param selector
     */
    function ba_rate_my_posts_populate_div(selector){

        $.ajax({

            url: wpApiSettings.root + 'ba-rate-my-posts/v1/ratings',

            method: 'GET',

            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
            },

            data: {
                'post_id': ba_rate_my_posts_vars.postID
            }

        }).done(function (response) {

            let ratingsDiv = $(selector);

            ratingsDiv.html( ba_rate_my_posts_create_rating_html( response ) );

            ratingsDiv.attr( 'title', response.avg_rating + ' ' + response.icon_text + 's' );

            if( ba_rate_my_posts_vars.user_logged_in === '1' ){
                ratingsDiv.append( ba_rate_my_posts_create_user_rating_div( response ) );
            }
        });
    }

    /**
     * Ajax call to update rating, 
	 * then call ba_rate_my_posts_populate_div to update rating
	 * 
	 * @since      1.0.0
     * @param rating (int)
     */
    function ba_rate_my_posts_update_user_rating( rating ){
        $.ajax({
			
            url: wpApiSettings.root + 'ba-rate-my-posts/v1/ratings',
			
            method: 'POST',
			
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
            },
			
            data: {
                'post_id': ba_rate_my_posts_vars.postID,
                'rating' : rating,
            }
            
        }).done(function (response) {

            $('.ba-rate-my-posts-ratings-user-rating .user-rating-text').html('').removeClass('dashicons dashicons-update spin');

        	if( !response.error ){
                ba_rate_my_posts_populate_div('#ba-rate-my-posts-rating');
			}else{
                $('.ba-rate-my-posts-ratings-user-rating').html(response.error)
			}
        	

            
        });
    }


    $(document).ready( function() {
    	
        ba_rate_my_posts_populate_div('#ba-rate-my-posts-rating');
        
        $('body').on('click', '.ba-rate-my-posts-ratings-user-rating span', function(e){

            $('.ba-rate-my-posts-ratings-user-rating .user-rating-text').html('').addClass('dashicons dashicons-update spin');

            let rating = $(this).attr('data-value');
            
            ba_rate_my_posts_update_user_rating(rating);
            
        })
    });
})( jQuery );