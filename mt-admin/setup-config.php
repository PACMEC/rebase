<?php
/**
 * Retrieves and creates the mt-config.php file.
 *
 * The permissions for the base directory must allow for writing files in order
 * for the mt-config.php to be created using this page.
 *
 * @package paCMec
 * @subpackage Administration
 */

/**
 * We are installing.
 */
define( 'MT_INSTALLING', true );

/**
 * We are blissfully unaware of anything.
 */
define( 'MT_SETUP_CONFIG', true );

/**
 * Disable error reporting
 *
 * Set this to error_reporting( -1 ) for debugging
 */
error_reporting( 0 );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__ ) . '/' );
}

require ABSPATH . 'mt-settings.php';

/** Load paCMec Administration Upgrade API */
require_once ABSPATH . 'mt-admin/includes/upgrade.php';

/** Load paCMec Translation Installation API */
require_once ABSPATH . 'mt-admin/includes/translation-install.php';

nocache_headers();

// Support mt-config-sample.php one level up, for the develop repo.
if ( file_exists( ABSPATH . 'mt-config-sample.php' ) ) {
	$config_file = file( ABSPATH . 'mt-config-sample.php' );
} elseif ( file_exists( dirname( ABSPATH ) . '/mt-config-sample.php' ) ) {
	$config_file = file( dirname( ABSPATH ) . '/mt-config-sample.php' );
} else {
	mt_die(
		sprintf(
			/* translators: %s: mt-config-sample.php */
			__( 'Lo siento, necesito el archivo %s para trabajar. Vuelva a cargar este archivo en su instalación de paCMec.' ),
			'<code>mt-config-sample.php</code>'
		)
	);
}

// Check if mt-config.php has been created.
if ( file_exists( ABSPATH . 'mt-config.php' ) ) {
	mt_die(
		'<p>' . sprintf(
			/* translators: 1: mt-config.php, 2: install.php */
			__( 'The file %1$s already exists. If you need to reset any of the configuration items in this file, please delete it first. You may try <a href="%2$s">installing now</a>.' ),
			'<code>mt-config.php</code>',
			'install.php'
		) . '</p>',
		409
	);
}

// Check if mt-config.php exists above the root directory but is not part of another installation.
if ( @file_exists( ABSPATH . '../mt-config.php' ) && ! @file_exists( ABSPATH . '../mt-settings.php' ) ) {
	mt_die(
		'<p>' . sprintf(
			/* translators: 1: mt-config.php, 2: install.php */
			__( 'The file %1$s already exists one level above your paCMec installation. If you need to reset any of the configuration items in this file, please delete it first. You may try <a href="%2$s">installing now</a>.' ),
			'<code>mt-config.php</code>',
			'install.php'
		) . '</p>',
		409
	);
}

$step = isset( $_GET['step'] ) ? (int) $_GET['step'] : -1;

/**
 * Display setup mt-config.php file header.
 *
 * @ignore
 * @since 2.3.0
 *
 * @global string    $mt_local_package Locale code of the package.
 * @global MT_Locale $mt_locale        paCMec date and time locale object.
 *
 * @param string|string[] $body_classes Class attribute values for the body tag.
 */
function setup_config_display_header( $body_classes = array() ) {
	$body_classes   = (array) $body_classes;
	$body_classes[] = 'mt-core-ui';
	$dir_attr       = '';
	if ( is_rtl() ) {
		$body_classes[] = 'rtl';
		$dir_attr       = ' dir="rtl"';
	}

	header( 'Content-Type: text/html; charset=utf-8' );
	?>
<!DOCTYPE html>
<html<?php echo $dir_attr; ?>>
<head>
	<meta name="viemtort" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title><?php _e( 'paCMec &rsaquo; Setup Configuration File' ); ?></title>
	<?php mt_admin_css( 'install', true ); ?>
</head>
<body class="<?php echo implode( ' ', $body_classes ); ?>">
<p id="logo"><?php _e( 'paCMec' ); ?></p>
	<?php
} // End function setup_config_display_header();

$language = '';
if ( ! empty( $_REQUEST['language'] ) ) {
	$language = preg_replace( '/[^a-zA-Z0-9_]/', '', $_REQUEST['language'] );
} elseif ( isset( $GLOBALS['mt_local_package'] ) ) {
	$language = $GLOBALS['mt_local_package'];
}

