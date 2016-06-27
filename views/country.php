<?php
get_header();
global $post;
?>
<?php include 'search.php'; ?>
<div class="rates">
    <img src="<?php echo plugins_url('/../img/bar.jpg', __FILE__); ?>">
    <div class="table-container container">
        <div class="country-post">
            <a href="<?php echo get_permalink(get_option('pt_table_id')); ?>"><- all rates</a>
            <h2><?php echo $post->post_title; ?> Rates</h2>
            <p><?php echo $post->post_content; ?></p>
        </div>
    </div>
</div>
<?php
get_footer();
