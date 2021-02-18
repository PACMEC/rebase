<?php
/**
 * paCMec Export Administration API
 *
 * @package paCMec
 * @subpackage Administration
 */

/**
 * Version number for the export format.
 *
 * Bump this when something changes that might affect compatibility.
 *
 * @since 2.5.0
 */
define( 'WXR_VERSION', '1.2' );

/**
 * Generates the WXR export file for download.
 *
 * Default behavior is to export all content, however, note that post content will only
 * be exported for post types with the `can_export` argument enabled. Any posts with the
 * 'auto-draft' status will be skipped.
 *
 * @since 2.1.0
 * @since 5.7.0 Added the `post_modified` and `post_modified_gmt` fields to the export file.
 *
 * @global mtdb    $mtdb paCMec database abstraction object.
 * @global MT_Post $post Global post object.
 *
 * @param array $args {
 *     Optional. Arguments for generating the WXR export file for download. Default empty array.
 *
 *     @type string $content        Type of content to export. If set, only the post content of this post type
 *                                  will be exported. Accepts 'all', 'post', 'page', 'attachment', or a defined
 *                                  custom post. If an invalid custom post type is supplied, every post type for
 *                                  which `can_export` is enabled will be exported instead. If a valid custom post
 *                                  type is supplied but `can_export` is disabled, then 'posts' will be exported
 *                                  instead. When 'all' is supplied, only post types with `can_export` enabled will
 *                                  be exported. Default 'all'.
 *     @type string $author         Author to export content for. Only used when `$content` is 'post', 'page', or
 *                                  'attachment'. Accepts false (all) or a specific author ID. Default false (all).
 *     @type string $category       Category (slug) to export content for. Used only when `$content` is 'post'. If
 *                                  set, only post content assigned to `$category` will be exported. Accepts false
 *                                  or a specific category slug. Default is false (all categories).
 *     @type string $start_date     Start date to export content from. Expected date format is 'Y-m-d'. Used only
 *                                  when `$content` is 'post', 'page' or 'attachment'. Default false (since the
 *                                  beginning of time).
 *     @type string $end_date       End date to export content to. Expected date format is 'Y-m-d'. Used only when
 *                                  `$content` is 'post', 'page' or 'attachment'. Default false (latest publish date).
 *     @type string $status         Post status to export posts for. Used only when `$content` is 'post' or 'page'.
 *                                  Accepts false (all statuses except 'auto-draft'), or a specific status, i.e.
 *                                  'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', or
 *                                  'trash'. Default false (all statuses except 'auto-draft').
 * }
 */
