module.exports = {
	env: {
		browser: true,
		commonjs: true,
		es6: true,
		node: true
	},
	extends: [ 'eslint:recommended', 'wordpress' ],
	parserOptions: {
		sourceType: 'module',
		ecmaVersion: 2020 // custom (https://stackoverflow.com/questions/36001552/eslint-parsing-error-unexpected-token)
	},
	rules: {

		// Disable weird WP spacing rules.
		// 'space-before-function-paren': 'off',
		// 'space-in-parens': 'off',
		// 'array-bracket-spacing': 'off', // Disable weird WP spacing rules.
		indent: [ 'error', 'tab' ],
		semi: [ 'error', 'always' ],
		quotes: [ 'error', 'single' ],
		'linebreak-style': [ 'error', 'unix' ]
	}
};
