<?php
/**
 * Main screen with countries
 */
get_header();
$args = array(
    'post_type' => 'country',
    'posts_per_page' => -1,
    'order' => 'ASC'
);
$query = new WP_Query($args);
?>
<div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Code</th>
                <th>Country</th>
                <th>Min Price USD/min</th>
            </tr>
        </thead>
        <tbody>

            <?php while ($query->have_posts()): ?>
                <tr>
                    <?php
                    $query->the_post(); ?>
                    <td><?php echo (get_post_meta(get_the_ID(), "code", true)); ?></td>
                    <td><a href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></td>
                    <td><?php echo (get_post_meta(get_the_ID(), "rate", true)); ?></td>
                     
                </tr> 
            <?php endwhile;
            ?>

        </tbody>
    </table>
</div>
<?php
get_footer();