switch ( $step ) {
	case -1:
		if ( mt_can_install_language_pack() && empty( $language ) ) {
			$languages = mt_get_available_translations();
			if ( $languages ) {
				setup_config_display_header( 'language-chooser' );
				echo '<h1 class="screen-reader-text">Seleccione un idioma predeterminado</h1>';
				echo '<form id="setup" method="post" action="?step=0">';
				mt_install_language_form( $languages );
				echo '</form>';
				break;
			}
		}

		// Deliberately fall through if we can't reach the translations API.

	case 0:
		if ( ! empty( $language ) ) {
			$loaded_language = mt_download_language_pack( $language );
			if ( $loaded_language ) {
				load_default_textdomain( $loaded_language );
				$GLOBALS['mt_locale'] = new MT_Locale();
			}
		}

		setup_config_display_header();
		$step_1 = 'setup-config.php?step=1';
		if ( isset( $_REQUEST['noapi'] ) ) {
			$step_1 .= '&amp;noapi';
		}
		if ( ! empty( $loaded_language ) ) {
			$step_1 .= '&amp;language=' . $loaded_language;
		}
		?>
<h1 class="screen-reader-text"><?php _e( 'Antes de empezar' ); ?></h1>
<p><?php _e( 'Bienvenido a paCMec. Antes de comenzar, necesitamos información sobre la base de datos. Deberá conocer los siguientes elementos antes de continuar.' ); ?></p>
<ol>
	<li><?php _e( 'Nombre de la base de datos' ); ?></li>
	<li><?php _e( 'Nombre de usuario de la base de datos' ); ?></li>
	<li><?php _e( 'Contraseña de la base de datos' ); ?></li>
	<li><?php _e( 'Host de base de datos' ); ?></li>
	<li><?php _e( 'Prefijo de tabla (si desea ejecutar más de un paCMec en una sola base de datos)' ); ?></li>
</ol>
<p>
		<?php
		printf(
			/* translators: %s: mt-config.php */
			__( 'Usaremos esta información para crear el archivo %s.' ),
			'<code>mt-config.php</code>'
		);
		?>
	<strong>
		<?php
		printf(
			/* translators: 1: mt-config-sample.php, 2: mt-config.php */
			__( 'Si por alguna razón esta creación automática de archivos no funciona, no se preocupe. Todo esto hace es completar la información de la base de datos en un archivo de configuración. También puede simplemente abrir %1$s en un editor de texto, completar su información y guardarla como %2$s.' ),
			'<code>mt-config-sample.php</code>',
			'<code>mt-config.php</code>'
		);
		?>
	</strong>
		<?php
		printf(
			/* translators: %s: Documentation URL. */
			__( 'Necesitas más ayuda? <a href="%s"> Lo tenemos </a>.' ),
			__( 'https://managertechnology.com.co/pacmec/support/article/editing-mt-config-php/' )
		);
		?>
</p>
<p><?php _e( 'Con toda probabilidad, estos elementos fueron proporcionados por su proveedor de alojamiento web. Si no tiene esta información, deberá comunicarse con ellos antes de continuar. Si está todo listo ...' ); ?></p>

<p class="step"><a href="<?php echo $step_1; ?>" class="button button-large"><?php _e( '¡Vamos!' ); ?></a></p>
		<?php
		break;

	case 1:
		load_default_textdomain( $language );
		$GLOBALS['mt_locale'] = new MT_Locale();

		setup_config_display_header();

		$autofocus = mt_is_mobile() ? '' : ' autofocus';
		?>
<h1 class="screen-reader-text"><?php _e( '' ); ?></h1>
<form method="post" action="setup-config.php?step=2">
	<p><?php _e( 'A continuación, debe ingresar los detalles de conexión de su base de datos. Si no está seguro de estos, comuníquese con su anfitrión.' ); ?></p>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label for="dbname"><?php _e( 'Nombre de la base de datos' ); ?></label></th>
			<td><input name="dbname" id="dbname" type="text" aria-describedby="dbname-desc" size="25" value="managertechnology"<?php echo $autofocus; ?>/></td>
			<td id="dbname-desc"><?php _e( 'El nombre de la base de datos que desea usar con paCMec.' ); ?></td>
		</tr>
		<tr>
			<th scope="row"><label for="uname"><?php _e( 'Usuario' ); ?></label></th>
			<td><input name="uname" id="uname" type="text" aria-describedby="uname-desc" size="25" value="<?php echo htmlspecialchars( _x( 'username', 'example username' ), ENT_QUOTES ); ?>" /></td>
			<td id="uname-desc"><?php _e( 'Su nombre de usuario de la base de datos.' ); ?></td>
		</tr>
		<tr>
			<th scope="row"><label for="pwd"><?php _e( 'Contraseña' ); ?></label></th>
			<td><input name="pwd" id="pwd" type="text" aria-describedby="pwd-desc" size="25" value="<?php echo htmlspecialchars( _x( 'password', 'example password' ), ENT_QUOTES ); ?>" autocomplete="off" /></td>
			<td id="pwd-desc"><?php _e( 'Su contraseña de la base de datos.' ); ?></td>
		</tr>
		<tr>
			<th scope="row"><label for="dbhost"><?php _e( 'Host de base de datos' ); ?></label></th>
			<td><input name="dbhost" id="dbhost" type="text" aria-describedby="dbhost-desc" size="25" value="localhost" /></td>
			<td id="dbhost-desc">
			<?php
				/* translators: %s: localhost */
				printf( __( '	Debería poder obtener esta información de su proveedor de alojamiento web, si %s no funciona.' ), '<code>localhost</code>' );
			?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="prefix"><?php _e( 'Prefijo de la tabla' ); ?></label></th>
			<td><input name="prefix" id="prefix" type="text" aria-describedby="prefix-desc" value="mt_" size="25" /></td>
			<td id="prefix-desc"><?php _e( 'Si desea ejecutar varias instalaciones de paCMec en una sola base de datos, cámbielo.' ); ?></td>
		</tr>
	</table>
		<?php
		if ( isset( $_GET['noapi'] ) ) {
			?>
<input name="noapi" type="hidden" value="1" /><?php } ?>
	<input type="hidden" name="language" value="<?php echo esc_attr( $language ); ?>" />
	<p class="step"><input name="submit" type="submit" value="<?php echo htmlspecialchars( __( 'Enviar' ), ENT_QUOTES ); ?>" class="button button-large" /></p>
</form>
		<?php
		break;

	case 2:
		load_default_textdomain( $language );
		$GLOBALS['mt_locale'] = new MT_Locale();

		$dbname = trim( mt_unslash( $_POST['dbname'] ) );
		$uname  = trim( mt_unslash( $_POST['uname'] ) );
		$pwd    = trim( mt_unslash( $_POST['pwd'] ) );
		$dbhost = trim( mt_unslash( $_POST['dbhost'] ) );
		$prefix = trim( mt_unslash( $_POST['prefix'] ) );

		$step_1  = 'setup-config.php?step=1';
		$install = 'install.php';
		if ( isset( $_REQUEST['noapi'] ) ) {
			$step_1 .= '&amp;noapi';
		}

		if ( ! empty( $language ) ) {
			$step_1  .= '&amp;language=' . $language;
			$install .= '?language=' . $language;
		} else {
			$install .= '?language=es_CO';
		}

		$tryagain_link = '</p><p class="step"><a href="' . $step_1 . '" onclick="javascript:history.go(-1);return false;" class="button button-large">' . __( 'Try Again' ) . '</a>';

		if ( empty( $prefix ) ) {
			mt_die( __( '<strong>Error</strong>: "Prefijo de tabla" no debe estar vacío.' ) . $tryagain_link );
		}

		// Validate $prefix: it can only contain letters, numbers and underscores.
		if ( preg_match( '|[^a-z0-9_]|i', $prefix ) ) {
			mt_die( __( '<strong>Error</strong>: "Prefijo de tabla" solo puede contener números, letras y guiones bajos.' ) . $tryagain_link );
		}

		// Test the DB connection.
		/**#@+
		 *
		 * @ignore
		 */
		define( 'DB_NAME', $dbname );
		define( 'DB_USER', $uname );
		define( 'DB_PASSWORD', $pwd );
		define( 'DB_HOST', $dbhost );
		/**#@-*/

		// Re-construct $mtdb with these new values.
		unset( $mtdb );
		require_mt_db();

		/*
		* The mtdb constructor bails when MT_SETUP_CONFIG is set, so we must
		* fire this manually. We'll fail here if the values are no good.
		*/
		$mtdb->db_connect();

		if ( ! empty( $mtdb->error ) ) {
			mt_die( $mtdb->error->get_error_message() . $tryagain_link );
		}

		$errors = $mtdb->hide_errors();
		$mtdb->query( "SELECT $prefix" );
		$mtdb->show_errors( $errors );
		if ( ! $mtdb->last_error ) {
			// MySQL was able to parse the prefix as a value, which we don't want. Bail.
			mt_die( __( '<strong>Error</strong>: "Prefijo de tabla" no es válido.' ) );
		}

		// Generate keys and salts using secure CSPRNG; fallback to API if enabled; further fallback to original mt_generate_password().
		try {
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
			$max   = strlen( $chars ) - 1;
			for ( $i = 0; $i < 8; $i++ ) {
				$key = '';
				for ( $j = 0; $j < 64; $j++ ) {
					$key .= substr( $chars, random_int( 0, $max ), 1 );
				}
				$secret_keys[] = $key;
			}
		} catch ( Exception $ex ) {
			$no_api = isset( $_POST['noapi'] );

			if ( ! $no_api ) {
				$secret_keys = mt_remote_get( 'https://api.managertechnology.com.co/pacmec/secret-key/1.1/salt/' );
			}

			if ( $no_api || is_mt_error( $secret_keys ) ) {
				$secret_keys = array();
				for ( $i = 0; $i < 8; $i++ ) {
					$secret_keys[] = mt_generate_password( 64, true, true );
				}
			} else {
				$secret_keys = explode( "\n", mt_remote_retrieve_body( $secret_keys ) );
				foreach ( $secret_keys as $k => $v ) {
					$secret_keys[ $k ] = substr( $v, 28, 64 );
				}
			}
		}

		$key = 0;
		foreach ( $config_file as $line_num => $line ) {
			if ( '$table_prefix =' === substr( $line, 0, 15 ) ) {
				$config_file[ $line_num ] = '$table_prefix = \'' . addcslashes( $prefix, "\\'" ) . "';\r\n";
				continue;
			}

			if ( ! preg_match( '/^define\(\s*\'([A-Z_]+)\',([ ]+)/', $line, $match ) ) {
				continue;
			}

			$constant = $match[1];
			$padding  = $match[2];

			switch ( $constant ) {
				case 'DB_NAME':
				case 'DB_USER':
				case 'DB_PASSWORD':
				case 'DB_HOST':
					$config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . addcslashes( constant( $constant ), "\\'" ) . "' );\r\n";
					break;
				case 'DB_CHARSET':
					if ( 'utf8mb4' === $mtdb->charset || ( ! $mtdb->charset && $mtdb->has_cap( 'utf8mb4' ) ) ) {
						$config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'utf8mb4' );\r\n";
					}
					break;
				case 'AUTH_KEY':
				case 'SECURE_AUTH_KEY':
				case 'LOGGED_IN_KEY':
				case 'NONCE_KEY':
				case 'AUTH_SALT':
				case 'SECURE_AUTH_SALT':
				case 'LOGGED_IN_SALT':
				case 'NONCE_SALT':
					$config_file[ $line_num ] = "define( '" . $constant . "'," . $padding . "'" . $secret_keys[ $key++ ] . "' );\r\n";
					break;
			}
		}
		unset( $line );

		if ( ! is_writable( ABSPATH ) ) :
			setup_config_display_header();
			?>
	<p>
			<?php
			/* translators: %s: mt-config.php */
			printf( __( 'No se puede escribir en el archivo %s.' ), '<code>mt-config.php</code>' );
			?>
