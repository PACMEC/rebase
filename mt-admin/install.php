<?php
/**
 * paCMec Installer
 *
 * @package paCMec
 * @subpackage Administration
 */

// Sanity check.
if ( false ) {
	?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Error: PHP no se está ejecutando</title>
</head>
<body class="mt-core-ui">
	<p id="logo"><a href="https://managertechnology.com.co/pacmec/">paCMec</a></p>
	<h1>Error: PHP no se está ejecutando</h1>
	<p>paCMec requiere que su servidor web esté ejecutando PHP. Su servidor no tiene PHP instalado o PHP está desactivado.</p>
</body>
</html>
	<?php
}

/**
 * We are installing paCMec.
 *
 * @since 1.5.1
 * @var bool
 */
define( 'MT_INSTALLING', true );

/** Load paCMec Bootstrap */
require_once dirname( __DIR__ ) . '/mt-load.php';

/** Load paCMec Administration Upgrade API */
require_once ABSPATH . 'mt-admin/includes/upgrade.php';

/** Load paCMec Translation Install API */
require_once ABSPATH . 'mt-admin/includes/translation-install.php';

/** Load mtdb */
require_once ABSPATH . MTINC . '/mt-db.php';

nocache_headers();

$step = isset( $_GET['step'] ) ? (int) $_GET['step'] : 0;

/**
 * Display installation header.
 *
 * @since 2.5.0
 *
 * @param string $body_classes
 */
function display_header( $body_classes = '' ) {
	header( 'Content-Type: text/html; charset=utf-8' );
	if ( is_rtl() ) {
		$body_classes .= 'rtl';
	}
	if ( $body_classes ) {
		$body_classes = ' ' . $body_classes;
	}
	?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta name="viemtort" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title><?php _e( 'Instalación &rsaquo; paCMec' ); ?></title>
	<?php mt_admin_css( 'install', true ); ?>
</head>
<body class="mt-core-ui<?php echo $body_classes; ?>">
<p id="logo"><?php _e( 'paCMec' ); ?></p>

	<?php
} // End display_header().

/**
 * Display installer setup form.
 *
 * @since 2.8.0
 *
 * @global mtdb $mtdb paCMec database abstraction object.
 *
 * @param string|null $error
 */