function export_mt( $args = array() ) {
	global $mtdb, $post;

	$defaults = array(
		'content'    => 'all',
		'author'     => false,
		'category'   => false,
		'start_date' => false,
		'end_date'   => false,
		'status'     => false,
	);
	$args     = mt_parse_args( $args, $defaults );

	/**
	 * Fires at the beginning of an export, before any headers are sent.
	 *
	 * @since 2.3.0
	 *
	 * @param array $args An array of export arguments.
	 */
	do_action( 'export_mt', $args );

	$sitename = sanitize_key( get_bloginfo( 'name' ) );
	if ( ! empty( $sitename ) ) {
		$sitename .= '.';
	}
	$date        = gmdate( 'Y-m-d' );
	$mt_filename = $sitename . 'paCMec.' . $date . '.xml';
	/**
	 * Filters the export filename.
	 *
	 * @since 4.4.0
	 *
	 * @param string $mt_filename The name of the file for download.
	 * @param string $sitename    The site name.
	 * @param string $date        Today's date, formatted.
	 */
	$filename = apply_filters( 'export_mt_filename', $mt_filename, $sitename, $date );

	header( 'Content-Description: File Transfer' );
	header( 'Content-Disposition: attachment; filename=' . $filename );
	header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

	if ( 'all' !== $args['content'] && post_type_exists( $args['content'] ) ) {
		$ptype = get_post_type_object( $args['content'] );
		if ( ! $ptype->can_export ) {
			$args['content'] = 'post';
		}

		$where = $mtdb->prepare( "{$mtdb->posts}.post_type = %s", $args['content'] );
	} else {
		$post_types = get_post_types( array( 'can_export' => true ) );
		$esses      = array_fill( 0, count( $post_types ), '%s' );

		// phpcs:ignore paCMec.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		$where = $mtdb->prepare( "{$mtdb->posts}.post_type IN (" . implode( ',', $esses ) . ')', $post_types );
	}

	if ( $args['status'] && ( 'post' === $args['content'] || 'page' === $args['content'] ) ) {
		$where .= $mtdb->prepare( " AND {$mtdb->posts}.post_status = %s", $args['status'] );
	} else {
		$where .= " AND {$mtdb->posts}.post_status != 'auto-draft'";
	}

	$join = '';
	if ( $args['category'] && 'post' === $args['content'] ) {
		$term = term_exists( $args['category'], 'category' );
		if ( $term ) {
			$join   = "INNER JOIN {$mtdb->term_relationships} ON ({$mtdb->posts}.ID = {$mtdb->term_relationships}.object_id)";
			$where .= $mtdb->prepare( " AND {$mtdb->term_relationships}.term_taxonomy_id = %d", $term['term_taxonomy_id'] );
		}
	}

	if ( in_array( $args['content'], array( 'post', 'page', 'attachment' ), true ) ) {
		if ( $args['author'] ) {
			$where .= $mtdb->prepare( " AND {$mtdb->posts}.post_author = %d", $args['author'] );
		}

		if ( $args['start_date'] ) {
			$where .= $mtdb->prepare( " AND {$mtdb->posts}.post_date >= %s", gmdate( 'Y-m-d', strtotime( $args['start_date'] ) ) );
		}

		if ( $args['end_date'] ) {
			$where .= $mtdb->prepare( " AND {$mtdb->posts}.post_date < %s", gmdate( 'Y-m-d', strtotime( '+1 month', strtotime( $args['end_date'] ) ) ) );
		}
	}

	// Grab a snapshot of post IDs, just in case it changes during the export.
	$post_ids = $mtdb->get_col( "SELECT ID FROM {$mtdb->posts} $join WHERE $where" );

	/*
	 * Get the requested terms ready, empty unless posts filtered by category
	 * or all content.
	 */
	$cats  = array();
	$tags  = array();
	$terms = array();
	if ( isset( $term ) && $term ) {
		$cat  = get_term( $term['term_id'], 'category' );
		$cats = array( $cat->term_id => $cat );
		unset( $term, $cat );
	} elseif ( 'all' === $args['content'] ) {
		$categories = (array) get_categories( array( 'get' => 'all' ) );
		$tags       = (array) get_tags( array( 'get' => 'all' ) );

		$custom_taxonomies = get_taxonomies( array( '_builtin' => false ) );
		$custom_terms      = (array) get_terms(
			array(
				'taxonomy' => $custom_taxonomies,
				'get'      => 'all',
			)
		);

		// Put categories in order with no child going before its parent.
		while ( $cat = array_shift( $categories ) ) {
			if ( 0 == $cat->parent || isset( $cats[ $cat->parent ] ) ) {
				$cats[ $cat->term_id ] = $cat;
			} else {
				$categories[] = $cat;
			}
		}

		// Put terms in order with no child going before its parent.
		while ( $t = array_shift( $custom_terms ) ) {
			if ( 0 == $t->parent || isset( $terms[ $t->parent ] ) ) {
				$terms[ $t->term_id ] = $t;
			} else {
				$custom_terms[] = $t;
			}
		}

		unset( $categories, $custom_taxonomies, $custom_terms );
	}

	/**
	 * Wrap given string in XML CDATA tag.
	 *
	 * @since 2.1.0
	 *
	 * @param string $str String to wrap in XML CDATA tag.
	 * @return string
	 */
	function wxr_cdata( $str ) {
		if ( ! seems_utf8( $str ) ) {
			$str = utf8_encode( $str );
		}
		// $str = ent2ncr(esc_html($str));
		$str = '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $str ) . ']]>';

		return $str;
	}

	/**
	 * Return the URL of the site
	 *
	 * @since 2.5.0
	 *
	 * @return string Site URL.
	 */
	function wxr_site_url() {
		if ( is_multisite() ) {
			// Multisite: the base URL.
			return network_home_url();
		} else {
			// paCMec (single site): the blog URL.
			return get_bloginfo_rss( 'url' );
		}
	}

	/**
	 * Output a cat_name XML tag from a given category object
	 *
	 * @since 2.1.0
	 *
	 * @param MT_Term $category Category Object
	 */
	function wxr_cat_name( $category ) {
		if ( empty( $category->name ) ) {
			return;
		}

		echo '<mt:cat_name>' . wxr_cdata( $category->name ) . "</mt:cat_name>\n";
	}

	/**
	 * Output a category_description XML tag from a given category object
	 *
	 * @since 2.1.0
	 *
	 * @param MT_Term $category Category Object
	 */
	function wxr_category_description( $category ) {
		if ( empty( $category->description ) ) {
			return;
		}

		echo '<mt:category_description>' . wxr_cdata( $category->description ) . "</mt:category_description>\n";
	}

	/**
	 * Output a tag_name XML tag from a given tag object
	 *
	 * @since 2.3.0
	 *
	 * @param MT_Term $tag Tag Object
	 */
	function wxr_tag_name( $tag ) {
		if ( empty( $tag->name ) ) {
			return;
		}

		echo '<mt:tag_name>' . wxr_cdata( $tag->name ) . "</mt:tag_name>\n";
	}

	/**
	 * Output a tag_description XML tag from a given tag object
	 *
	 * @since 2.3.0
	 *
	 * @param MT_Term $tag Tag Object
	 */
	function wxr_tag_description( $tag ) {
		if ( empty( $tag->description ) ) {
			return;
		}

		echo '<mt:tag_description>' . wxr_cdata( $tag->description ) . "</mt:tag_description>\n";
	}

	/**
	 * Output a term_name XML tag from a given term object
	 *
	 * @since 2.9.0
	 *
	 * @param MT_Term $term Term Object
	 */
	function wxr_term_name( $term ) {
		if ( empty( $term->name ) ) {
			return;
		}

		echo '<mt:term_name>' . wxr_cdata( $term->name ) . "</mt:term_name>\n";
	}

	/**
	 * Output a term_description XML tag from a given term object
	 *
	 * @since 2.9.0
	 *
	 * @param MT_Term $term Term Object
	 */
	function wxr_term_description( $term ) {
		if ( empty( $term->description ) ) {
			return;
		}

		echo "\t\t<mt:term_description>" . wxr_cdata( $term->description ) . "</mt:term_description>\n";
	}

	/**
	 * Output term meta XML tags for a given term object.
	 *
	 * @since 4.6.0
	 *
	 * @param MT_Term $term Term object.
	 */
	function wxr_term_meta( $term ) {
		global $mtdb;

		$termmeta = $mtdb->get_results( $mtdb->prepare( "SELECT * FROM $mtdb->termmeta WHERE term_id = %d", $term->term_id ) );

		foreach ( $termmeta as $meta ) {
			/**
			 * Filters whether to selectively skip term meta used for WXR exports.
			 *
			 * Returning a truthy value from the filter will skip the current meta
			 * object from being exported.
			 *
			 * @since 4.6.0
			 *
			 * @param bool   $skip     Whether to skip the current piece of term meta. Default false.
			 * @param string $meta_key Current meta key.
			 * @param object $meta     Current meta object.
			 */
			if ( ! apply_filters( 'wxr_export_skip_termmeta', false, $meta->meta_key, $meta ) ) {
				printf( "\t\t<mt:termmeta>\n\t\t\t<mt:meta_key>%s</mt:meta_key>\n\t\t\t<mt:meta_value>%s</mt:meta_value>\n\t\t</mt:termmeta>\n", wxr_cdata( $meta->meta_key ), wxr_cdata( $meta->meta_value ) );
			}
		}
	}

	/**
	 * Output list of authors with posts
	 *
	 * @since 3.1.0
	 *
	 * @global mtdb $mtdb paCMec database abstraction object.
	 *
	 * @param int[] $post_ids Optional. Array of post IDs to filter the query by.
	 */
	function wxr_authors_list( array $post_ids = null ) {
		global $mtdb;

		if ( ! empty( $post_ids ) ) {
			$post_ids = array_map( 'absint', $post_ids );
			$and      = 'AND ID IN ( ' . implode( ', ', $post_ids ) . ')';
		} else {
			$and = '';
		}

		$authors = array();
		$results = $mtdb->get_results( "SELECT DISTINCT post_author FROM $mtdb->posts WHERE post_status != 'auto-draft' $and" );
		foreach ( (array) $results as $result ) {
			$authors[] = get_userdata( $result->post_author );
		}

		$authors = array_filter( $authors );

		foreach ( $authors as $author ) {
			echo "\t<mt:author>";
			echo '<mt:author_id>' . (int) $author->ID . '</mt:author_id>';
			echo '<mt:author_login>' . wxr_cdata( $author->user_login ) . '</mt:author_login>';
			echo '<mt:author_email>' . wxr_cdata( $author->user_email ) . '</mt:author_email>';
			echo '<mt:author_display_name>' . wxr_cdata( $author->display_name ) . '</mt:author_display_name>';
			echo '<mt:author_first_name>' . wxr_cdata( $author->first_name ) . '</mt:author_first_name>';
			echo '<mt:author_last_name>' . wxr_cdata( $author->last_name ) . '</mt:author_last_name>';
			echo "</mt:author>\n";
		}
	}

	/**
	 * Output all navigation menu terms
	 *
	 * @since 3.1.0
	 */
	function wxr_nav_menu_terms() {
		$nav_menus = mt_get_nav_menus();
		if ( empty( $nav_menus ) || ! is_array( $nav_menus ) ) {
			return;
		}

		foreach ( $nav_menus as $menu ) {
			echo "\t<mt:term>";
			echo '<mt:term_id>' . (int) $menu->term_id . '</mt:term_id>';
			echo '<mt:term_taxonomy>nav_menu</mt:term_taxonomy>';
			echo '<mt:term_slug>' . wxr_cdata( $menu->slug ) . '</mt:term_slug>';
			wxr_term_name( $menu );
			echo "</mt:term>\n";
		}
	}

	/**
	 * Output list of taxonomy terms, in XML tag format, associated with a post
	 *
	 * @since 2.3.0
	 */
	function wxr_post_taxonomy() {
		$post = get_post();

		$taxonomies = get_object_taxonomies( $post->post_type );
		if ( empty( $taxonomies ) ) {
			return;
		}
		$terms = mt_get_object_terms( $post->ID, $taxonomies );

		foreach ( (array) $terms as $term ) {
			echo "\t\t<category domain=\"{$term->taxonomy}\" nicename=\"{$term->slug}\">" . wxr_cdata( $term->name ) . "</category>\n";
		}
	}

	/**
	 * @param bool   $return_me
	 * @param string $meta_key
	 * @return bool
	 */
	function wxr_filter_postmeta( $return_me, $meta_key ) {
		if ( '_edit_lock' === $meta_key ) {
			$return_me = true;
		}
		return $return_me;
	}
	add_filter( 'wxr_export_skip_postmeta', 'wxr_filter_postmeta', 10, 2 );

	echo '<?xml version="1.0" encoding="' . get_bloginfo( 'charset' ) . "\" ?>\n";

	?>
