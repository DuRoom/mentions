import app from 'duroom/admin/app';

app.initializers.add('duroom-mentions', function () {
  app.extensionData.for('duroom-mentions').registerSetting({
    setting: 'duroom-mentions.allow_username_format',
    type: 'boolean',
    label: app.translator.trans('duroom-mentions.admin.settings.allow_username_format_label'),
    help: app.translator.trans('duroom-mentions.admin.settings.allow_username_format_text'),
  });
});
