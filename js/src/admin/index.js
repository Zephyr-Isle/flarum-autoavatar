import app from 'flarum/admin/app';
import AutoAvatarSettingsPage from './components/AutoAvatarSettingsPage';

app.initializers.add('zephyrisle-autoavatar-admin', () => {
  // Some setups resolve extension id as `vendor-package`,
  // while others may strip `flarum-` from package name.
  ['zephyrisle-autoavatar', 'zephyrisle-flarum-autoavatar'].forEach((extensionId) => {
    app.extensionData.for(extensionId).registerPage(AutoAvatarSettingsPage);
  });
});
