<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$message = '';

if(isset($_POST['settings_submit'])){
	if(!empty($_POST['settings_submit'])){
		
		$area_title = sanitize_text_field( $_POST[ 'area_title' ] );
		$bg_color 	= sanitize_text_field( $_POST[ 'bg_color' ] );
		$text_color = sanitize_text_field( $_POST[ 'text_color' ] );

		if( isset( $area_title ) ){
			$area_result = update_option( 'bn_area_title', $area_title );
		}

		if( isset( $bg_color ) ){
			$bg_color_result = update_option( 'bn_bg_color', $bg_color );
		}

		if( isset( $text_color ) ){
			$text_color_result = update_option( 'bn_text_color', $text_color );
		}
		

		if ($area_result or $bg_color_result or $text_color_result ){
			$message = "<div class='message-div success-message alert alert-success'><p>" . esc_html( 'Setting Updated Successfully' ) . "</p></div>";
		}
		else {
			$message = "<div class='message-div failed-message alert alert-failed'><p>" . esc_html( 'Something went wrong' ) . "</p></div>";
		}
		
	}
	else{
		$message = "<div class='message-div error-message alert alert-warning'><p>" . esc_html( 'There is some problem' ) . "</p></div>";
	}
}
?>
<div class="bn-settings">
<div class="container">
	<h2><?php echo esc_html( 'Breaking News Settings' ) ?></h2>
	<form action="" method="post" name="bn-settings-form">
		<?php if( isset( $_POST['settings_submit'] ) and isset( $message ) ) { echo $message; } ?>
		<div class="form-group settings-form-group">
			<label>Breaking News Area Title</label>
			<input type="text" name="area_title" value="<?php if( get_option( 'bn_area_title') ){ echo esc_html( get_option( 'bn_area_title' ) ); } else { echo 'Breaking News'; } ?>" class="form-control">
		</div>
		<div class="form-group settings-form-group">
			<label>Background Color</label>
			<input type="text" name="bg_color" class="form-control color-field bg-color" value="<?php if( get_option( 'bn_bg_color' ) ){ echo esc_html( get_option( 'bn_bg_color' ) ); } ?>">
		</div>
		<div class="form-group settings-form-group">
			<label>Text Color</label>
			<input type="text" name="text_color" class="form-control color-field text-color" value="<?php if( get_option( 'bn_text_color' ) ){ echo esc_html( get_option( 'bn_text_color' ) ); } ?>">
		</div>
		<div class="form-group settings-form-group submit-btn-group">
			<input type="submit" name="settings_submit" class="settings-field submit-field settings-field-2 btn btn-sm btn-outline-info" value="save">
		</div>
	</form>
</div>	
</div>
<div class="current-br-area">
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
	 	)
		)
	);

	$query = new WP_Query( $args );
	
  	while ( $query->have_posts() ) : $query->the_post();


?>
  	<span class='current-br'>Current Breaking News: </span>
  	<?php 
  	if( get_post_meta( get_the_ID(), 'bn_custom_title', true ) ): ?>
  		<a href='<?php echo esc_attr( get_edit_post_link() ); ?>'> <?php echo esc_html( get_post_meta( get_the_ID(), 'bn_custom_title', true ) ); ?> </a>
  	<?php else: ?>
  		<a href='<?php echo esc_attr( get_edit_post_link() ); ?>'> <?php echo esc_html( get_the_title() ); ?> </a>
  	<?php endif; ?>
  	

	<?php endwhile; ?>
</div>

<div class="notice-display">
	<p><i>Copy and paste below shortcode under your header to display Breaking news on frontend. Enclose it within php tags.</i></p>
	<code>
				 do_shortcode('[display_beaking_news]');
	</code>
</div>

<style type="text/css">
	h2 {
	    font-size: 20px;
	}
	.bn-settings {
	    padding: 0 10px;
	}
	.form-group.settings-form-group {
	    display: flex;
	    padding: 10px 0;
	    align-items: center;
	}
	.form-group label {
	    flex: 1;
	    font-weight: 600;
    	font-size: 16px;
	}
	.form-group input.form-control {
	    flex: 1;
	    margin-left: 16px;
	}
	.container {
	    width: 600px;
	    text-align: left;
	}
	.form-group .wp-picker-container {
	    flex: 1;
	}
	.current-br-area {
	    display: flex;
	    max-width: 900px;
	    align-items: center;
	    font-size: 16px;
	    padding: 30px 10px;
	}
	.current-br-area span.current-br {
	    flex: 1;
	    font-weight: 600;
	}
	.current-br-area a {
	    flex: 1;
	}
	input.settings-field {
	    font-size: 16px;
	    background: rgb(38, 45, 61);
	    color: #fff;
	    text-transform: capitalize;
	    line-height: 24px;
	    padding: 5px 20px;
	    border-radius: 5px;
	}
	input.settings-field:hover {
	    background: transparent;
	    color: rgb(38, 45, 61);
	    cursor: pointer;
	}
</style>