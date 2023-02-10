/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/custom/components/deck.js":
/*!******************************************!*\
  !*** ./src/js/custom/components/deck.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Deck)
/* harmony export */ });
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

class Deck {
  static init() {
    // Exit if element.deck is not found
    if (!document.querySelector('.deck')) {
      return;
    }
    let qsRegex;
    const badgeEl = document.querySelector('.deck-count-badge');

    // Initialize dynamic filtering and sorting
    // eslint-disable-next-line no-undef
    const deck = new Isotope('.deck', {
      // Isotope is enqueued from cdn.
      itemSelector: '.deck-item',
      layoutMode: 'fitRows'
    });

    // Define the filter functions
    const filters = {
      search: function (itemElem) {
        return qsRegex ? itemElem.textContent.match(qsRegex) : true;
      }
    };

    // Filter items on quicksearch focus and keyup events
    const quicksearchEl = document.querySelector('.quicksearch');
    if (!quicksearchEl) {
      throw new Error('<input class="quicksearch"> required for deck.js');
    }
    const quicksearch = debounce(() => {
      qsRegex = new RegExp(quicksearchEl.value, 'gi');
      deck.arrange({
        filter: filters.search
      });
    });
    quicksearchEl.addEventListener('focus', quicksearch);
    quicksearchEl.addEventListener('keyup', quicksearch);

    // Handle initializing the count badge
    const elements = deck.getItemElements();
    if (badgeEl) {
      badgeEl.textContent = elements.length;
    }

    // Handle events after filtering complete @TODO: this is not working and we need to setup badges anyway
    deck.on('layoutComplete', function (filteredItems) {
      // Display empty message if no matching items found
      const emptyEl = document.querySelector('.deck-empty');
      if (0 === filteredItems.length) {
        if (emptyEl) {
          emptyEl.style.display = 'block';
        }
      } else {
        if (emptyEl) {
          emptyEl.style.display = 'none';
        }
      }

      // Update the badge count
      if (badgeEl) {
        badgeEl.textContent = filteredItems.length;
      }
    });

    // Debounce so filtering doesn't happen every millisecond
    function debounce(fn, threshold) {
      let timeout;
      threshold = threshold || 200;
      return function debounced() {
        var args = arguments;
        var _this = this;
        clearTimeout(timeout);
        function delayed() {
          fn.apply(_this, args);
        }
        timeout = setTimeout(delayed, threshold);
      };
    }
  }
}

/***/ }),

/***/ "./src/js/custom/components/input-addons.js":
/*!**************************************************!*\
  !*** ./src/js/custom/components/input-addons.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ InputAddons)
/* harmony export */ });
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

class InputAddons {
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
    var buttonEl = document.querySelector('.input-addon-clear');
    var inputEl = buttonEl.parentElement.querySelector('input');
    buttonEl.addEventListener('click', function () {
      inputEl.value = '';
      inputEl.focus();
    });
  }
}

/***/ }),

/***/ "./src/js/custom/components/util.js":
/*!******************************************!*\
  !*** ./src/js/custom/components/util.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ready": () => (/* binding */ ready)
/* harmony export */ });
/**
 * Miscellaneous helper and utility functions.
 */

/**
 * Executes the provided callback function when the DOM content
 * has loaded similar to jQuery's $(document).ready() function.
 *
 * @param {function} callback The function to run.
 */
const ready = callback => {
  if ('loading' != document.readyState) {
    callback();
  } else {
    document.addEventListener('DOMContentLoaded', callback);
  }
};

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*********************************!*\
  !*** ./src/js/custom/custom.js ***!
  \*********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_deck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/deck */ "./src/js/custom/components/deck.js");
/* harmony import */ var _components_input_addons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/input-addons */ "./src/js/custom/components/input-addons.js");
/* harmony import */ var _components_util__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/util */ "./src/js/custom/components/util.js");
/**
 * custom.js
 */



(0,_components_util__WEBPACK_IMPORTED_MODULE_2__.ready)(() => {
  // Deck controls the filtering of publications
  _components_deck__WEBPACK_IMPORTED_MODULE_0__["default"].init();

  // InputAddons controls the clear button in the publication search input
  _components_input_addons__WEBPACK_IMPORTED_MODULE_1__["default"].init();
});
})();

/******/ })()
;
//# sourceMappingURL=custom.js.map