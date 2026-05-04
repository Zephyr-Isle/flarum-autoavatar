import app from 'flarum/admin/app';
import AutoAvatarSettingsPage from './components/AutoAvatarSettingsPage';

app.initializers.add('zephyrisle-flarum-autoavatar', () => {
  app.extensionData
    .for('zephyrisle-flarum-autoavatar')
    .registerPage(AutoAvatarSettingsPage);
});
