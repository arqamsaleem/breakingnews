<?php 
$bg_color = '#000';
$text_color = '#000';
if(get_option( 'bn_bg_color')){
	$bg_color = get_option( 'bn_bg_color');
} 
if(get_option( 'bn_text_color')){
	$text_color = get_option( 'bn_text_color');
}
?>

<?php 
	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => 1,
		'meta_key'          => 'bn_select_breaking_news',
  		'orderby'           => 'meta_value_num',
  		'order'             => 'ASC',
		'meta_query' => array(
			'relation'		=> 'AND',
			array('key'	 	=> 'bn_select_breaking_news',
							'value'	  	=> 'yes',
	 	))
	);

	$query = new WP_Query($args);
 ?>

 <?php if ($query->have_posts()) : ?>
<div class="breaking-news">
	<?php if( get_option( 'bn_area_title' ) ){ $breaking_news_area_title = get_option( 'bn_area_title' ); } else { $breaking_news_area_title = 'Breaking News'; } ?>
	<?php 
	
  	while ( $query->have_posts() ) : $query->the_post();
?>
  	<?php
  	//echo $breaking_news_area_title;
  	if( get_post_meta( get_the_ID(), 'bn_custom_title', true ) ): ?>
  		<h2><span><?php echo esc_html( $breaking_news_area_title ); ?> : </span><a href='<?php echo esc_attr( get_post_permalink() ); ?>'> <?php echo esc_html( get_post_meta( get_the_ID(), 'bn_custom_title', true ) ); ?> </a></h2>
  	<?php else: ?>
  		<h2><span><?php echo esc_html( $breaking_news_area_title ); ?> : </span><a href='<?php echo esc_attr( get_post_permalink() ); ?>'> <?php echo esc_html( get_the_title() ); ?> </a></h2>
  	<?php endif; ?>
  	

	<?php endwhile; ?>
</div>
<style type="text/css">
	.breaking-news{
		color: <?php echo $text_color; ?>;
		background-color: <?php echo $bg_color; ?>;
		padding: 30px;
	}
	.breaking-news a{
		color: <?php echo $text_color; ?>;
	}
</style>
<?php endif; ?>