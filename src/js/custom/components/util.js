/**
 * Miscellaneous helper and utility functions.
 */

/**
 * Executes the provided callback function when the DOM content
 * has loaded similar to jQuery's $(document).ready() function.
 *
 * @param {function} callback The function to run.
 */
export const ready = ( callback ) => {
	if ( 'loading' != document.readyState ) {
		callback();
	} else {
		document.addEventListener( 'DOMContentLoaded', callback );
	}
};
