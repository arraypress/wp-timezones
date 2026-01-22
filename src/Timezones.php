<?php
/**
 * WordPress Timezones Library
 *
 * A simple, lightweight library for timezone data and utilities in WordPress.
 *
 * @package     ArrayPress\Timezones
 * @copyright   Copyright (c) 2025, ArrayPress Limited
 * @license     GPL2+
 * @version     1.0.0
 * @author      David Sherlock
 */

namespace ArrayPress\Timezones;

// Exit if accessed directly
use DateTime;
use DateTimeZone;

defined( 'ABSPATH' ) || exit;

/**
 * Timezones class
 *
 * Provides static methods for working with timezone data.
 *
 * @since 1.0.0
 */
class Timezones {

	/**
	 * Get all timezone identifiers.
	 *
	 * @return array Array of timezone identifiers.
	 */
	public static function all(): array {
		return timezone_identifiers_list();
	}

	/**
	 * Check if timezone identifier exists.
	 *
	 * @param string $timezone Timezone identifier to check.
	 *
	 * @return bool True if valid timezone.
	 */
	public static function exists( string $timezone ): bool {
		return in_array( $timezone, timezone_identifiers_list(), true );
	}

	/**
	 * Validate and sanitize timezone identifier.
	 *
	 * @param string $timezone Timezone identifier to validate.
	 *
	 * @return string|null Sanitized timezone or null if invalid.
	 */
	public static function sanitize( string $timezone ): ?string {
		$timezone = trim( $timezone );

		return self::exists( $timezone ) ? $timezone : null;
	}

	/**
	 * Get formatted timezone label.
	 *
	 * @param string $timezone Timezone identifier.
	 *
	 * @return string Formatted label with underscores replaced by spaces.
	 */
	public static function get_label( string $timezone ): string {
		return str_replace( '_', ' ', $timezone );
	}

	/**
	 * Get timezone region.
	 *
	 * @param string $timezone Timezone identifier.
	 *
	 * @return string Region name (e.g., 'America', 'Europe').
	 */
	public static function get_region( string $timezone ): string {
		$parts = explode( '/', $timezone, 2 );

		return $parts[0];
	}

	/**
	 * Get timezone city/location.
	 *
	 * @param string $timezone Timezone identifier.
	 *
	 * @return string City/location name with underscores replaced.
	 */
	public static function get_city( string $timezone ): string {
		$parts = explode( '/', $timezone, 2 );
		$city  = $parts[1] ?? $timezone;

		return str_replace( '_', ' ', $city );
	}

	/**
	 * Get current UTC offset for timezone.
	 *
	 * @param string $timezone Timezone identifier.
	 *
	 * @return int|null Offset in seconds or null if invalid.
	 */
	public static function get_offset( string $timezone ): ?int {
		if ( ! self::exists( $timezone ) ) {
			return null;
		}

		try {
			$tz = new DateTimeZone( $timezone );
			$dt = new DateTime( 'now', $tz );

			return $tz->getOffset( $dt );
		} catch ( \Exception ) {
			return null;
		}
	}

	/**
	 * Get formatted UTC offset string.
	 *
	 * @param string $timezone Timezone identifier.
	 *
	 * @return string Formatted offset (e.g., '+05:30', '-08:00') or empty string.
	 */
	public static function get_offset_string( string $timezone ): string {
		$offset = self::get_offset( $timezone );

		if ( $offset === null ) {
			return '';
		}

		$hours   = abs( intdiv( $offset, 3600 ) );
		$minutes = abs( ( $offset % 3600 ) / 60 );
		$sign    = $offset >= 0 ? '+' : '-';

		return sprintf( '%s%02d:%02d', $sign, $hours, $minutes );
	}

	/**
	 * Get all available regions.
	 *
	 * @return array Array of unique region names.
	 */
	public static function get_regions(): array {
		$regions = [];

		foreach ( timezone_identifiers_list() as $timezone ) {
			$region = self::get_region( $timezone );
			if ( ! in_array( $region, $regions, true ) ) {
				$regions[] = $region;
			}
		}

		sort( $regions );

		return $regions;
	}

	/**
	 * Get timezones by region.
	 *
	 * @param string $region Region name (e.g., 'America', 'Europe').
	 *
	 * @return array Array of timezone identifiers in the region.
	 */
	public static function get_by_region( string $region ): array {
		$timezones = [];

		foreach ( timezone_identifiers_list() as $timezone ) {
			if ( self::get_region( $timezone ) === $region ) {
				$timezones[] = $timezone;
			}
		}

		return $timezones;
	}

