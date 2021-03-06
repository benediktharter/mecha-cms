<div class="tab-area">
  <a class="tab active" href="#tab-content-1"><i class="fa fa-fw fa-cog"></i> <?php echo $speak->config; ?></a>
  <a class="tab" href="#tab-content-2"><i class="fa fa-fw fa-user"></i> <?php echo $speak->about; ?></a>
</div>
<div class="tab-content-area">
  <?php echo $messages; ?>
  <div class="tab-content" id="tab-content-1">
  <?php if($file->configurator): ?>
  <?php include $file->configurator; ?>
  <?php else: ?>
  <p><?php echo Config::speak('notify_not_available', array($speak->config)); ?></p>
  <?php endif; ?>
  </div>
  <div class="tab-content hidden" id="tab-content-2">
    <p class="plugin-author"><strong><?php echo $speak->author; ?>:</strong> <?php echo Text::parse($file->author, '->encoded_html'); ?><?php if(isset($file->url) && $file->url != '#'): ?> <a class="help" href="<?php echo $file->url; ?>" title="<?php echo $speak->link; ?>" rel="nofollow" target="_blank"><i class="fa fa-external-link-square"></i></a><?php endif; ?></p>
    <h3 class="plugin-title"><?php echo $file->title; if(isset($file->version)) echo ' ' . $file->version; ?></h3>
    <div class="plugin-description"><?php echo $file->content; ?></div>
  </div>
</div>