<?php get_header(); ?>
<div  class="site-main container-fluid apply-bottom-pattern p-0 pb-5 position-relative apum-innerpage-section">
	<?php if (have_posts()) : while (have_posts()) : the_post(); 
	$post_id =  $post->ID;?>
	<div class="theme-top-ct apply-top-pattern apply-bottom-pattern pattern-contrast"></div>
	<main id="primary" class="site-main apum-section position-relative section-pad pt-4">
		<article id="post-<?php the_ID(); ?>" class="container bg-white pt-80 pb-4 rounded d-flex flex-column gap-24-40 " >
			<div class="maxw-720 content-area ">
				<div class="title-area add-motif mb-5 ">
					<span class="print-no"><?php custom_breadcrumb(); ?></span>
					<h1 class="entry-title mb-0"><?php the_title(); ?></h1>
					<?php
						$email = get_field('authors_bio_mail');
						if ($email) :
					?>
					<a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
					<?php endif; ?>
				</div>
				<div class="d-flex flex-column flex-md-row gap-24-40 single-author-details">
					<div class="post-thumbnail" style="width: 220px; height: 220px; flex-shrink: 0;">
						<?php if (has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('full', [
								'style' => 'width: 100%; height: 100%; object-fit: cover;',
								'alt' => get_the_title()
							]); ?>
						<?php else : ?>
							<img src="/wp-content/uploads/2025/06/author-placeholder.jpg"
								class="object-fit-cover"
								style="width: 100%; height: 100%; object-fit: cover;"
								alt="Placeholder image">
						<?php endif; ?>
					</div>
					<div class="entry-content">
						<?php the_content(); ?>
						<!-- Share and Download -->
					</div>
				</div>
				<?php
					//$author_id = get_the_ID(); // ID of current authors_bio post
                    $default_lang = apply_filters('wpml_default_language', null);
$original_author_id = apply_filters('wpml_object_id', get_the_ID(), 'authors_bio', false, $default_lang);
					$selected_categories = isset($_GET['category']) ? explode(',', sanitize_text_field($_GET['category'])) : array();
global $wp_query;
if (get_query_var('pa')) {
    $paged = get_query_var('pa');
} elseif (get_query_var('pa')) {
    $paged = get_query_var('pa');
} elseif (isset($_GET['pa'])) {
    $paged = $_GET['pa'];
} else {
    $paged = 1;
}
$paged = max(1, get_query_var('pa', get_query_var('pa')));

$paged = get_query_var('pa') ? get_query_var('pa') : 1;

$args = array(
    'post_type' => array('post', 'resource'),
    'posts_per_page' => 6,
    'paged' => $paged,
    'post_status' => 'publish',
    // 'meta_query' => array(
    //     'relation' => 'OR',
    //     array(
    //         'key' => 'article_author',
    //         'value' => '"' . $author_id . '"',
    //         'compare' => 'LIKE'
    //     ),
    //     array(
    //         'key' => 'resources_author',
    //         'value' => '"' . $author_id . '"',
    //         'compare' => 'LIKE'
    //     )
    // ),
    'meta_query' => array(
    'relation' => 'OR',
    array(
        'key' => 'article_author',
        'value' => '"' . $original_author_id . '"',
        'compare' => 'LIKE'
    ),
    array(
        'key' => 'resources_author',
        'value' => '"' . $original_author_id . '"',
        'compare' => 'LIKE'
    )
),
    'tax_query' => array(),
);

if (!empty($selected_categories)) {
    $tax_filters = array('relation' => 'OR');
    $tax_filters[] = array(
        'taxonomy' => 'category',
        'field' => 'slug',
        'terms' => $selected_categories,
        'operator' => 'IN'
    );
    $tax_filters[] = array(
        'taxonomy' => 'resource_category',
        'field' => 'slug',
        'terms' => $selected_categories,
        'operator' => 'IN'
    );
    $args['tax_query'][] = $tax_filters;
}

$articles_query = new WP_Query($args);

if ($articles_query->have_posts()) : ?>
<div class="container-fluid p-0 position-relative mt-5 ">
    <div class="theme-top-ct apply-top-pattern apply-bottom-pattern pattern-contrast"></div>
<h2><?php echo esc_html( t('aras_from_the_author') ); ?></h2>
    <!-- Unified Post + Resource Query -->
    <div class="d-grid grid-col-2">
        <?php
        while ($articles_query->have_posts()) : $articles_query->the_post();
            $author_title = get_field('author_title');
            $additional_classes = 'card-column h-100';
            $show_author = true;
            $image_size = [1280, 720];
            $external_url = get_field('external_url');
            $short_note = get_field('resource_short_note');
            $post_link = (get_post_type() === 'resource' && $external_url) ? $external_url : get_permalink();

            get_template_part('template-parts/articlecard', null, compact('author_title', 'additional_classes', 'show_author', 'image_size','external_url','short_note','post_link'));
        endwhile;
        ?>
    </div>
	
<!-- Pagination -->
<nav class="pagination-container mt-5">
    <?php
    $big = 999999999;
    $current_page = max(1, get_query_var('pa'));
    $total_pages = $articles_query->max_num_pages; // Use your custom query here
    $base = trailingslashit(get_permalink($post_id)) . 'pa/%#%/';
    $next_page_link = str_replace('%#%', $current_page + 1, $base);
    $prev_page_link = str_replace('%#%', $current_page - 1, $base);

    if ($total_pages > 1) {
        echo '<ul class="pagination">';

        // Previous button
        if ($current_page > 1) {
echo '<li class="prev"><a href="' . esc_url($prev_page_link) . '"><i class="fas fa-arrow-left"></i> <span class="btn-text">' . t('aras_previous') . '</span></a></li>';


        } else {
            echo '<li class="prev disabled"><span><i class="fas fa-arrow-left"></i><span class="btn-text">' . t('aras_previous') . '</span></span></li>';
        }

        // First page (always shown)
        if ($current_page == 1) {
            echo '<li><span class="page-numbers current">1</span></li>';
        } else {
            echo '<li><a class="page-numbers" href="' . esc_url(str_replace('%#%', 1, $base)) . '">1</a></li>';
        }

        // Ellipsis after first page (always shown)
        echo '<li><span class="page-numbers dots">...</span></li>';

        // Current page in center (only if not first or last)
        if ($current_page != 1 && $current_page != $total_pages) {
            echo '<li><span class="page-numbers current">' . $current_page . '</span></li>';
            echo '<li><span class="page-numbers dots">...</span></li>';
        }

        // Last page (always shown)
        if ($current_page == $total_pages) {
            echo '<li><span class="page-numbers current">' . $total_pages . '</span></li>';
        } else {
            echo '<li><a class="page-numbers" href="' . esc_url(str_replace('%#%', $total_pages, $base)) . '">' . $total_pages . '</a></li>';
        }

        // Next button
        if ($current_page < $total_pages) {
            echo '<li class="next"><a href="' . esc_url($next_page_link) . '"><span class="btn-text">' . t('aras_next') . '</span><i class="fas fa-arrow-right"></i></a></li>';
        } else {
            echo '<li class="next disabled"><span><span class="btn-text">' . t('aras_next') . '</span><i class="fas fa-arrow-right"></i></span></li>';
        }

        echo '</ul>';
    }
    ?>
</nav>

</div>
<?php endif;
wp_reset_postdata();
?>

				<?php
// Get all author_bio posts sorted alphabetically
$authors = get_posts(array(
    'post_type' => 'authors_bio',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'ASC',
    'post_status' => 'publish'
));

$current_id = get_the_ID();
$prev_post = null;
$next_post = null;

foreach ($authors as $index => $author) {
    if ($author->ID == $current_id) {
        // Get previous
        if ($index > 0) {
            $prev_post = $authors[$index - 1];
        }

        // Get next
        if ($index < count($authors) - 1) {
            $next_post = $authors[$index + 1];
        }

        break;
    }
}
?>

<div class="single-post-nav maxw-720 mx-auto mt-96-144">
    <?php
    // Get current language
    $current_lang = apply_filters('wpml_current_language', null);

    // Get adjacent posts
    $prev_post = get_previous_post();
    $next_post = get_next_post();

    // Translate them to current language
    if ($prev_post) {
        $translated_prev_id = apply_filters('wpml_object_id', $prev_post->ID, 'post', false, $current_lang);
        $prev_post = get_post($translated_prev_id);
    }

    if ($next_post) {
        $translated_next_id = apply_filters('wpml_object_id', $next_post->ID, 'post', false, $current_lang);
        $next_post = get_post($translated_next_id);
    }
    ?>

    <?php if ($prev_post): ?>
        <a href="<?php echo get_permalink($prev_post); ?>" class="nav-article nav-prev mb-3" aria-label="Previous Author: <?php echo esc_attr($prev_post->post_title); ?>">
            <span class="nav-arrow" aria-hidden="true"><i class="fas fa-arrow-left"></i></span>
            <div class="flex-grow-1">
                <div class="nav-label"><?php echo __( t('aras_previous') ); ?>
</div>
                <div class="nav-title fs-16-24"><?php echo wp_trim_words($prev_post->post_title, 8, '...'); ?></div>
            </div>
        </a>
    <?php endif; ?>

    <?php if ($next_post): ?>
        <a href="<?php echo get_permalink($next_post); ?>" class="nav-article nav-next">
            <div class="flex-grow-1">
                <div class="nav-label"><?php echo __( t('aras_next') ); ?>
</div>
                <div class="nav-title fs-16-24"><?php echo wp_trim_words($next_post->post_title, 8, '...'); ?></div>
            </div>
            <span class="nav-arrow"><i class="fas fa-arrow-right"></i></span>
        </a>
    <?php endif; ?>
</div>

</div>
		</article>
		<?php endwhile;
			else : ?>
		<p>No author bio found.</p>
		<?php endif; ?>
	</main>
</div>
<?php get_template_part( 'template-parts/section', 'info-cards' ); ?>
<?php get_footer(); ?>
