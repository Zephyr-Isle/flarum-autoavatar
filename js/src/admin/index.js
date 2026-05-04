import app from 'flarum/admin/app';
import AutoAvatarSettingsPage from './components/AutoAvatarSettingsPage';

app.initializers.add('zephyrisle-autoavatar', () => {
  app.extensionData
    .for('zephyrisle-autoavatar')
    .registerPage(AutoAvatarSettingsPage);
});