function display_setup_form( $error = null ) {
	global $mtdb;

	$user_table = ( $mtdb->get_var( $mtdb->prepare( 'SHOW TABLES LIKE %s', $mtdb->esc_like( $mtdb->users ) ) ) !== null );

	// Ensure that sites appear in search engines by default.
	$blog_public = 1;
	if ( isset( $_POST['weblog_title'] ) ) {
		$blog_public = isset( $_POST['blog_public'] );
	}

	$weblog_title = isset( $_POST['weblog_title'] ) ? trim( mt_unslash( $_POST['weblog_title'] ) ) : '';
	$user_name    = isset( $_POST['user_name'] ) ? trim( mt_unslash( $_POST['user_name'] ) ) : '';
	$admin_email  = isset( $_POST['admin_email'] ) ? trim( mt_unslash( $_POST['admin_email'] ) ) : '';

	if ( ! is_null( $error ) ) {
		?>
<h1><?php _ex( 'Bienvenid@', 'Hola' ); ?></h1>
<p class="message"><?php echo $error; ?></p>
<?php } ?>
<form id="setup" method="post" action="install.php?step=2" novalidate="novalidate">
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label for="weblog_title"><?php _e( 'Título del sitio' ); ?></label></th>
			<td><input name="weblog_title" type="text" id="weblog_title" size="25" value="<?php echo esc_attr( $weblog_title ); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="user_login"><?php _e( 'Nombre de usuario' ); ?></label></th>
			<td>
			<?php
			if ( $user_table ) {
				_e( 'El usuario ya existe.' );
				echo '<input name="user_name" type="hidden" value="admin" />';
			} else {
				?>
				<input name="user_name" type="text" id="user_login" size="25" value="<?php echo esc_attr( sanitize_user( $user_name, true ) ); ?>" />
				<p><?php _e( 'Los nombres de usuario solo pueden tener caracteres alfanuméricos, espacios, guiones bajos, guiones, puntos y el símbolo @.' ); ?></p>
				<?php
			}
			?>
			</td>
		</tr>
		<?php if ( ! $user_table ) : ?>
		<tr class="form-field form-required user-pass1-wrap">
			<th scope="row">
				<label for="pass1">
					<?php _e( 'Contraseña' ); ?>
				</label>
			</th>
			<td>
				<div class="mt-pwd">
					<?php $initial_password = isset( $_POST['admin_password'] ) ? stripslashes( $_POST['admin_password'] ) : mt_generate_password( 18 ); ?>
					<input type="password" name="admin_password" id="pass1" class="regular-text" autocomplete="off" data-reveal="1" data-pw="<?php echo esc_attr( $initial_password ); ?>" aria-describedby="pass-strength-result" />
					<button type="button" class="button mt-hide-pw hide-if-no-js" data-start-masked="<?php echo (int) isset( $_POST['admin_password'] ); ?>" data-toggle="0" aria-label="<?php esc_attr_e( 'Contraseña oculta' ); ?>">
						<span class="dashicons dashicons-hidden"></span>
						<span class="text"><?php _e( 'Esconder' ); ?></span>
					</button>
					<div id="pass-strength-result" aria-live="polite"></div>
				</div>
				<p><span class="description important hide-if-no-js">
				<strong><?php _e( 'Importante:' ); ?></strong>
				<?php /* translators: The non-breaking space prevents 1Password from thinking the text "log in" should trigger a password save prompt. */ ?>
				<?php _e( 'Necesitará esta contraseña para iniciar sesión &nbsp;. Guárdelo en un lugar seguro.' ); ?></span></p>
			</td>
		</tr>
		<tr class="form-field form-required user-pass2-wrap hide-if-js">
			<th scope="row">
				<label for="pass2"><?php _e( 'Repite la contraseña' ); ?>
					<span class="description"><?php _e( '(requerido)' ); ?></span>
				</label>
			</th>
			<td>
				<input name="admin_password2" type="password" id="pass2" autocomplete="off" />
			</td>
		</tr>
		<tr class="pw-weak">
			<th scope="row"><?php _e( 'confirmar Contraseña' ); ?></th>
			<td>
				<label>
					<input type="checkbox" name="pw_weak" class="pw-checkbox" />
					<?php _e( 'Confirmar el uso de una contraseña débil' ); ?>
				</label>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th scope="row"><label for="admin_email"><?php _e( 'Tu correo electrónico' ); ?></label></th>
			<td><input name="admin_email" type="email" id="admin_email" size="25" value="<?php echo esc_attr( $admin_email ); ?>" />
			<p><?php _e( 'Vuelva a verificar su dirección de correo electrónico antes de continuar.' ); ?></p></td>
		</tr>
		<tr>
			<th scope="row"><?php has_action( 'blog_privacy_selector' ) ? _e( 'Visibilidad del sitio' ) : _e( 'Visibilidad del motor de búsqueda' ); ?></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php has_action( 'blog_privacy_selector' ) ? _e( 'Visibilidad del sitio' ) : _e( 'Visibilidad del motor de búsqueda' ); ?> </span></legend>
					<?php
					if ( has_action( 'blog_privacy_selector' ) ) {
						?>
						<input id="blog-public" type="radio" name="blog_public" value="1" <?php checked( 1, $blog_public ); ?> />
						<label for="blog-public"><?php _e( 'Permitir al motor de busqueda indexar ese sitio' ); ?></label><br/>
						<input id="blog-norobots" type="radio" name="blog_public" value="0" <?php checked( 0, $blog_public ); ?> />
						<label for="blog-norobots"><?php _e( 'Evite que los motores de búsqueda indexen este sitio' ); ?></label>
						<p class="description"><?php _e( 'Nota: Ninguna de estas opciones bloquea el acceso a su sitio & mdash; Depende de los motores de búsqueda cumplir con su solicitud.' ); ?></p>
						<?php
						/** This action is documented in mt-admin/options-reading.php */
						do_action( 'blog_privacy_selector' );
					} else {
						?>
						<label for="blog_public"><input name="blog_public" type="checkbox" id="blog_public" value="0" <?php checked( 0, $blog_public ); ?> />
						<?php _e( 'Evite que los motores de búsqueda indexen este sitio' ); ?></label>
						<p class="description"><?php _e( 'Depende de los motores de búsqueda cumplir con esta solicitud.' ); ?></p>
					<?php } ?>
				</fieldset>
			</td>
		</tr>
	</table>
	<p class="step"><?php submit_button( __( 'Instalar paCMec' ), 'large', 'Submit', false, array( 'id' => 'submit' ) ); ?></p>
	<input type="hidden" name="language" value="<?php echo isset( $_REQUEST['language'] ) ? esc_attr( $_REQUEST['language'] ) : ''; ?>" />
</form>
	<?php
} // End display_setup_form().

