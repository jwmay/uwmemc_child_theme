/**
 * Controllers for input buttons (buttons overlaid on an input)
 *
 * Implementation:
 * 	<div class="had-input-addons">
 * 		<input />
 * 		<div class="input-clear"><i class="far fa-times-circle"></i></div>
 * 	</div>
 *
 * Currently supports:
 * 	[input-clear] Clears the input value
 */

export default class InputAddons {
	static init() {
		return new InputAddons();
	}

	constructor() {
		this.loadHandlers();
	}

	loadHandlers() {
		this.inputClearHandler();
	}

	inputClearHandler() {
		var buttonEl = document.querySelector( '.input-addon-clear' );
		var inputEl = buttonEl.parentElement.querySelector( 'input' );
		buttonEl.addEventListener( 'click', function() {
			inputEl.value = '';
			inputEl.focus();
		});
	}
}