	/**
	 * Search timezones by partial match.
	 *
	 * @param string $search Search term.
	 * @param int    $limit  Maximum results to return (0 = unlimited).
	 *
	 * @return array Array of matching timezone identifiers.
	 */
	public static function search( string $search, int $limit = 0 ): array {
		$search = strtolower( trim( $search ) );
		if ( empty( $search ) ) {
			return [];
		}

		$matches = [];

		foreach ( timezone_identifiers_list() as $timezone ) {
			if ( str_contains( strtolower( $timezone ), $search ) ) {
				$matches[] = $timezone;

				if ( $limit > 0 && count( $matches ) >= $limit ) {
					break;
				}
			}
		}

		return $matches;
	}

	/** Options ***********************************************************/

	/**
	 * Get timezones formatted for select/dropdown options.
	 *
	 * @param bool   $as_key_value  If true, returns ['timezone' => 'label']. If false, returns [['value' => '', 'label' => '']].
	 * @param bool   $include_empty Whether to include empty option.
	 * @param string $empty_label   Label for empty option.
	 *
	 * @return array Array of options.
	 */
	public static function get_options( bool $as_key_value = false, bool $include_empty = false, string $empty_label = '— Select —' ): array {
		$options = [];

		if ( $include_empty ) {
			if ( $as_key_value ) {
				$options[''] = $empty_label;
			} else {
				$options[] = [
					'value' => '',
					'label' => $empty_label,
				];
			}
		}

		foreach ( timezone_identifiers_list() as $timezone ) {
			$label = self::get_label( $timezone );

			if ( $as_key_value ) {
				$options[ $timezone ] = $label;
			} else {
				$options[] = [
					'value' => $timezone,
					'label' => $label,
				];
			}
		}

		return $options;
	}

	/**
	 * Get timezones with UTC offset in label.
	 *
	 * @param bool   $include_empty Whether to include empty option.
	 * @param string $empty_label   Label for empty option.
	 *
	 * @return array<array{value: string, label: string}>
	 */
	public static function get_options_with_offset( bool $include_empty = false, string $empty_label = '— Select —' ): array {
		$options = [];

		if ( $include_empty ) {
			$options[] = [
				'value' => '',
				'label' => $empty_label,
			];
		}

		foreach ( timezone_identifiers_list() as $timezone ) {
			$offset = self::get_offset_string( $timezone );
			$label  = self::get_label( $timezone );

			$options[] = [
				'value' => $timezone,
				'label' => $offset ? "(UTC{$offset}) {$label}" : $label,
			];
		}

		return $options;
	}

	/**
	 * Get grouped timezone options (by region).
	 *
	 * @return array<string, array<array{value: string, label: string}>>
	 */
	public static function get_grouped_options(): array {
		$grouped = [];

		foreach ( timezone_identifiers_list() as $timezone ) {
			$region = self::get_region( $timezone );
			$city   = self::get_city( $timezone );

			if ( ! isset( $grouped[ $region ] ) ) {
				$grouped[ $region ] = [];
			}

			$grouped[ $region ][] = [
				'value' => $timezone,
				'label' => $city,
			];
		}

		return $grouped;
	}

	/**
	 * Get grouped timezone options with UTC offset in labels.
	 *
	 * @return array<string, array<array{value: string, label: string}>>
	 */
	public static function get_grouped_options_with_offset(): array {
		$grouped = [];

		foreach ( timezone_identifiers_list() as $timezone ) {
			$region = self::get_region( $timezone );
			$city   = self::get_city( $timezone );
			$offset = self::get_offset_string( $timezone );

			if ( ! isset( $grouped[ $region ] ) ) {
				$grouped[ $region ] = [];
			}

			$grouped[ $region ][] = [
				'value' => $timezone,
				'label' => $offset ? "(UTC{$offset}) {$city}" : $city,
			];
		}

		return $grouped;
	}

	/**
	 * Get region options.
	 *
	 * @param bool   $include_empty Whether to include empty option.
	 * @param string $empty_label   Label for empty option.
	 *
	 * @return array<array{value: string, label: string}>
	 */
	public static function get_region_options( bool $include_empty = false, string $empty_label = '— Select —' ): array {
		$options = [];

		if ( $include_empty ) {
			$options[] = [
				'value' => '',
				'label' => $empty_label,
			];
		}

		foreach ( self::get_regions() as $region ) {
			$options[] = [
				'value' => $region,
				'label' => $region,
			];
		}

		return $options;
	}

}