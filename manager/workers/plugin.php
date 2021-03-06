<div class="tab-area">
  <a class="tab active" href="#tab-content-1"><i class="fa fa-fw fa-plug"></i> <?php echo $speak->plugins; ?></a>
  <a class="tab" href="#tab-content-2"><i class="fa fa-fw fa-file-archive-o"></i> <?php echo $speak->upload; ?></a>
</div>
<div class="tab-content-area">
  <?php echo $messages; ?>
  <div class="tab-content" id="tab-content-1">
    <h3><?php echo Config::speak('manager.title_your_', array($speak->plugins)); ?></h3>
    <?php if($files): ?>
    <?php foreach($files as $plugin): $c = File::exist(PLUGIN . DS . $plugin->slug . DS . 'capture.png'); ?>
    <div class="media<?php if( ! $c): ?> no-capture<?php endif; ?>" id="plugin:<?php echo $plugin->slug; ?>">
      <?php if($c): ?>
      <div class="media-capture" style="background-image:url('<?php echo File::url($c); ?>?v=<?php echo filemtime($c); ?>');" role="image"></div>
      <?php endif; ?>
      <h4 class="media-title"><i class="fa <?php echo File::exist(PLUGIN . DS . $plugin->slug . DS . 'pending.php') ? 'fa-unlock-alt' : 'fa-lock'; ?>"></i> <?php echo $plugin->about->title; ?></h4>
      <div class="media-content">
        <p><?php echo Converter::curt($plugin->about->content); ?></p>
        <p>
          <?php if(File::exist(PLUGIN . DS . $plugin->slug . DS . 'launch.php')): ?>
          <a class="btn btn-small btn-begin" href="<?php echo $config->url . '/' . $config->manager->slug . '/plugin/' . $plugin->slug; ?>"><i class="fa fa-cog"></i> <?php echo $speak->manage; ?></a> <a class="btn btn-small btn-action" href="<?php echo $config->url . '/' . $config->manager->slug . '/plugin/freeze/id:' . $plugin->slug . '?o=' . $config->offset; ?>"><i class="fa fa-minus-circle"></i> <?php echo $speak->uninstall; ?></a>
          <?php else: ?>
          <?php if(File::exist(PLUGIN . DS . $plugin->slug . DS . 'pending.php')): ?>
          <a class="btn btn-small btn-action" href="<?php echo $config->url . '/' . $config->manager->slug . '/plugin/fire/id:' . $plugin->slug . '?o=' . $config->offset; ?>"><i class="fa fa-plus-circle"></i> <?php echo $speak->install; ?></a>
          <?php endif; ?>
          <?php endif; ?>
          <?php if( ! File::exist(PLUGIN . DS . $plugin->slug . DS . 'configurator.php') && ! File::exist(PLUGIN . DS . $plugin->slug . DS . 'launch.php') && ! File::exist(PLUGIN . DS . $plugin->slug . DS . 'pending.php')): ?>
          <span class="btn btn-small btn-destruct btn-disabled"><i class="fa fa-times-circle"></i> <?php echo $speak->remove; ?></span>
          <?php else: ?>
          <a class="btn btn-small btn-destruct" href="<?php echo $config->url . '/' . $config->manager->slug . '/plugin/kill/id:' . $plugin->slug; ?>"><i class="fa fa-times-circle"></i> <?php echo $speak->remove; ?></a>
          <?php endif; ?>
        </p>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if( ! empty($pager->step->url)): ?>
    <p class="pager cf"><?php echo $pager->step->link; ?></p>
    <?php endif; ?>
    <?php else: ?>
    <p class="empty"><?php echo Config::speak('notify_' . (Request::get('q_id') || $config->offset !== 1 ? 'error_not_found' : 'empty'), array(strtolower($speak->plugins))); ?></p>
    <?php endif; ?>
  </div>
  <div class="tab-content hidden" id="tab-content-2">
    <h3><?php echo Config::speak('manager.title__upload_package', array($speak->plugin)); ?></h3>
    <form class="form-upload" action="<?php echo $config->url . '/' . $config->manager->slug; ?>/plugin" method="post" enctype="multipart/form-data">
      <input name="token" type="hidden" value="<?php echo $token; ?>">
      <span class="input-outer btn btn-default">
        <span><i class="fa fa-folder-open"></i> <?php echo $speak->manager->placeholder_file; ?></span>
        <input type="file" name="file" title="<?php echo $speak->manager->placeholder_file; ?>" data-icon-ready="fa fa-check" data-icon-error="fa fa-times" data-accepted-extensions="zip">
      </span> <button class="btn btn-action" type="submit"><i class="fa fa-cloud-upload"></i> <?php echo $speak->upload; ?></button>
    </form>
    <hr>
    <?php echo Config::speak('file:plugin'); ?>
  </div>
</div>