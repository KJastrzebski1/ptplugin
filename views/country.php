<?php
get_header();
$post = get_post();
$country = $post->post_name;
$args = array(
    'post_type' => 'country',
);
$query = new WP_Query($args);
?>
<div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Code</th>
                <th>Destination</th>
                <th>Price USD/min</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php while ($query->have_posts()): ?>
                    <?php
                    $query->the_post();
                    
                endwhile;
                ?>
            </tr>
        </tbody>
    </table>
</div>
<?php
get_sidebar();
get_footer();
