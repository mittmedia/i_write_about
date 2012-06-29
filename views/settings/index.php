<?php global $site; global $areas; global $content; ?>

<div class="wrap i_write_about">
  <div id="icon-options-general" class="icon32"><br></div>
  <h2><?php _e( 'I Write About Settings' ); ?></h2>
  <h3><?php _e( 'Configure Areas' ); ?></h3>
  <?php

  \WpMvc\FormHelper::render_form( $site, $content );

  ?>
</div>
