<?php echo $messages; ?>
<form class="form-kill form-page" action="<?php echo $config->url_current; ?>" method="post">
  <input name="token" type="hidden" value="<?php echo $token; ?>">
  <h3><?php echo $page->title; ?></h3>
  <p><?php echo $page->description; ?></p>
  <?php if( ! empty($page->css)): ?>
  <pre><code><?php echo substr(Text::parse($page->css, '->encoded_html'), 0, $config->excerpt_length); ?><?php if(strlen($page->css) > $config->excerpt_length) echo ' &hellip;'; ?></code></pre>
  <?php endif; ?>
  <?php if( ! empty($page->js)): ?>
  <pre><code><?php echo substr(Text::parse($page->js, '->encoded_html'), 0, $config->excerpt_length); ?><?php if(strlen($page->js) > $config->excerpt_length) echo ' &hellip;'; ?></code></pre>
  <?php endif; ?>
  <p><button class="btn btn-action" type="submit"><i class="fa fa-check-circle"></i> <?php echo $speak->yes; ?></button> <a class="btn btn-reject" href="<?php echo $config->url . '/' . $config->manager->slug . '/page/repair/id:' . $page->id; ?>"><i class="fa fa-times-circle"></i> <?php echo $speak->no; ?></a></p>
</form>