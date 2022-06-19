<div class="bn_box">
    <style scoped>
        .bn_box{
            display: grid;
            grid-template-columns: max-content 1fr;
            grid-row-gap: 10px;
            grid-column-gap: 20px;
        }
        .bn_field{
            display: contents;
        }
    </style>
    <p class="meta-options bn_field">
        <label for="bn_select_breaking_news"><?php echo esc_html( 'Make this post breaking news' ); ?></label>
        <input id="bn_select_breaking_news" type="checkbox" name="bn_select_breaking_news" value='yes' class="form-control" <?php if( get_post_meta( get_the_ID(), 'bn_select_breaking_news', true ) == 'yes' ): ?> checked <?php endif?>>
    </p>
    <p class="meta-options bn_field">
        <label for="bn_custom_title"><?php echo esc_html( 'Custom Title' ); ?></label>
        <input id="bn_custom_title" type="text" name="bn_custom_title" value="<?php if( get_post_meta( get_the_ID(), 'bn_custom_title', true ) ) { echo get_post_meta( get_the_ID(), 'bn_custom_title', true ); } ?>">
    </p>
    <p class="meta-options bn_field">
        <label for="bn_select_expiry"><?php echo esc_html( 'Select Expir' ); ?>y</label>
        <input id="bn_select_expiry" type="checkbox" name="bn_select_expiry" value='yes' class="form-control" <?php if( get_post_meta( get_the_ID(), 'bn_select_expiry', true ) == 'yes' ): ?> checked <?php endif; ?>>
    </p>
    <p class="meta-options bn_field expiry-date">
        <label for="bn_select_datetime"><?php echo esc_html( 'Expiry DateTime' ); ?></label>
        <input id="bn_select_datetime" type="text" name="bn_select_datetime" value="<?php if( get_post_meta( get_the_ID(), 'bn_select_datetime', true ) ){ echo get_post_meta( get_the_ID(), 'bn_select_datetime', true ); } ?>">
    </p>
</div>

<script type="text/javascript">
	$ = jQuery;
	$(function () {
    if ($('input[name="bn_select_expiry"]').prop('checked')) {
        $('.expiry-date').fadeIn();
    } else {
        $('.expiry-date').hide();
    }

    //show it when the checkbox is clicked
    $('input[name="bn_select_expiry"]').on('click', function () {
        if ($(this).prop('checked')) {
            $('.expiry-date').fadeIn();
        } else {
            $('.expiry-date').hide();
        }
    });

    jQuery('#bn_select_datetime').datetimepicker();
});
</script>