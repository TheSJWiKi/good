<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group custom-control custom-switch">
        <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= settings()->ai_writer->is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="is_enabled"><?= l('admin_settings.ai_writer.is_enabled') ?></label>
    </div>

    <div class="form-group">
        <label for="openai_api_key"><?= l('admin_settings.ai_writer.openai_api_key') ?></label>
        <input id="openai_api_key" type="text" name="openai_api_key" class="form-control" value="<?= settings()->ai_writer->openai_api_key ?>" />
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
