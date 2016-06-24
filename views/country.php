<?php
get_header();
global $post;
?>
<div class="container">
    <h2><?php echo $post->post_title; ?></h2>
    <p><?php echo $post->post_content; ?></p>
</div>
<?php
get_footer();
