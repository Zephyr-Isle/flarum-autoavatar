import extend from './extend';
import app from 'flarum/admin/app';

app.initializers.add('zephyrisle-autoavatar-admin', () => {
  extend(app);
});

export { extend };
