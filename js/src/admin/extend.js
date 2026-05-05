import Extend from 'flarum/common/extenders';
import app from 'flarum/admin/app';

export default [
  new Extend.Admin()
    .setting(() => ({
      setting: 'zephyrisle-autoavatar.mode',
      label: app.translator.trans('zephyrisle-autoavatar.admin.settings.mode_label', {}, true),
      type: 'select',
      options: {
        letter: app.translator.trans('zephyrisle-autoavatar.admin.settings.modes.letter', {}, true),
        api: app.translator.trans('zephyrisle-autoavatar.admin.settings.modes.api', {}, true),
        multiavatar: app.translator.trans('zephyrisle-autoavatar.admin.settings.modes.multiavatar', {}, true),
      },
      default: 'letter',
    }))
    .setting(() => ({
      setting: 'zephyrisle-autoavatar.letter_bg_color',
      label: app.translator.trans('zephyrisle-autoavatar.admin.settings.letter_bg_color_label', {}, true),
      type: 'text',
      placeholder: '#2196f3',
    }))
    .setting(() => ({
      setting: 'zephyrisle-autoavatar.letter_text_color',
      label: app.translator.trans('zephyrisle-autoavatar.admin.settings.letter_text_color_label', {}, true),
      type: 'text',
      placeholder: '#ffffff',
    }))
    .setting(() => ({
      setting: 'zephyrisle-autoavatar.letter_shape_circle',
      label: app.translator.trans('zephyrisle-autoavatar.admin.settings.letter_shape_circle_label', {}, true),
      type: 'boolean',
    }))
    .setting(() => ({
      setting: 'zephyrisle-autoavatar.api_url',
      label: app.translator.trans('zephyrisle-autoavatar.admin.settings.api_url_label', {}, true),
      help: app.translator.trans('zephyrisle-autoavatar.admin.settings.api_url_help', {}, true),
      type: 'text',
      placeholder: 'https://picsum.photos/200',
    }))
    .setting(() => ({
      setting: 'zephyrisle-autoavatar.api_fallback_url',
      label: app.translator.trans('zephyrisle-autoavatar.admin.settings.api_fallback_url_label', {}, true),
      type: 'text',
    })),
];