// Let's check to make sure MT isn't already installed.
if ( is_blog_installed() ) {
	display_header();
	die(
		'<h1>' . __( 'Ya instalado' ) . '</h1>' .
		'<p>' . __( 'Parece que ya ha instalado paCMan. Para instalar, primero borre las tablas de su base de datos anterior.' ) . '</p>' .
		'<p class="step"><a href="' . esc_url( mt_login_url() ) . '" class="button button-large">' . __( 'Iniciar sesión' ) . '</a></p>' .
		'</body></html>'
	);
}

/**
 * @global string $mt_version             The paCMec version string.
 * @global string $required_php_version   The required PHP version string.
 * @global string $required_mysql_version The required MySQL version string.
 */
global $mt_version, $required_php_version, $required_mysql_version;

$php_version   = phpversion();
$mysql_version = $mtdb->db_version();
$php_compat    = version_compare( $php_version, $required_php_version, '>=' );
$mysql_compat  = version_compare( $mysql_version, $required_mysql_version, '>=' ) || file_exists( MT_CONTENT_DIR . '/db.php' );

$version_url = sprintf(
	/* translators: %s: paCMec version. */
	esc_url( __( 'https://managertechnology.com.co/pacmec/support/pacmec-version/version-%s/' ) ),
	sanitize_title( $mt_version )
);

/* translators: %s: URL to Update PHP page. */
$php_update_message = '</p><p>' . sprintf(
	__( '<a href="%s">Más información sobre cómo actualizar PHP</a>.' ),
	esc_url( mt_get_update_php_url() )
);

$annotation = mt_get_update_php_annotation();

if ( $annotation ) {
	$php_update_message .= '</p><p><em>' . $annotation . '</em>';
}

if ( ! $mysql_compat && ! $php_compat ) {
	$compat = sprintf(
		/* translators: 1: URL to paCMec release notes, 2: paCMec version number, 3: Minimum required PHP version number, 4: Minimum required MySQL version number, 5: Current PHP version number, 6: Current MySQL version number. */
		__( 'You cannot install because <a href="%1$s">paCMec %2$s</a> requires PHP version %3$s or higher and MySQL version %4$s or higher. You are running PHP version %5$s and MySQL version %6$s.' ),
		$version_url,
		$mt_version,
		$required_php_version,
		$required_mysql_version,
		$php_version,
		$mysql_version
	) . $php_update_message;
} elseif ( ! $php_compat ) {
	$compat = sprintf(
		/* translators: 1: URL to paCMec release notes, 2: paCMec version number, 3: Minimum required PHP version number, 4: Current PHP version number. */
		__( 'You cannot install because <a href="%1$s">paCMec %2$s</a> requires PHP version %3$s or higher. You are running version %4$s.' ),
		$version_url,
		$mt_version,
		$required_php_version,
		$php_version
	) . $php_update_message;
} elseif ( ! $mysql_compat ) {
	$compat = sprintf(
		/* translators: 1: URL to paCMec release notes, 2: paCMec version number, 3: Minimum required MySQL version number, 4: Current MySQL version number. */
		__( 'You cannot install because <a href="%1$s">paCMec %2$s</a> requires MySQL version %3$s or higher. You are running version %4$s.' ),
		$version_url,
		$mt_version,
		$required_mysql_version,
		$mysql_version
	);
}

