/**
 * custom.js
 */
import Deck from './components/deck';
import InputAddons from './components/input-addons';
import { ready } from './components/util';

ready( () => {

	// Deck controls the filtering of publications
	Deck.init();

	// InputAddons controls the clear button in the publication search input
	InputAddons.init();
});
