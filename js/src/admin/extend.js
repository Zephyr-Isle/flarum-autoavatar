import app from 'flarum/admin/app';

export default function extend(appInstance) {
  ['zephyrisle-autoavatar', 'zephyrisle-flarum-autoavatar'].forEach((extensionId) => {
    appInstance.extensionData
      .for(extensionId)
      .registerSetting({
        setting: 'zephyrisle-autoavatar.mode',
        label: app.translator.trans('zephyrisle-autoavatar.admin.settings.mode_label'),
        type: 'select',
        options: {
          letter: app.translator.trans('zephyrisle-autoavatar.admin.settings.modes.letter'),
          api: app.translator.trans('zephyrisle-autoavatar.admin.settings.modes.api'),
          multiavatar: app.translator.trans('zephyrisle-autoavatar.admin.settings.modes.multiavatar'),
        },
        default: 'letter',
      })
      .registerSetting({
        setting: 'zephyrisle-autoavatar.letter_bg_color',
        label: app.translator.trans('zephyrisle-autoavatar.admin.settings.letter_bg_color_label'),
        type: 'text',
        placeholder: '#2196f3',
      })
      .registerSetting({
        setting: 'zephyrisle-autoavatar.letter_text_color',
        label: app.translator.trans('zephyrisle-autoavatar.admin.settings.letter_text_color_label'),
        type: 'text',
        placeholder: '#ffffff',
      })
      .registerSetting({
        setting: 'zephyrisle-autoavatar.letter_shape_circle',
        label: app.translator.trans('zephyrisle-autoavatar.admin.settings.letter_shape_circle_label'),
        type: 'boolean',
      })
      .registerSetting({
        setting: 'zephyrisle-autoavatar.api_url',
        label: app.translator.trans('zephyrisle-autoavatar.admin.settings.api_url_label'),
        help: app.translator.trans('zephyrisle-autoavatar.admin.settings.api_url_help'),
        type: 'text',
        placeholder: 'https://picsum.photos/200',
      })
      .registerSetting({
        setting: 'zephyrisle-autoavatar.api_fallback_url',
        label: app.translator.trans('zephyrisle-autoavatar.admin.settings.api_fallback_url_label'),
        type: 'text',
      });
  });
}
