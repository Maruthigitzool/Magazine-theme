<?php get_header(); ?>

<h2>Search Results for: <?php echo get_search_query(); ?></h2>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
    <article>
      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <div><?php the_excerpt(); ?></div>
    </article>
  <?php endwhile; ?>
<?php else : ?>
  <p>No results found.</p>
<?php endif; ?>

<?php get_footer(); ?>
