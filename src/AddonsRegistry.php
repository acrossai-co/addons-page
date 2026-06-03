<?php

namespace WPBoilerplate\AddonsPage;

/**
 * The hardcoded list of add-ons shown on the Add-ons page.
 * Every plugin that includes this package shows this exact same list.
 *
 * Add-on schema:
 *   slug         — WordPress plugin folder/file slug
 *   name         — display name
 *   description  — short description (2 lines max)
 *   icon         — URL to icon image
 *   more_url     — link to the add-on's marketing page
 *   type         — 'free' | 'paid'
 *   source       — 'wordpress.org' | 'github' | 'freemius'
 *
 * Source-specific keys:
 *   download_url    — ZIP URL (github source only)
 *   fs_product_id   — Freemius standalone product ID (freemius source only)
 *   fs_plan_id      — Freemius plan ID (freemius source only)
 *   fs_public_key   — Freemius public key (freemius source only)
 *   price_label     — e.g. '$49/year' (freemius source only)
 */
class AddonsRegistry {

	/** @var array[]|null */
	private static $all = null;

	/** @return array[] */
	public static function all(): array {
		if ( null !== self::$all ) {
			return self::$all;
		}

		self::$all = self::definitions();
		return self::$all;
	}

	/** @return array|null */
	public static function find( string $slug ): ?array {
		foreach ( self::all() as $addon ) {
			if ( $addon['slug'] === $slug ) {
				return $addon;
			}
		}
		return null;
	}

	/** @return array[] */
	public static function by_type( string $type ): array {
		return array_values( array_filter( self::all(), function ( $a ) use ( $type ) {
			return $a['type'] === $type;
		} ) );
	}

	/** @return array[] */
	public static function by_source( string $source ): array {
		return array_values( array_filter( self::all(), function ( $a ) use ( $source ) {
			return $a['source'] === $source;
		} ) );
	}

	// -------------------------------------------------------------------------
	// Hardcoded add-ons list
	// -------------------------------------------------------------------------

	/** @return array[] */
	private static function definitions(): array {
		return array(

			// ---- Free add-on from WordPress.org ---------------------------------
			array(
				'slug'        => 'wpb-example-addon',
				'name'        => 'WPB Example Free Add-on',
				'description' => 'An example free add-on hosted on WordPress.org. Installs with one click.',
				'icon'        => 'https://ps.w.org/wpb-example-addon/assets/icon-128x128.png',
				'more_url'    => 'https://wordpress.org/plugins/wpb-example-addon/',
				'type'        => 'free',
				'source'      => 'wordpress.org',
			),

			// ---- Free add-on from GitHub ----------------------------------------
			array(
				'slug'         => 'wpb-github-addon',
				'name'         => 'WPB GitHub Add-on',
				'description'  => 'An example free add-on hosted on GitHub. Installs directly from the release ZIP.',
				'icon'         => 'https://raw.githubusercontent.com/example/wpb-github-addon/main/assets/icon-128x128.png',
				'more_url'     => 'https://github.com/example/wpb-github-addon',
				'type'         => 'free',
				'source'       => 'github',
				'download_url' => 'https://github.com/example/wpb-github-addon/releases/latest/download/wpb-github-addon.zip',
			),

			// ---- Paid add-on from Freemius ---------------------------------------
			array(
				'slug'          => 'wpb-premium-addon',
				'name'          => 'WPB Premium Add-on',
				'description'   => 'An example premium add-on sold and delivered via Freemius. Unlock advanced features.',
				'icon'          => 'https://example.com/wpb-premium-addon/icon-128x128.png',
				'more_url'      => 'https://example.com/wpb-premium-addon/',
				'type'          => 'paid',
				'source'        => 'freemius',
				'fs_product_id' => 'REPLACE_WITH_ADDON_FS_PRODUCT_ID',
				'fs_plan_id'    => 'REPLACE_WITH_ADDON_FS_PLAN_ID',
				'fs_public_key' => 'REPLACE_WITH_ADDON_FS_PUBLIC_KEY',
				'price_label'   => '$49/year',
			),

		);
	}
}