<!-- This is a paCMec eXtended RSS file generated by paCMec as an export of your site. -->
<!-- It contains information about your site's posts, pages, comments, categories, and other content. -->
<!-- You may use this file to transfer that content from one site to another. -->
<!-- This file is not intended to serve as a complete backup of your site. -->

<!-- To import this information into a paCMec site follow these steps: -->
<!-- 1. Log in to that site as an administrator. -->
<!-- 2. Go to Tools: Import in the paCMec admin panel. -->
<!-- 3. Install the "paCMec" importer from the list. -->
<!-- 4. Activate & Run Importer. -->
<!-- 5. Upload this file using the form provided on that page. -->
<!-- 6. You will first be asked to map the authors in this export file to users -->
<!--    on the site. For each author, you may choose to map to an -->
<!--    existing user on the site or to create a new user. -->
<!-- 7. paCMec will then import each of the posts, pages, comments, categories, etc. -->
<!--    contained in this file into your site. -->

	<?php the_generator( 'export' ); ?>
<rss version="2.0"
	xmlns:excerpt="http://managertechnology.org/export/<?php echo WXR_VERSION; ?>/excerpt/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:mt="http://managertechnology.org/export/<?php echo WXR_VERSION; ?>/"
>

<channel>
	<title><?php bloginfo_rss( 'name' ); ?></title>
	<link><?php bloginfo_rss( 'url' ); ?></link>
	<description><?php bloginfo_rss( 'description' ); ?></description>
	<pubDate><?php echo gmdate( 'D, d M Y H:i:s +0000' ); ?></pubDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<mt:wxr_version><?php echo WXR_VERSION; ?></mt:wxr_version>
	<mt:base_site_url><?php echo wxr_site_url(); ?></mt:base_site_url>
	<mt:base_blog_url><?php bloginfo_rss( 'url' ); ?></mt:base_blog_url>

	<?php wxr_authors_list( $post_ids ); ?>

	<?php foreach ( $cats as $c ) : ?>
	<mt:category>
		<mt:term_id><?php echo (int) $c->term_id; ?></mt:term_id>
		<mt:category_nicename><?php echo wxr_cdata( $c->slug ); ?></mt:category_nicename>
		<mt:category_parent><?php echo wxr_cdata( $c->parent ? $cats[ $c->parent ]->slug : '' ); ?></mt:category_parent>
		<?php
		wxr_cat_name( $c );
		wxr_category_description( $c );
		wxr_term_meta( $c );
		?>
	</mt:category>
	<?php endforeach; ?>
	<?php foreach ( $tags as $t ) : ?>
	<mt:tag>
		<mt:term_id><?php echo (int) $t->term_id; ?></mt:term_id>
		<mt:tag_slug><?php echo wxr_cdata( $t->slug ); ?></mt:tag_slug>
		<?php
		wxr_tag_name( $t );
		wxr_tag_description( $t );
		wxr_term_meta( $t );
		?>
	</mt:tag>
	<?php endforeach; ?>
	<?php foreach ( $terms as $t ) : ?>
	<mt:term>
		<mt:term_id><?php echo (int) $t->term_id; ?></mt:term_id>
		<mt:term_taxonomy><?php echo wxr_cdata( $t->taxonomy ); ?></mt:term_taxonomy>
		<mt:term_slug><?php echo wxr_cdata( $t->slug ); ?></mt:term_slug>
		<mt:term_parent><?php echo wxr_cdata( $t->parent ? $terms[ $t->parent ]->slug : '' ); ?></mt:term_parent>
		<?php
		wxr_term_name( $t );
		wxr_term_description( $t );
		wxr_term_meta( $t );
		?>
	</mt:term>
	<?php endforeach; ?>
	<?php
	if ( 'all' === $args['content'] ) {
		wxr_nav_menu_terms();}
	?>

	<?php
	/** This action is documented in mt-includes/feed-rss2.php */
	do_action( 'rss2_head' );
	?>

	<?php
	if ( $post_ids ) {
		/**
		 * @global MT_Query $mt_query paCMec Query object.
		 */
		global $mt_query;

		// Fake being in the loop.
		$mt_query->in_the_loop = true;

		// Fetch 20 posts at a time rather than loading the entire table into memory.
		while ( $next_posts = array_splice( $post_ids, 0, 20 ) ) {
			$where = 'WHERE ID IN (' . implode( ',', $next_posts ) . ')';
			$posts = $mtdb->get_results( "SELECT * FROM {$mtdb->posts} $where" );

			// Begin Loop.
			foreach ( $posts as $post ) {
				setup_postdata( $post );

				/**
				 * Filters the post title used for WXR exports.
				 *
				 * @since 5.7.0
				 *
				 * @param string $post_title Title of the current post.
				 */
				$title = wxr_cdata( apply_filters( 'the_title_export', $post->post_title ) );

				/**
				 * Filters the post content used for WXR exports.
				 *
				 * @since 2.5.0
				 *
				 * @param string $post_content Content of the current post.
				 */
				$content = wxr_cdata( apply_filters( 'the_content_export', $post->post_content ) );

				/**
				 * Filters the post excerpt used for WXR exports.
				 *
				 * @since 2.6.0
				 *
				 * @param string $post_excerpt Excerpt for the current post.
				 */
				$excerpt = wxr_cdata( apply_filters( 'the_excerpt_export', $post->post_excerpt ) );

				$is_sticky = is_sticky( $post->ID ) ? 1 : 0;
				?>
	<item>
		<title><?php echo $title; ?></title>
		<link><?php the_permalink_rss(); ?></link>
		<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
		<dc:creator><?php echo wxr_cdata( get_the_author_meta( 'login' ) ); ?></dc:creator>
		<guid isPermaLink="false"><?php the_guid(); ?></guid>
		<description></description>
		<content:encoded><?php echo $content; ?></content:encoded>
		<excerpt:encoded><?php echo $excerpt; ?></excerpt:encoded>
		<mt:post_id><?php echo (int) $post->ID; ?></mt:post_id>
		<mt:post_date><?php echo wxr_cdata( $post->post_date ); ?></mt:post_date>
		<mt:post_date_gmt><?php echo wxr_cdata( $post->post_date_gmt ); ?></mt:post_date_gmt>
		<mt:post_modified><?php echo wxr_cdata( $post->post_modified ); ?></mt:post_modified>
		<mt:post_modified_gmt><?php echo wxr_cdata( $post->post_modified_gmt ); ?></mt:post_modified_gmt>
		<mt:comment_status><?php echo wxr_cdata( $post->comment_status ); ?></mt:comment_status>
		<mt:ping_status><?php echo wxr_cdata( $post->ping_status ); ?></mt:ping_status>
		<mt:post_name><?php echo wxr_cdata( $post->post_name ); ?></mt:post_name>
		<mt:status><?php echo wxr_cdata( $post->post_status ); ?></mt:status>
		<mt:post_parent><?php echo (int) $post->post_parent; ?></mt:post_parent>
		<mt:menu_order><?php echo (int) $post->menu_order; ?></mt:menu_order>
		<mt:post_type><?php echo wxr_cdata( $post->post_type ); ?></mt:post_type>
		<mt:post_password><?php echo wxr_cdata( $post->post_password ); ?></mt:post_password>
		<mt:is_sticky><?php echo (int) $is_sticky; ?></mt:is_sticky>
				<?php	if ( 'attachment' === $post->post_type ) : ?>
		<mt:attachment_url><?php echo wxr_cdata( mt_get_attachment_url( $post->ID ) ); ?></mt:attachment_url>
	<?php endif; ?>
				<?php wxr_post_taxonomy(); ?>
				<?php
				$postmeta = $mtdb->get_results( $mtdb->prepare( "SELECT * FROM $mtdb->postmeta WHERE post_id = %d", $post->ID ) );
				foreach ( $postmeta as $meta ) :
					/**
					 * Filters whether to selectively skip post meta used for WXR exports.
					 *
					 * Returning a truthy value from the filter will skip the current meta
					 * object from being exported.
					 *
					 * @since 3.3.0
					 *
					 * @param bool   $skip     Whether to skip the current post meta. Default false.
					 * @param string $meta_key Current meta key.
					 * @param object $meta     Current meta object.
					 */
					if ( apply_filters( 'wxr_export_skip_postmeta', false, $meta->meta_key, $meta ) ) {
						continue;
					}
					?>
		<mt:postmeta>
		<mt:meta_key><?php echo wxr_cdata( $meta->meta_key ); ?></mt:meta_key>
		<mt:meta_value><?php echo wxr_cdata( $meta->meta_value ); ?></mt:meta_value>
		</mt:postmeta>
					<?php
	endforeach;

				$_comments = $mtdb->get_results( $mtdb->prepare( "SELECT * FROM $mtdb->comments WHERE comment_post_ID = %d AND comment_approved <> 'spam'", $post->ID ) );
				$comments  = array_map( 'get_comment', $_comments );
				foreach ( $comments as $c ) :
					?>
		<mt:comment>
			<mt:comment_id><?php echo (int) $c->comment_ID; ?></mt:comment_id>
			<mt:comment_author><?php echo wxr_cdata( $c->comment_author ); ?></mt:comment_author>
			<mt:comment_author_email><?php echo wxr_cdata( $c->comment_author_email ); ?></mt:comment_author_email>
			<mt:comment_author_url><?php echo esc_url_raw( $c->comment_author_url ); ?></mt:comment_author_url>
			<mt:comment_author_IP><?php echo wxr_cdata( $c->comment_author_IP ); ?></mt:comment_author_IP>
			<mt:comment_date><?php echo wxr_cdata( $c->comment_date ); ?></mt:comment_date>
			<mt:comment_date_gmt><?php echo wxr_cdata( $c->comment_date_gmt ); ?></mt:comment_date_gmt>
			<mt:comment_content><?php echo wxr_cdata( $c->comment_content ); ?></mt:comment_content>
			<mt:comment_approved><?php echo wxr_cdata( $c->comment_approved ); ?></mt:comment_approved>
			<mt:comment_type><?php echo wxr_cdata( $c->comment_type ); ?></mt:comment_type>
			<mt:comment_parent><?php echo (int) $c->comment_parent; ?></mt:comment_parent>
			<mt:comment_user_id><?php echo (int) $c->user_id; ?></mt:comment_user_id>
					<?php
					$c_meta = $mtdb->get_results( $mtdb->prepare( "SELECT * FROM $mtdb->commentmeta WHERE comment_id = %d", $c->comment_ID ) );
					foreach ( $c_meta as $meta ) :
						/**
						 * Filters whether to selectively skip comment meta used for WXR exports.
						 *
						 * Returning a truthy value from the filter will skip the current meta
						 * object from being exported.
						 *
						 * @since 4.0.0
						 *
						 * @param bool   $skip     Whether to skip the current comment meta. Default false.
						 * @param string $meta_key Current meta key.
						 * @param object $meta     Current meta object.
						 */
						if ( apply_filters( 'wxr_export_skip_commentmeta', false, $meta->meta_key, $meta ) ) {
							continue;
						}
						?>
	<mt:commentmeta>
	<mt:meta_key><?php echo wxr_cdata( $meta->meta_key ); ?></mt:meta_key>
			<mt:meta_value><?php echo wxr_cdata( $meta->meta_value ); ?></mt:meta_value>
			</mt:commentmeta>
					<?php	endforeach; ?>
		</mt:comment>
			<?php	endforeach; ?>
		</item>
				<?php
			}
		}
	}
	?>
</channel>
</rss>
	<?php
}
