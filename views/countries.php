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
<?php include 'search.php'; ?>
<div class="rates">
    <img src="<?php echo plugins_url('/../img/bar.jpg', __FILE__); ?>">
    <div class="table-container container">
        <div>
            <h2 id="all-rates" style="text-align: center;">All Rates</h2>
            <div id="letters-list">

            </div>
        </div>
        <table class="table country-table">
            <thead>
                <tr>
                    <th>Country</th>
                    <th>Runo out calls</th>
                    <th>Runo offline calls</th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>

                <?php while ($query->have_posts()): ?>
                    <?php $query->the_post(); ?>
                    <?php if (strpos(get_the_title(), 'VAS') === false || get_option("show_vas"))  ?>
                    <tr>
                        <td class="country-name"><?php the_title(); ?></td>

                        <td><?php echo (get_post_meta(get_the_ID(), "rate_out", true)); ?></td>
                        <td><?php echo (get_post_meta(get_the_ID(), "rate_offline", true)); ?></td>
                        <td><a href="<?php the_permalink(); ?>">Details ></a></td>

                    </tr> 
                <?php endwhile;
                ?>

            </tbody>
        </table>
    </div>
</div>
<?php
get_footer();
