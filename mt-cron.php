<?php
/**
 * A pseudo-cron daemon for scheduling paCMec tasks.
 *
 * MT-Cron is triggered when the site receives a visit. In the scenario
 * where a site may not receive enough visits to execute scheduled tasks
 * in a timely manner, this file can be called directly or via a server
 * cron daemon for X number of times.
 *
 * Defining DISABLE_MT_CRON as true and calling this file directly are
 * mutually exclusive and the latter does not rely on the former to work.
 *
 * The HTTP request to this file will not slow down the visitor who happens to
 * visit when a scheduled cron event runs.
 *
 * @package paCMec
 */

ignore_user_abort( true );

/* Don't make the request block till we finish, if possible. */
if ( function_exists( 'fastcgi_finish_request' ) && version_compare( phpversion(), '7.0.16', '>=' ) ) {
	if ( ! headers_sent() ) {
		header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
		header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
	}

	fastcgi_finish_request();
}

if ( ! empty( $_POST ) || defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) ) {
	die();
}

/**
 * Tell paCMec we are doing the cron task.
 *
 * @var bool
 */
define( 'DOING_CRON', true );

if ( ! defined( 'ABSPATH' ) ) {
	/** Set up paCMec environment */
	require_once __DIR__ . '/mt-load.php';
}

/**
 * Retrieves the cron lock.
 *
 * Returns the uncached `doing_cron` transient.
 *
 * @ignore
 * @since 3.3.0
 *
 * @global mtdb $mtdb paCMec database abstraction object.
 *
 * @return string|false Value of the `doing_cron` transient, 0|false otherwise.
 */
function _get_cron_lock() {
	global $mtdb;

	$value = 0;
	if ( mt_using_ext_object_cache() ) {
		/*
		 * Skip local cache and force re-fetch of doing_cron transient
		 * in case another process updated the cache.
		 */
		$value = mt_cache_get( 'doing_cron', 'transient', true );
	} else {
		$row = $mtdb->get_row( $mtdb->prepare( "SELECT option_value FROM $mtdb->options WHERE option_name = %s LIMIT 1", '_transient_doing_cron' ) );
		if ( is_object( $row ) ) {
			$value = $row->option_value;
		}
	}

	return $value;
}

$crons = mt_get_ready_cron_jobs();
if ( empty( $crons ) ) {
	die();
}

$gmt_time = microtime( true );

// The cron lock: a unix timestamp from when the cron was spawned.
$doing_cron_transient = get_transient( 'doing_cron' );

// Use global $doing_mt_cron lock, otherwise use the GET lock. If no lock, try to grab a new lock.
if ( empty( $doing_mt_cron ) ) {
	if ( empty( $_GET['doing_mt_cron'] ) ) {
		// Called from external script/job. Try setting a lock.
		if ( $doing_cron_transient && ( $doing_cron_transient + MT_CRON_LOCK_TIMEOUT > $gmt_time ) ) {
			return;
		}
		$doing_mt_cron        = sprintf( '%.22F', microtime( true ) );
		$doing_cron_transient = $doing_mt_cron;
		set_transient( 'doing_cron', $doing_mt_cron );
	} else {
		$doing_mt_cron = $_GET['doing_mt_cron'];
	}
}

/*
 * The cron lock (a unix timestamp set when the cron was spawned),
 * must match $doing_mt_cron (the "key").
 */
if ( $doing_cron_transient !== $doing_mt_cron ) {
	return;
}

foreach ( $crons as $timestamp => $cronhooks ) {
	if ( $timestamp > $gmt_time ) {
		break;
	}

	foreach ( $cronhooks as $hook => $keys ) {

		foreach ( $keys as $k => $v ) {

			$schedule = $v['schedule'];

			if ( $schedule ) {
				mt_reschedule_event( $timestamp, $schedule, $hook, $v['args'] );
			}

			mt_unschedule_event( $timestamp, $hook, $v['args'] );

			/**
			 * Fires scheduled events.
			 *
			 * @ignore
			 * @since 2.1.0
			 *
			 * @param string $hook Name of the hook that was scheduled to be fired.
			 * @param array  $args The arguments to be passed to the hook.
			 */
			do_action_ref_array( $hook, $v['args'] );

			// If the hook ran too long and another cron process stole the lock, quit.
			if ( _get_cron_lock() !== $doing_mt_cron ) {
				return;
			}
		}
	}
}

if ( _get_cron_lock() === $doing_mt_cron ) {
	delete_transient( 'doing_cron' );
}

die();
