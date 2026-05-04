import ExtensionPage from 'flarum/admin/components/ExtensionPage';

export default class AutoAvatarSettingsPage extends ExtensionPage {
  content() {
    const mode = this.setting('zephyrisle-autoavatar.mode')();

    return [
      <div className="container">
        <div className="AutoAvatarSettingsPage">
          <div className="Form">
            {this.buildSettingComponent({
              type: 'select',
              setting: 'zephyrisle-autoavatar.mode',
              label: app.translator.trans('zephyrisle-autoavatar.admin.settings.mode_label'),
              options: {
                letter: app.translator.trans('zephyrisle-autoavatar.admin.settings.modes.letter'),
                api: app.translator.trans('zephyrisle-autoavatar.admin.settings.modes.api'),
                multiavatar: app.translator.trans('zephyrisle-autoavatar.admin.settings.modes.multiavatar'),
              },
              default: 'letter',
            })}

            <hr />

            {mode === 'letter' && (
              <fieldset>
                <legend>{app.translator.trans('zephyrisle-autoavatar.admin.settings.letter_section')}</legend>
                {this.buildSettingComponent({
                  type: 'text',
                  setting: 'zephyrisle-autoavatar.letter_bg_color',
                  label: app.translator.trans('zephyrisle-autoavatar.admin.settings.letter_bg_color_label'),
                  placeholder: '#2196f3',
                })}
                {this.buildSettingComponent({
                  type: 'text',
                  setting: 'zephyrisle-autoavatar.letter_text_color',
                  label: app.translator.trans('zephyrisle-autoavatar.admin.settings.letter_text_color_label'),
                  placeholder: '#ffffff',
                })}
                {this.buildSettingComponent({
                  type: 'boolean',
                  setting: 'zephyrisle-autoavatar.letter_shape_circle',
                  label: app.translator.trans('zephyrisle-autoavatar.admin.settings.letter_shape_circle_label'),
                })}
              </fieldset>
            )}

            {mode === 'api' && (
              <fieldset>
                <legend>{app.translator.trans('zephyrisle-autoavatar.admin.settings.api_section')}</legend>
                {this.buildSettingComponent({
                  type: 'text',
                  setting: 'zephyrisle-autoavatar.api_url',
                  label: app.translator.trans('zephyrisle-autoavatar.admin.settings.api_url_label'),
                  help: app.translator.trans('zephyrisle-autoavatar.admin.settings.api_url_help'),
                  placeholder: 'https://picsum.photos/200',
                })}
                {this.buildSettingComponent({
                  type: 'text',
                  setting: 'zephyrisle-autoavatar.api_fallback_url',
                  label: app.translator.trans('zephyrisle-autoavatar.admin.settings.api_fallback_url_label'),
                })}
              </fieldset>
            )}

            {mode === 'multiavatar' && (
              <div className="helpText">
                {app.translator.trans('zephyrisle-autoavatar.admin.settings.modes.multiavatar')} 不需要额外配置。
              </div>
            )}

            {this.submitButton()}
          </div>
        </div>
      </div>
    ];
  }
}
