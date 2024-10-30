jQuery( document ).ready(
	function ($) {

		'use strict';

		var ajaxurl = wmhp_vars.ajax_url;
		var nonce   = wmhp_vars.nonce;

		$( '.wmhp_select2' ).select2();

		$( '.wmhp_users' ).select2(
			{
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					type: 'POST',
					delay: 200,
					data: function (params) {
						return {
							q: params.term,
							action: 'wmhp_search_users',
							nonce: nonce
						};
					},
					processResults: function ( data ) {
						var options = [];
						if ( data ) {
							$.each(
								data,
								function ( index, text ) {
									options.push( { id: text[0], text: text[1]  } );
								}
							);
						}
						return {
							results: options
						};
					},
					cache: true
				},
				multiple: true,
				minimumInputLength: 2
			}
		);

		$( '.wmhp_products' ).select2(
			{
				ajax: {
					url: ajaxurl,
					dataType: 'json',
					type: 'POST',
					delay: 200,
					data: function (params) {
						return {
							q: params.term,
							action: 'wmhp_search_products',
							nonce: nonce
						};
					},
					processResults: function ( data ) {
						var options = [];
						if ( data ) {
							$.each(
								data,
								function ( index, text ) {
									options.push( { id: text[0], text: text[1]  } );
								}
							);
						}
						return {
							results: options
						};
					},
					cache: true
				},
				multiple: true,
				minimumInputLength: 3
			}
		);

		if ( $( '#wmhp_all_products' ).is( ':checked' ) ) {
			hide_show_all_products_option( true );
		} else {
			hide_show_all_products_option( false );
		}

		$( '#wmhp_all_products' ).click(
			function () {

				if ( $( this ).is( ':checked' ) ) {
						hide_show_all_products_option( true );
				} else {
					hide_show_all_products_option( false );
				}
			}
		);

		let atc_option = $( 'input[name="wmhp_hide_atc_options"]:checked' ).val();

		hide_show_fields_atc_options( atc_option );

		$( 'input[name="wmhp_hide_atc_options"]' ).change(
			function () {

				if ( $( this ).is( ':checked' ) ) {

					hide_show_fields_atc_options( $( this ).val() );
				}
			}
		);

		function hide_show_all_products_option( hide = false ){

			$( '.wmhp_products_tr' ).show();
			$( '.wmhp_cats_tr' ).show();
			$( '.wmhp_tags_tr' ).show();
			$( '.wmhp_brands_tr' ).show();

			switch ( hide ) {
				case true:
					$( '.wmhp_products_tr' ).hide();
					$( '.wmhp_cats_tr' ).hide();
					$( '.wmhp_tags_tr' ).hide();
					$( '.wmhp_brands_tr' ).hide();
					break;
			}
		}

		function hide_show_fields_atc_options( atc_option ){

			$( '.wmhp_atc_replace_text' ).hide();
			$( '.wmhp_atc_custom_link' ).hide();
			$( '.wmhp_atc_replace_contact_form' ).hide();
			$( '.wmhp_atc_replace_wpforms' ).hide();
			$( '.wmhp_atc_replace_gravity_form' ).hide();

			switch ( atc_option ) {
				case 'text':
					$( '.wmhp_atc_replace_text' ).show();
					break;
				case 'link':
					$( '.wmhp_atc_replace_text' ).show();
					$( '.wmhp_atc_custom_link' ).show();
					break;
				case 'contact_form':
					$( '.wmhp_atc_replace_contact_form' ).show();
					break;
				case 'wpforms':
					$( '.wmhp_atc_replace_wpforms' ).show();
					break;
				case 'gravity_form':
					$( '.wmhp_atc_replace_gravity_form' ).show();
					break;
			}
		}
	}
);
