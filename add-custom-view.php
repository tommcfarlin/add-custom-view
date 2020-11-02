<?php
/**
 * Demo plugin for how to add a custom WordPress view link for all posts tagged
 * with a specific taxonomy. In this case, it's the 'Uncategorized' category.
 *
 * This will add an anchor to the post of the 'All Posts' page allowing users to
 * retrieve all posts that have the uncategorized category.
 *
 * NOTE: Over time, this will be converted to an object-oriented implementation.
 *
 * @author  Tom McFarlin <tom@tommcfarlin.com>
 * @since   01-11-2020
 * @version 01-11-2020
 * @package WordPressCustomViews
 */

namespace WordPressCustomViews;

add_filter( 'views_edit-post', __NAMESPACE__ . '\\add_uncategorized_view', 10, 1 );
/**
 * Renders all of the Uncategorized posts as a custom view in the 'All Posts'
 * page of WordPress.
 *
 * @param array $views  The current list of views available.
 *
 * @return array $views The updated list of views to display.
 *
 * @author Tom McFarlin <tom@tommcfarlin.com>
 * @since  01-11-2020
 */
function add_uncategorized_view( array $views ) : array {
	$category_name = 'Uncategorized';

	// Build the anchor for the 'Uncategorized' view and push it to the $views array.
	array_push(
		$views,
		sprintf('
			<a href="%1$s" %2$s>%3$s <span class="count">(%4$s)</span></a>
			',
			add_query_arg([
				'cat'         => get_cat_ID( $category_name ),
				'post_type'   => 'post',
				'post_status' => 'all',
			], 'edit.php'),
			get_uncategorized_post_attributes( $category_name ),
			$category_name,
			filter_post_results( get_uncategorized_results( $category_name ) ),
		)
	);

	return $views;
}

/**
 * Determines if we're looking at the same category as specified by the query string
 * arguments.
 *
 * @param string $category_name The name of the category.
 *
 * @return string $attrs The attributes to apply to an anchor if it matches the name.
 *
 * @author Tom McFarlin <tom@tommcfarlin.com>
 * @since  01-11-2020
 */
function get_uncategorized_post_attributes( string $category_name ) : string {
	$attrs = 'class=""';

	if ( (int) get_cat_ID( $category_name ) === (int) filter_input( INPUT_GET, 'cat' ) ) {
		$attrs = 'class="current" aria-current="page"';
	}

	return $attrs;
}

/**
 * Determines how many uncategorized posts exist in the database.
 *
 * @param string $name The name of the category for which we want to count.
 *
 * @return int The number of uncategorized psots.
 *
 * @author Tom McFarlin <tom@tommcfarlin.com>
 * @since  0.1.0
 */
function get_uncategorized_results( string $name ) {
	global $wpdb;

	// Get the number of object_ids with the specified category name.
	$results = $wpdb->get_results(
		$wpdb->prepare(
			"
			SELECT object_id FROM {$wpdb->term_relationships}
			WHERE term_taxonomy_id = %d
			",
			get_cat_ID( strtolower( $name ) )
		),
		ARRAY_A
	);

	return $results;
}

/**
 * Returns an array of post IDs.
 *
 * @param array $results The results of all posts/pages with a given category.
 *
 * @return array $results The results with the given category that are only posts.
 *
 * @author Tom McFarlin <tom@tommcfarlin.com>
 * @since  01-11-2020
 */
function filter_post_results( array $results ) : int {
	$post_ids = [];
	foreach ( $results as $result ) {
		if ( 'post' === get_post_type( $result ) ) {
			$post_ids[] = $result['object_id'];
		}
	}
	return count( $post_ids );
}
