<?php get_header(); ?>

<main>
	<h1>Index.php</h1>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
