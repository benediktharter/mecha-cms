<div class="main-action-group">
  <a class="btn btn-begin" href="<?php echo $config->url . '/' . $config->manager->slug; ?>/field/ignite"><i class="fa fa-plus-square"></i> <?php echo Config::speak('manager.title_new_', array($speak->field)); ?></a>
</div>
<?php echo $messages; ?>
<?php if($files): ?>
<table class="table-bordered table-full-width">
  <thead>
    <tr>
      <th><?php echo $speak->title; ?></th>
      <th><?php echo $speak->key; ?></th>
      <th><?php echo $speak->type; ?></th>
      <th class="text-center" colspan="2"><?php echo $speak->action; ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($files as $key => $value): ?>
    <tr>
      <td><?php echo $value->title; ?></td>
      <td><?php echo $key; ?></td>
      <?php

      $s = Mecha::alter($value->type[0], array(
          't' => 'Text',
          'b' => 'Boolean',
          'o' => 'Option'
      ), 'Summary');

      ?>
      <td><em class="text-info"><?php echo $s; ?></em></td>
      <td class="td-icon"><a class="text-construct" href="<?php echo $config->url . '/' . $config->manager->slug . '/field/repair/key:' . $key; ?>" title="<?php echo $speak->edit; ?>"><i class="fa fa-pencil"></i></a></td>
      <td class="td-icon"><a class="text-destruct" href="<?php echo $config->url . '/' . $config->manager->slug . '/field/kill/key:' . $key; ?>" title="<?php echo $speak->delete; ?>"><i class="fa fa-times"></i></a></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<p class="empty"><?php echo Config::speak('notify_empty', array(strtolower($speak->fields))); ?></p>
<?php endif; ?>