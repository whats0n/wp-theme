<?php /* Template Name: Home Page */ ?>
<?php get_header(); ?>

<?php
  $hero = null;

  if ($posts = get_posts(array( 
    'post_name' => 'hero-post', 
    'post_type' => 'hero'
  ))) $hero = $posts[0];

  if (!is_null($hero)) :
?>
  <div class="hero">
    <div class="hero__container">
      <h2 class="hero__title"><?= get_post_field('wpcf-hero-title', $hero); ?></h2>
      <div class="hero__description">
        <?= get_post_field('wpcf-hero-description', $hero); ?>
      </div>
      <?php if (get_post_field('wpcf-hero-button', $hero)) : ?>
        <div class="hero__footer">
          <a href="#" class="btn">Start your exchange</a>
        </div>
      <?php endif; ?>
    </div>
    <div hidden>
      <?php var_dump(get_post_meta(
        get_post_field('ID', $hero)
      )); ?>
    </div>
  </div>
  <code hidden style="white-space: pre;"><?php var_dump($hero); ?></code>
<?php endif; ?>

<?php get_footer(); ?>