if ( ! $mysql_compat || ! $php_compat ) {
	display_header();
	die( '<h1>' . __( 'Requisitos no cumplidos' ) . '</h1><p>' . $compat . '</p></body></html>' );
}

if ( ! is_string( $mtdb->base_prefix ) || '' === $mtdb->base_prefix ) {
	display_header();
	die(
		'<h1>' . __( 'Error de configuración' ) . '</h1>' .
		'<p>' . sprintf(
			/* translators: %s: mt-config.php */
			__( 'Su archivo %s tiene un prefijo de tabla de base de datos vacío, que no es compatible.' ),
			'<code>mt-config.php</code>'
		) . '</p></body></html>'
	);
}

// Set error message if DO_NOT_UPGRADE_GLOBAL_TABLES isn't set as it will break install.
if ( defined( 'DO_NOT_UPGRADE_GLOBAL_TABLES' ) ) {
	display_header();
	die(
		'<h1>' . __( 'Error de configuración' ) . '</h1>' .
		'<p>' . sprintf(
			/* translators: %s: DO_NOT_UPGRADE_GLOBAL_TABLES */
			__( 'La constante %s no se puede definir al instalar paCMec.' ),
			'<code>DO_NOT_UPGRADE_GLOBAL_TABLES</code>'
		) . '</p></body></html>'
	);
}

/**
 * @global string    $mt_local_package Locale code of the package.
 * @global MT_Locale $mt_locale        paCMec date and time locale object.
 */
$language = '';
if ( ! empty( $_REQUEST['language'] ) ) {
	$language = preg_replace( '/[^a-zA-Z0-9_]/', '', $_REQUEST['language'] );
} elseif ( isset( $GLOBALS['mt_local_package'] ) ) {
	$language = $GLOBALS['mt_local_package'];
}

$scripts_to_print = array( 'jquery' );