</p>
<p>
			<?php
			/* translators: %s: mt-config.php */
			printf( __( 'Puede crear el archivo %s manualmente y pegar el siguiente texto en él.' ), '<code>mt-config.php</code>' );

			$config_text = '';

			foreach ( $config_file as $line ) {
				$config_text .= htmlentities( $line, ENT_COMPAT, 'UTF-8' );
			}
			?>
</p>
<textarea id="mt-config" cols="98" rows="15" class="code" readonly="readonly"><?php echo $config_text; ?></textarea>
<p><?php _e( 'After you&#8217;ve done that, click &#8220;Run the installation&#8221;.' ); ?></p>
<p class="step"><a href="<?php echo $install; ?>" class="button button-large"><?php _e( 'Ejecuta la instalación' ); ?></a></p>
<script>
(function(){
if ( ! /iPad|iPod|iPhone/.test( navigator.userAgent ) ) {
	var el = document.getElementById('mt-config');
	el.focus();
	el.select();
}
})();
</script>
			<?php
	else :
		/*
		* Si este archivo no existe, entonces estamos usando mt-config-sample.php
		* Archivar un nivel arriba, que es para el repositorio de desarrollo.
		*/
		if ( file_exists( ABSPATH . 'mt-config-sample.php' ) ) {
			$path_to_mt_config = ABSPATH . 'mt-config.php';
		} else {
			$path_to_mt_config = dirname( ABSPATH ) . '/mt-config.php';
		}

		$handle = fopen( $path_to_mt_config, 'w' );
		foreach ( $config_file as $line ) {
			fwrite( $handle, $line );
		}
		fclose( $handle );
		chmod( $path_to_mt_config, 0666 );
		setup_config_display_header();
		?>
<h1 class="screen-reader-text"><?php _e( 'Conexión exitosa a la base de datos' ); ?></h1>
<p><?php _e( 'All right, sparky! You&#8217;ve made it through this part of the installation. paCMec can now communicate with your database. If you are ready, time now to&hellip;' ); ?></p>

<p class="step"><a href="<?php echo $install; ?>" class="button button-large"><?php _e( 'Run the installation' ); ?></a></p>
		<?php
	endif;
		break;
}
?>
<?php mt_print_scripts( 'language-chooser' ); ?>
</body>
</html>
