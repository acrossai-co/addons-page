# WPBoilerplate Add-ons Page

A reusable Composer package that adds a fully working **Add-ons page** inside WP Admin for any WordPress plugin.

## Requirements

- PHP 7.4+
- WordPress 6.0+
- `automattic/jetpack-autoloader: ^5.0` in your plugin's `composer.json`

## Installation

```bash
composer require wpboilerplate/addons-page
```

Load the autoloader in your plugin (jetpack-autoloader generates `vendor/autoload_packages.php`):

```php
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload_packages.php';
```

## Integration

Call the constructor anywhere after your plugin's admin menu is registered (e.g. inside a `plugins_loaded` callback or `define_admin_hooks()`):

```php
new \WPBoilerplate\AddonsPage\AddonsPage(
    'your-plugin-menu-slug', // parent menu slug your plugin registered with add_menu_page()
    __FILE__                 // your plugin's main file — passed as-is to Freemius
);
```

That is the entire integration. The package handles:

- Registering the **Add-ons submenu** under your parent menu slug
- Rendering the add-ons grid (free + paid)
- Installing free add-ons silently (WordPress.org API or GitHub ZIP)
- Paid add-on checkout via Freemius JS popup
- Opt-in / "Login & Connect" flow
- Shared opt-in: if the user opted in via another plugin using this package, the banner is already hidden

## Upgrading jetpack-autoloader from v3

If your plugin currently has `"automattic/jetpack-autoloader": "^3.0"`, bump it to `^5.0`:

```json
"automattic/jetpack-autoloader": "^5.0"
```

Then run:

```bash
composer update automattic/jetpack-autoloader
```

Your existing `vendor/autoload_packages.php` bootstrap call continues to work unchanged.

See [docs/upgrade-notes.md](docs/upgrade-notes.md) for the full migration guide.

## readme.txt sections (for wordpress.org submissions)

Copy the blocks from [docs/readme-template.txt](docs/readme-template.txt) into your plugin's `readme.txt`.
These sections are **required by wordpress.org**:
- `== Installation ==`
- `== External Services ==`
- `== Privacy Policy ==`

## Known limitations

- **Multisite**: not tested or supported in v1. Works on per-site dashboards but network-activated behaviour is undefined.
- **Uninstall edge case**: if you have two plugins using this package and one is *uninstalled* (not just deactivated) while the other is active, Freemius may clear shared opt-in state. Recovery: the user clicks "Login / Connect" on the remaining plugin's Add-ons page.
- **Non-plugin contexts**: instantiating outside a WordPress plugin (theme, mu-plugin, CLI) throws `\RuntimeException`.

## License

GPL-2.0-or-later — see [LICENSE.txt](LICENSE.txt).
