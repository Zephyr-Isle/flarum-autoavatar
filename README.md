# Flarum Auto Avatar

Automatically generate and set avatars for new users in Flarum.

## Features

- **Letter Avatar**: Generates an image based on the user's first letter. Highly customizable CSS.
- **Random Image API**: Fetches a random image from a specified URL (e.g., Unsplash, Picsum) and saves it locally.
- **Multiavatar**: Generates unique, colorful SVG avatars (converted to PNG) using the Multiavatar library.
- **Asynchronous**: Uses Flarum's queue system to generate avatars without slowing down the registration process.
- **Local Storage**: All avatars are downloaded and saved to Flarum's local storage.

## Installation

```bash
composer require zephyrisle/flarum-autoavatar
```

## Configuration

1. Enable the extension in your Flarum admin panel.
2. Go to the **Auto Avatar** settings page.
3. Select your preferred avatar mode:
   - **Letter Avatar**: Set background color, text color, font size, and shape (circle or square).
   - **Random Image API**: Provide a primary and fallback URL.
   - **Multiavatar**: No extra configuration needed, uses user ID/email for unique generation.
4. Ensure your Flarum queue is running (`php flarum queue:work`) for asynchronous generation.

## Requirements

- Flarum v2.0 or higher.
- PHP GD extension (for Letter Avatar and Multiavatar conversion).
- PHP 8.0 or higher.

## License

MIT
