<?php $segment = 'comment'; ?>
<div class="tab-area">
  <a class="tab active" href="#tab-content-1"><i class="fa fa-fw fa-pencil"></i> <?php echo $speak->edit; ?></a>
  <a class="tab" href="#tab-content-3"><i class="fa fa-fw fa-th-list"></i> <?php echo $speak->fields; ?></a>
  <a class="tab ajax-post" href="#tab-content-2" data-action-url="<?php echo $config->url . '/' . $config->manager->slug . '/ajax/preview:comment'; ?>" data-text-progress="<?php echo $speak->previewing; ?>&hellip;" data-text-error="<?php echo $speak->error; ?>." data-scope="#form-repair" data-target="#form-repair-preview"><i class="fa fa-fw fa-eye"></i> <?php echo $speak->preview; ?></a>
</div>
<div class="tab-content-area">
  <?php echo $messages; ?>
  <form class="form-repair form-comment" id="form-repair" action="<?php echo $config->url_current; ?>" method="post">
    <input name="token" type="hidden" value="<?php echo $token; ?>">
    <div class="tab-content" id="tab-content-1">
      <?php Weapon::fire('unit_composer_1_before', array($segment)); ?>
      <?php if(isset($default->ip)): ?>
      <div class="grid-group">
        <span class="grid span-1 form-label">IP</span>
        <span class="grid span-5 form-static"><strong><?php echo $default->ip; ?></strong></span>
      </div>
      <?php endif; ?>
      <label class="grid-group">
        <span class="grid span-1 form-label"><?php echo $speak->comment_name; ?></span>
        <span class="grid span-5"><input name="name" type="text" class="input-block" value="<?php echo Text::parse(Guardian::wayback('name', $default->name), '->encoded_html'); ?>"></span>
      </label>
      <label class="grid-group">
        <span class="grid span-1 form-label"><?php echo $speak->comment_email; ?></span>
        <span class="grid span-5"><input name="email" type="text" class="input-block" value="<?php echo Guardian::wayback('email', $default->email); ?>"></span>
      </label>
      <label class="grid-group">
        <span class="grid span-1 form-label"><?php echo $speak->comment_url; ?></span>
        <span class="grid span-5"><input name="url" type="text" class="input-block" value="<?php echo Text::parse(Guardian::wayback('url', $default->url), '->encoded_html'); ?>"></span>
      </label>
      <label class="grid-group">
        <span class="grid span-1 form-label"><?php echo $speak->comment_status; ?></span>
        <span class="grid span-5">
          <select name="status">
          <?php

          foreach(array('pilot' => $speak->pilot, 'passenger' => $speak->passenger, 'intruder' => $speak->intruder) as $key => $value) {
              echo '<option value="' . $key . '"' . (Guardian::wayback('status', $default->status) == $key ? ' selected' : "") . '>' . $value . '</option>';
          }

          ?>
          </select>
        </span>
      </label>
      <label class="grid-group">
        <span class="grid span-1 form-label"><?php echo $speak->comment_message; ?></span>
        <span class="grid span-5"><textarea name="message" class="textarea-block textarea-expand code MTE" data-MTE-config='{"toolbar":true,"shortcut":true}'><?php echo Text::parse(Guardian::wayback('message', $default->message_raw), '->encoded_html'); ?></textarea></span>
      </label>
      <div class="grid-group">
        <span class="grid span-1 form-label"></span>
        <span class="grid span-5"><label><input name="content_type" type="checkbox" value="<?php echo HTML_PARSER; ?>"<?php echo Guardian::wayback('content_type', $default->content_type) == HTML_PARSER ? ' checked' : ""; ?>> <span><?php echo $speak->manager->title_html_parser; ?></span></label></span>
      </div>
      <?php Weapon::fire('unit_composer_1_after', array($segment)); ?>
    </div>
    <div class="tab-content hidden" id="tab-content-2">
      <?php Weapon::fire('unit_composer_2_before', array($segment)); ?>
      <div id="form-repair-preview"></div>
      <?php Weapon::fire('unit_composer_2_after', array($segment)); ?>
    </div>
    <div class="tab-content hidden" id="tab-content-3">
      <?php include 'unit.composer.3.php'; ?>
    </div>
    <hr>
    <p><?php if(Guardian::wayback('state', $default->state) == 'pending'): ?><button class="btn btn-accept" name="action" type="submit" value="publish"><i class="fa fa-check-circle"></i> <?php echo $speak->approve; ?></button> <button class="btn btn-action" name="action" type="submit" value="save"><i class="fa fa-clock-o"></i> <?php echo $speak->update; ?></button><?php else: ?><button class="btn btn-action" name="action" type="submit" value="publish"><i class="fa fa-check-circle"></i> <?php echo $speak->update; ?></button> <button class="btn btn-action" name="action" type="submit" value="save"><i class="fa fa-history"></i> <?php echo $speak->unpublish; ?></button><?php endif; ?> <a class="btn btn-destruct" href="<?php echo $config->url . '/' . $config->manager->slug; ?>/comment/kill/id:<?php echo Guardian::wayback('id', $default->id); ?>"><i class="fa fa-times-circle"></i> <?php echo $speak->delete; ?></a></p>
  </form>
</div>