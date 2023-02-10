/**
 * Dynamic filtering and sorting using isotope.js
 *
 * Implementation:
 * 	<input class="quicksearch" placeholder="Search...">
 * 	<div class="deck-empty">Empty message</div>
 * 	<ol class="deck">
 * 		<div class="deck-item">Content</div>
 * 		<div class="deck-item">Content</div>
 * 		...
 * 	</ol>
 */

export default class Deck {
	static init() {

		// Exit if element.deck is not found
		if ( ! document.querySelector( '.deck' ) ) {
			return;
		}

		let qsRegex;

		const badgeEl = document.querySelector( '.deck-count-badge' );

		// Initialize dynamic filtering and sorting
		// eslint-disable-next-line no-undef
		const deck = new Isotope( '.deck', { // Isotope is enqueued from cdn.
			itemSelector: '.deck-item',
			layoutMode: 'fitRows'
		});

		// Define the filter functions
		const filters = {
			search: function( itemElem ) {
				return qsRegex ? itemElem.textContent.match( qsRegex ) : true;
			}
		};

		// Filter items on quicksearch focus and keyup events
		const quicksearchEl = document.querySelector( '.quicksearch' );
		if ( ! quicksearchEl ) {
			throw new Error( '<input class="quicksearch"> required for deck.js' );
		}
		const quicksearch = debounce( () => {
			qsRegex = new RegExp( quicksearchEl.value, 'gi' );
			deck.arrange({
				filter: filters.search
			});
		});
		quicksearchEl.addEventListener( 'focus', quicksearch );
		quicksearchEl.addEventListener( 'keyup', quicksearch );

		// Handle initializing the count badge
		const elements = deck.getItemElements();
		if ( badgeEl ) {
			badgeEl.textContent = elements.length;
		}

		// Handle events after filtering complete @TODO: this is not working and we need to setup badges anyway
		deck.on( 'layoutComplete',
			function( filteredItems ) {

				// Display empty message if no matching items found
				const emptyEl = document.querySelector( '.deck-empty' );
				if ( 0 === filteredItems.length ) {
					if ( emptyEl ) {
						emptyEl.style.display = 'block';
					}
				} else {
					if ( emptyEl ) {
						emptyEl.style.display = 'none';
					}
				}

				// Update the badge count
				if ( badgeEl ) {
					badgeEl.textContent = filteredItems.length;
				}
			}
		);

		// Debounce so filtering doesn't happen every millisecond
		function debounce( fn, threshold ) {
			let timeout;
			threshold = threshold || 200;
			return function debounced() {
				var args = arguments;
				var _this = this;
				clearTimeout( timeout );
				function delayed() {
					fn.apply( _this, args );
				}
				timeout = setTimeout( delayed, threshold );
			};
		}
	}
}
