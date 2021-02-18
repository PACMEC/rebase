<?php
/**
 * Non-latin language handling.
 *
 * Handle non-latin language styles.
 *
 * @package paCMec
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

if ( ! class_exists( 'TwentyTwenty_Non_Latin_Languages' ) ) {
	/**
	 * Language handling.
	 */
	class TwentyTwenty_Non_Latin_Languages {

		/**
		 * Get custom CSS.
		 *
		 * Return CSS for non-latin language, if available, or null
		 *
		 * @param string $type Whether to return CSS for the "front-end", "block-editor" or "classic-editor".
		 * @return void
		 */
		public static function get_non_latin_css( $type = 'front-end' ) {

			// Fetch site locale.
			$locale = get_bloginfo( 'language' );

			// Define fallback fonts for non-latin languages.
			$font_family = apply_filters(
				'twentytwenty_get_localized_font_family_types',
				array(

					// Arabic.
					'ar'    => array( 'Tahoma', 'Arial', 'sans-serif' ),
					'ary'   => array( 'Tahoma', 'Arial', 'sans-serif' ),
					'azb'   => array( 'Tahoma', 'Arial', 'sans-serif' ),
					'ckb'   => array( 'Tahoma', 'Arial', 'sans-serif' ),
					'fa-IR' => array( 'Tahoma', 'Arial', 'sans-serif' ),
					'haz'   => array( 'Tahoma', 'Arial', 'sans-serif' ),
					'ps'    => array( 'Tahoma', 'Arial', 'sans-serif' ),

					// Chinese Simplified (China) - Noto Sans SC.
					'zh-CN' => array( '\'PingFang SC\'', '\'Helvetica Neue\'', '\'Microsoft YaHei New\'', '\'STHeiti Light\'', 'sans-serif' ),

					// Chinese Traditional (Taiwan) - Noto Sans TC.
					'zh-TW' => array( '\'PingFang TC\'', '\'Helvetica Neue\'', '\'Microsoft YaHei New\'', '\'STHeiti Light\'', 'sans-serif' ),

					// Chinese (Hong Kong) - Noto Sans HK.
					'zh-HK' => array( '\'PingFang HK\'', '\'Helvetica Neue\'', '\'Microsoft YaHei New\'', '\'STHeiti Light\'', 'sans-serif' ),

					// Cyrillic.
					'bel'   => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
					'bg-BG' => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
					'kk'    => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
					'mk-MK' => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
					'mn'    => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
					'ru-RU' => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
					'sah'   => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
					'sr-RS' => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
					'tt-RU' => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),
					'uk'    => array( '\'Helvetica Neue\'', 'Helvetica', '\'Segoe UI\'', 'Arial', 'sans-serif' ),

					// Devanagari.
					'bn-BD' => array( 'Arial', 'sans-serif' ),
					'hi-IN' => array( 'Arial', 'sans-serif' ),
					'mr'    => array( 'Arial', 'sans-serif' ),
					'ne-NP' => array( 'Arial', 'sans-serif' ),

					// Greek.
					'el'    => array( '\'Helvetica Neue\', Helvetica, Arial, sans-serif' ),

					// Gujarati.
					'gu'    => array( 'Arial', 'sans-serif' ),

					// Hebrew.
					'he-IL' => array( '\'Arial Hebrew\'', 'Arial', 'sans-serif' ),

					// Japanese.
					'ja'    => array( 'sans-serif' ),

					// Korean.
					'ko-KR' => array( '\'Apple SD Gothic Neo\'', '\'Malgun Gothic\'', '\'Nanum Gothic\'', 'Dotum', 'sans-serif' ),

					// Thai.
					'th'    => array( '\'Sukhumvit Set\'', '\'Helvetica Neue\'', 'Helvetica', 'Arial', 'sans-serif' ),

					// Vietnamese.
					'vi'    => array( '\'Libre Franklin\'', 'sans-serif' ),

				)
			);

			// Return if the selected language has no fallback fonts.
			if ( empty( $font_family[ $locale ] ) ) {
				return;
			}

			// Define elements to apply fallback fonts to.
			$elements = apply_filters(
				'twentytwenty_get_localized_font_family_elements',
				array(
					'front-end'      => array( 'body', 'input', 'textarea', 'button', '.button', '.faux-button', '.mt-block-button__link', '.mt-block-file__button', '.has-drop-cap:not(:focus)::first-letter', '.has-drop-cap:not(:focus)::first-letter', '.entry-content .mt-block-archives', '.entry-content .mt-block-categories', '.entry-content .mt-block-cover-image', '.entry-content .mt-block-latest-comments', '.entry-content .mt-block-latest-posts', '.entry-content .mt-block-pullquote', '.entry-content .mt-block-quote.is-large', '.entry-content .mt-block-quote.is-style-large', '.entry-content .mt-block-archives *', '.entry-content .mt-block-categories *', '.entry-content .mt-block-latest-posts *', '.entry-content .mt-block-latest-comments *', '.entry-content p', '.entry-content ol', '.entry-content ul', '.entry-content dl', '.entry-content dt', '.entry-content cite', '.entry-content figcaption', '.entry-content .mt-caption-text', '.comment-content p', '.comment-content ol', '.comment-content ul', '.comment-content dl', '.comment-content dt', '.comment-content cite', '.comment-content figcaption', '.comment-content .mt-caption-text', '.widget_text p', '.widget_text ol', '.widget_text ul', '.widget_text dl', '.widget_text dt', '.widget-content .rssSummary', '.widget-content cite', '.widget-content figcaption', '.widget-content .mt-caption-text' ),
					'block-editor'   => array( '.editor-styles-wrapper > *', '.editor-styles-wrapper p', '.editor-styles-wrapper ol', '.editor-styles-wrapper ul', '.editor-styles-wrapper dl', '.editor-styles-wrapper dt', '.editor-post-title__block .editor-post-title__input', '.editor-styles-wrapper .mt-block h1', '.editor-styles-wrapper .mt-block h2', '.editor-styles-wrapper .mt-block h3', '.editor-styles-wrapper .mt-block h4', '.editor-styles-wrapper .mt-block h5', '.editor-styles-wrapper .mt-block h6', '.editor-styles-wrapper .has-drop-cap:not(:focus)::first-letter', '.editor-styles-wrapper cite', '.editor-styles-wrapper figcaption', '.editor-styles-wrapper .mt-caption-text' ),
					'classic-editor' => array( 'body#tinymce.mt-editor', 'body#tinymce.mt-editor p', 'body#tinymce.mt-editor ol', 'body#tinymce.mt-editor ul', 'body#tinymce.mt-editor dl', 'body#tinymce.mt-editor dt', 'body#tinymce.mt-editor figcaption', 'body#tinymce.mt-editor .mt-caption-text', 'body#tinymce.mt-editor .mt-caption-dd', 'body#tinymce.mt-editor cite', 'body#tinymce.mt-editor table' ),
				)
			);

			// Return if the specified type doesn't exist.
			if ( empty( $elements[ $type ] ) ) {
				return;
			}

			// Return the specified styles.
			return twentytwenty_generate_css( implode( ',', $elements[ $type ] ), 'font-family', implode( ',', $font_family[ $locale ] ), null, null, false );

		}
	}
}
