<?php
/**
 * Global Timezone Helper Functions
 *
 * Provides convenient global functions for common timezone operations.
 * These functions are wrappers around the ArrayPress\Timezones\Timezones class.
 *
 * Functions included:
 * - get_timezone_label() - Get formatted timezone label
 * - get_timezone_offset() - Get UTC offset in seconds
 * - get_timezone_offset_string() - Get formatted UTC offset string
 * - get_timezone_region() - Get region for timezone
 * - get_timezone_options() - Get timezones formatted for select/dropdown
 * - get_timezone_regions() - Get all available regions
 * - is_valid_timezone() - Check if timezone identifier is valid
 * - sanitize_timezone() - Validate and sanitize timezone identifier
 *
 * @package ArrayPress\Timezones
 * @since   1.0.0
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use ArrayPress\Timezones\Timezones;

if ( ! function_exists( 'get_timezone_label' ) ) {
	/**
	 * Get formatted timezone label.
	 *
	 * @param string $timezone Timezone identifier.
	 *
	 * @return string Formatted label with underscores replaced by spaces.
	 */
	function get_timezone_label( string $timezone ): string {
		return Timezones::get_label( $timezone );
	}
}

if ( ! function_exists( 'get_timezone_offset' ) ) {
	/**
	 * Get current UTC offset for timezone.
	 *
	 * @param string $timezone Timezone identifier.
	 *
	 * @return int|null Offset in seconds or null if invalid.
	 */
	function get_timezone_offset( string $timezone ): ?int {
		return Timezones::get_offset( $timezone );
	}
}

if ( ! function_exists( 'get_timezone_offset_string' ) ) {
	/**
	 * Get formatted UTC offset string.
	 *
	 * @param string $timezone Timezone identifier.
	 *
	 * @return string Formatted offset (e.g., '+05:30', '-08:00') or empty string.
	 */
	function get_timezone_offset_string( string $timezone ): string {
		return Timezones::get_offset_string( $timezone );
	}
}

if ( ! function_exists( 'get_timezone_region' ) ) {
	/**
	 * Get region for timezone.
	 *
	 * @param string $timezone Timezone identifier.
	 *
	 * @return string Region name (e.g., 'America', 'Europe').
	 */
	function get_timezone_region( string $timezone ): string {
		return Timezones::get_region( $timezone );
	}
}

if ( ! function_exists( 'get_timezone_regions' ) ) {
	/**
	 * Get all available timezone regions.
	 *
	 * @return array Array of unique region names.
	 */
	function get_timezone_regions(): array {
		return Timezones::get_regions();
	}
}

if ( ! function_exists( 'is_valid_timezone' ) ) {
	/**
	 * Check if timezone identifier is valid.
	 *
	 * @param string $timezone Timezone identifier to check.
	 *
	 * @return bool True if valid timezone.
	 */
	function is_valid_timezone( string $timezone ): bool {
		return Timezones::exists( $timezone );
	}
}

if ( ! function_exists( 'sanitize_timezone' ) ) {
	/**
	 * Validate and sanitize timezone identifier.
	 *
	 * @param string $timezone Timezone identifier to validate.
	 *
	 * @return string|null Sanitized timezone or null if invalid.
	 */
	function sanitize_timezone( string $timezone ): ?string {
		return Timezones::sanitize( $timezone );
	}
}