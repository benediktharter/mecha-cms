<div class="grid-group">
  <div class="grid span-1"></div>
  <div class="grid span-5">
    <div><label><input name="css_live_check" type="checkbox"> <span><?php echo $speak->manager->title_live_preview_css; ?></span></label></div>
    <!-- div><label><input name="js_live_check" type="checkbox"> <span><?php echo $speak->manager->title_live_preview_js; ?></span></label></div -->
  </div>
</div>
<label class="grid-group">
  <span class="grid span-1 form-label"><?php echo $speak->manager->title_custom_css; ?></span>
  <span class="grid span-5"><textarea name="css" class="textarea-block textarea-expand code MTE" placeholder="<?php echo $speak->manager->placeholder_css; ?>"><?php echo Text::parse(Guardian::wayback('css', $default->css_raw), '->encoded_html'); ?></textarea></span>
</label>
<label class="grid-group">
  <span class="grid span-1 form-label"><?php echo $speak->manager->title_custom_js; ?></span>
  <span class="grid span-5"><textarea name="js" class="textarea-block textarea-expand code MTE" placeholder="<?php echo $speak->manager->placeholder_js; ?>"><?php echo Text::parse(Guardian::wayback('js', $default->js_raw), '->encoded_html'); ?></textarea></span>
</label>