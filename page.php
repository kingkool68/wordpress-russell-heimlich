<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<main class="post">
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
	</main>
<?php endwhile; endif; ?>

<?php get_footer(); ?>