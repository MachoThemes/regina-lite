<?php

if ( current_user_can( 'edit_theme_options' ) ) {

	$regina_lite_section_title     = get_theme_mod( 'regina_lite_news_section_title', __( 'Latest news', 'regina-lite' ) );
	$regina_lite_section_sub_title = get_theme_mod( 'regina_lite_news_section_sub_title', __( 'Straight from our blog', 'regina-lite' ) );

} else {

	$regina_lite_section_title     = get_theme_mod( 'regina_lite_news_section_title' );
	$regina_lite_section_sub_title = get_theme_mod( 'regina_lite_news_section_sub_title' );

}


$regina_lite_news_button_text                = get_theme_mod( 'regina_lite_news_section_button_text' );
$regina_lite_news_section_no_posts_per_slide = get_theme_mod( 'regina_lite_news_section_no_posts_per_slide', 2 );

// section args
$regina_lite_limit = get_option( 'posts_per_page', 4 );
// Logic used to dynamically create the layout, based on how blog posts have been set in the reading options
$regina_lite_cols      = '';
$regina_lite_news_size = '';
if ( $regina_lite_limit == 1 ) {
	$regina_lite_cols      = 'col-md-offset-4 col-sm-offset-3';
	$regina_lite_news_size = 'col-md-4 col-sm-6 col-xs-12';
} else if ( $regina_lite_limit == 2 ) {
	$regina_lite_cols      = 'col-md-offset-2 col-sm-offset-1';
	$regina_lite_news_size = 'col-md-4 col-sm-5 col-xs-12';
} else if ( $regina_lite_limit == 3 ) {
	$regina_lite_cols      = 'col-md-offset-1 col-xs-12';
	$regina_lite_news_size = 'col-md-3 col-sm-4 col-xs-12';
} else if ( $regina_lite_limit >= 4 ) {
	$regina_lite_cols      = 'col-xs-12';
	$regina_lite_news_size = 'col-md-3 col-sm-6 col-xs-12';
}
# Logic used for getting the blog template page ID
$page_which_uses_blog_template = regina_lite_get_page_id_by_template();

# Turn off default featured images
$random_featured_images = get_theme_mod( 'regina_lite_enable_default_images', 'images_disabled' );


echo '<section class="bg-block-white" id="section-news">';
	echo '<div class="container">';
		echo '<div class="row">';
				echo '<div class="section-info">';
					echo '<h2>' . esc_html( $regina_lite_section_title ) . '</h2>';
					echo '<hr>';
					echo '<p>'.esc_html( $regina_lite_section_sub_title ).'</p>';
			echo '</div>';
		echo '</div><!--/.row-->';


		echo '<div class="row">';

		// query args
		$args          = array(
			'post_type'      => 'post',
			'posts_per_page' => $regina_lite_limit,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		$regina_lite_q = new WP_Query( $args );
		if ( $regina_lite_q->have_posts() ) {
			// $i is used as counter for clearing blog posts
			$i = 1;
			echo '<div class="mt-blog-posts text-center">';
			echo '<div class="row">';
			echo '<div class="mt-blogpost-wrapper ' . $regina_lite_cols . '"  data-slider-items="' . $regina_lite_news_section_no_posts_per_slide . '">';
			while ( $regina_lite_q->have_posts() ) {
				$regina_lite_q->the_post();
				echo '<div class="' . $regina_lite_news_size . '" style="width: 100%;">';
				echo '<div class="thumbnail">';
				if ( has_post_thumbnail( $regina_lite_q->post->ID ) ) {
					echo '<a href="' . get_the_permalink() . '">';
					echo get_the_post_thumbnail( $regina_lite_q->post->ID, 'regina-lite-homepage-blog-posts' );
					echo '</a>';
				} else if ( ! has_post_thumbnail() ) {
					echo '<div class="entry-featured-image">';
					echo '<a href="' . get_the_permalink() . '">';
					echo '<img class="lazyOwl" data-src="' . esc_url( get_template_directory_uri() . '/layout/images/blog-defaults/rand-' . $i ) . '.jpg" width="250" height="250" alt="' . esc_attr( get_the_title() ) . '">';
					echo '</a>';
					echo '</div>';
				}
				echo '<div class="caption">';
				echo '<div class="mt-date">' . get_the_date( get_option( 'date-format' ), $regina_lite_q->post->ID ) . '</div>';
				echo '<h4><a class="mt-blogpost-title" href="' . esc_url( get_the_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h4>';
				echo '<p>' . the_excerpt() . '</p>';
				echo '</div><!--/.caption-->';
				echo '</div><!--/.thumbnail-->';
				echo '</div> <!--/.col-sm-6.col-md-4-->';
				$i++;
				if ( $i == 5 ) {
					echo '<div class="clearfix"></div>';
					$i = 1;
				}
			}
			echo '</div> <!--/.mt-blogpost-wrapper-->';
			echo '</div><!--/.row-->';

			echo '</div><!--/.mt-blog-posts-->';
		}
		echo '</div><!--/.row-->';
	echo '</div><!--/.container-->';

echo '</section>';