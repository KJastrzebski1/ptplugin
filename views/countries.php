<?php
/**
 * Main screen with countries
 */
get_header();
$args = array(
    'post_type' => 'country',
    'posts_per_page' => -1,
    'orderby' => 'title',
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
                <?php
                $letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
                    'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
                for ($i = 0; $i < count($letters); $i++) {
                    echo '<div id="' . $letters[$i] . '-countries" class="search-letters">' . $letters[$i] . '</div>';
                }
                ?>
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
                    <tr>
                        <td class="country-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
                        <td><a href="<?php the_permalink(); ?>"><?php echo (get_post_meta(get_the_ID(), "rate_out", true)); ?></a></td>
                        <td><a href="<?php the_permalink(); ?>"><?php echo (get_post_meta(get_the_ID(), "rate_offline", true)); ?></a></td>
                        <td><a href="<?php the_permalink(); ?>">Details></a></td>

                    </tr> 

                <?php endwhile;
                ?>

            </tbody>
        </table>
    </div>
</div>
<?php
get_footer();