switch ( $step ) {
	case 0: // Step 0.
		if ( mt_can_install_language_pack() && empty( $language ) ) {
			$languages = mt_get_available_translations();
			if ( $languages ) {
				$scripts_to_print[] = 'language-chooser';
				display_header( 'language-chooser' );
				echo '<form id="setup" method="post" action="?step=1">';
				mt_install_language_form( $languages );
				echo '</form>';
				break;
			}
		}

		// Deliberately fall through if we can't reach the translations API.

	case 1: // Step 1, direct link or from language chooser.
		if ( ! empty( $language ) ) {
			$loaded_language = mt_download_language_pack( $language );
			if ( $loaded_language ) {
				load_default_textdomain( $loaded_language );
				$GLOBALS['mt_locale'] = new MT_Locale();
			}
		}

		$scripts_to_print[] = 'user-profile';

		display_header();
		?>
<h1><?php _ex( 'Bienvenid@', 'Hola' ); ?></h1>
<p><?php _e( '¡Bienvenido al famoso proceso de instalación de paCMec de cinco minutos! Simplemente complete la información a continuación y estará en camino de utilizar la plataforma de publicación personal más amplia y poderosa del mundo.' ); ?></p>

<h2><?php _e( 'Información necesaria' ); ?></h2>
<p><?php _e( 'Por favor provea la siguiente información. No se preocupe, siempre puede cambiar esta configuración más tarde.' ); ?></p>

		<?php
		display_setup_form();
		break;
	case 2:
		if ( ! empty( $language ) && load_default_textdomain( $language ) ) {
			$loaded_language      = $language;
			$GLOBALS['mt_locale'] = new MT_Locale();
		} else {
			$loaded_language = 'en_US';
		}

		if ( ! empty( $mtdb->error ) ) {
			mt_die( $mtdb->error->get_error_message() );
		}

		$scripts_to_print[] = 'user-profile';

		display_header();
		// Fill in the data we gathered.
		$weblog_title         = isset( $_POST['weblog_title'] ) ? trim( mt_unslash( $_POST['weblog_title'] ) ) : '';
		$user_name            = isset( $_POST['user_name'] ) ? trim( mt_unslash( $_POST['user_name'] ) ) : '';
		$admin_password       = isset( $_POST['admin_password'] ) ? mt_unslash( $_POST['admin_password'] ) : '';
		$admin_password_check = isset( $_POST['admin_password2'] ) ? mt_unslash( $_POST['admin_password2'] ) : '';
		$admin_email          = isset( $_POST['admin_email'] ) ? trim( mt_unslash( $_POST['admin_email'] ) ) : '';
		$public               = isset( $_POST['blog_public'] ) ? (int) $_POST['blog_public'] : 1;

		// Check email address.
		$error = false;
		if ( empty( $user_name ) ) {
			// TODO: Poka-yoke.
			display_setup_form( __( 'Proporcione un nombre de usuario válido.' ) );
			$error = true;
		} elseif ( sanitize_user( $user_name, true ) !== $user_name ) {
			display_setup_form( __( 'El nombre de usuario que proporcionó tiene caracteres no válidos.' ) );
			$error = true;
		} elseif ( $admin_password !== $admin_password_check ) {
			// TODO: Poka-yoke.
			display_setup_form( __( 'Tus contraseñas no coinciden. Inténtalo de nuevo.' ) );
			$error = true;
		} elseif ( empty( $admin_email ) ) {
			// TODO: Poka-yoke.
			display_setup_form( __( 'Debe proporcionar una dirección de correo electrónico.' ) );
			$error = true;
		} elseif ( ! is_email( $admin_email ) ) {
			// TODO: Poka-yoke.
			display_setup_form( __( 'Lo sentimos, esa no es una dirección de correo electrónico válida. Las direcciones de correo electrónico se parecen a <code> username@example.com </code>.' ) );
			$error = true;
		}

		if ( false === $error ) {
			$mtdb->show_errors();
			$result = mt_install( $weblog_title, $user_name, $admin_email, $public, '', mt_slash( $admin_password ), $loaded_language );
			?>

<h1><?php _e( 'Éxito!' ); ?></h1>

<p><?php _e( 'Se ha instalado paCMec. Gracias y disfruta!' ); ?></p>

<table class="form-table install-success">
	<tr>
		<th><?php _e( 'Nombre de usuario' ); ?></th>
		<td><?php echo esc_html( sanitize_user( $user_name, true ) ); ?></td>
	</tr>
	<tr>
		<th><?php _e( 'Nombre de usuario' ); ?></th>
		<td>
			<?php
			if ( ! empty( $result['password'] ) && empty( $admin_password_check ) ) :
				?>
			<code><?php echo esc_html( $result['password'] ); ?></code><br />
		<?php endif ?>
			<p><?php echo $result['password_message']; ?></p>
		</td>
	</tr>
</table>

<p class="step"><a href="<?php echo esc_url( mt_login_url() ); ?>" class="button button-large"><?php _e( 'Finalizar membresía' ); ?></a></p>

			<?php
		}
		break;
}

if ( ! mt_is_mobile() ) {
	?>
<script type="text/javascript">var t = document.getElementById('weblog_title'); if (t){ t.focus(); }</script>
	<?php
}

mt_print_scripts( $scripts_to_print );
?>
<script type="text/javascript">
jQuery( function( $ ) {
	$( '.hide-if-no-js' ).removeClass( 'hide-if-no-js' );
} );
</script>
</body>
</html>
