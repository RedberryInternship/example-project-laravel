/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(2);
module.exports = __webpack_require__(14);


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

Nova.booting(function (Vue, router, store) {
    router.addRoutes([{
        name: 'charger-terminals',
        path: '/charger-terminals',
        component: __webpack_require__(3)
    }]);
});

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(9)
/* template */
var __vue_template__ = __webpack_require__(13)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/Tool.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-68ff5483", Component.options)
  } else {
    hotAPI.reload("data-v-68ff5483", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 4 */,
/* 5 */,
/* 6 */
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function(useSourceMap) {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		return this.map(function (item) {
			var content = cssWithMappingToString(item, useSourceMap);
			if(item[2]) {
				return "@media " + item[2] + "{" + content + "}";
			} else {
				return content;
			}
		}).join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};

function cssWithMappingToString(item, useSourceMap) {
	var content = item[1] || '';
	var cssMapping = item[3];
	if (!cssMapping) {
		return content;
	}

	if (useSourceMap && typeof btoa === 'function') {
		var sourceMapping = toComment(cssMapping);
		var sourceURLs = cssMapping.sources.map(function (source) {
			return '/*# sourceURL=' + cssMapping.sourceRoot + source + ' */'
		});

		return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
	}

	return [content].join('\n');
}

// Adapted from convert-source-map (MIT)
function toComment(sourceMap) {
	// eslint-disable-next-line no-undef
	var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
	var data = 'sourceMappingURL=data:application/json;charset=utf-8;base64,' + base64;

	return '/*# ' + data + ' */';
}


/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/

var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

var listToStyles = __webpack_require__(8)

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}
var options = null
var ssrIdKey = 'data-vue-ssr-id'

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

module.exports = function (parentId, list, _isProduction, _options) {
  isProduction = _isProduction

  options = _options || {}

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[' + ssrIdKey + '~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }
  if (options.ssrId) {
    styleElement.setAttribute(ssrIdKey, obj.id)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),
/* 8 */
/***/ (function(module, exports) {

/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
module.exports = function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}


/***/ }),
/* 9 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Header__ = __webpack_require__(10);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Header___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__Header__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Search__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Search___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__Search__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__Chargers__ = __webpack_require__(25);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__Chargers___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__Chargers__);
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

//
//
//
//
//
//
//
//






/* harmony default export */ __webpack_exports__["default"] = ({
    components: {
        Header: __WEBPACK_IMPORTED_MODULE_0__Header___default.a,
        Search: __WEBPACK_IMPORTED_MODULE_1__Search___default.a,
        Chargers: __WEBPACK_IMPORTED_MODULE_2__Chargers___default.a
    },
    methods: {
        getChargers: function getChargers() {
            var _this = this;

            fetch('/nova-vendor/charger-terminals/chargers').then(function (response) {
                return response.json();
            }).then(function (data) {
                _this.chargers = data.map(function (el) {
                    var location = JSON.parse(el.location).ka;
                    return _extends({}, el, {
                        location: location && location.length > 39 ? location.substring(0, 39) + '...' : location
                    });
                });
                _this.filteredChargers = _this.chargers;
            }).catch(function (err) {
                return console.log(err);
            });
        },
        filterChargers: function filterChargers(chargerCode) {
            this.filteredChargers = this.chargers.filter(function (el) {
                return el.code.includes(chargerCode);
            });
        },
        getTerminals: function getTerminals() {
            var _this2 = this;

            fetch('/nova-vendor/charger-terminals/terminals').then(function (response) {
                return response.json();
            }).then(function (data) {
                _this2.terminals = data;
            }).catch(function (err) {
                return console.log;
            });
        }
    },
    mounted: function mounted() {
        this.getChargers();
        this.getTerminals();
    },
    data: function data() {
        return {
            chargers: [],
            filteredChargers: [],
            terminals: []
        };
    }
});

/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(11)
/* template */
var __vue_template__ = __webpack_require__(12)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/Header.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1f42fb90", Component.options)
  } else {
    hotAPI.reload("data-v-1f42fb90", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 11 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({});

/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("heading", { staticClass: "mb-6" }, [
    _vm._v("Set terminals to chargers with report")
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-1f42fb90", module.exports)
  }
}

/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c("Header"),
      _vm._v(" "),
      _c("Search", {
        attrs: { chargers: _vm.chargers },
        on: { "filter-chargers": _vm.filterChargers }
      }),
      _vm._v(" "),
      _c("Chargers", {
        attrs: { chargers: _vm.filteredChargers, terminals: _vm.terminals }
      })
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-68ff5483", module.exports)
  }
}

/***/ }),
/* 14 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 15 */,
/* 16 */,
/* 17 */,
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(21)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(23)
/* template */
var __vue_template__ = __webpack_require__(24)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-5026ffd3"
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/Search.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5026ffd3", Component.options)
  } else {
    hotAPI.reload("data-v-5026ffd3", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(30)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(27)
/* template */
var __vue_template__ = __webpack_require__(32)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-15620ea3"
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/Charger.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-15620ea3", Component.options)
  } else {
    hotAPI.reload("data-v-15620ea3", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 20 */,
/* 21 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(22);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("091fcf4d", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-5026ffd3\",\"scoped\":true,\"hasInlineConfig\":true}!../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Search.vue", function() {
     var newContent = require("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-5026ffd3\",\"scoped\":true,\"hasInlineConfig\":true}!../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Search.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 22 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(6)(false);
// imports


// module
exports.push([module.i, "\n.find-charger-input[data-v-5026ffd3]{\n  width: 30%;\n  padding: .5em;\n  border: 1px solid #7e8ea1;\n  border-radius: 3px;\n}\n.find-charger-button[data-v-5026ffd3]{\n  border: 1px solid grey;\n  padding: .5em 2em;\n  border-radius: 3px;\n  background-color: #7e8ea1;\n  color: white;\n}\n", ""]);

// exports


/***/ }),
/* 23 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__helpers_validation__ = __webpack_require__(36);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



var isBackspace = __WEBPACK_IMPORTED_MODULE_0__helpers_validation__["a" /* default */].isBackspace,
    isEnter = __WEBPACK_IMPORTED_MODULE_0__helpers_validation__["a" /* default */].isEnter,
    shouldPrevent = __WEBPACK_IMPORTED_MODULE_0__helpers_validation__["a" /* default */].shouldPrevent,
    isAlreadyFilled = __WEBPACK_IMPORTED_MODULE_0__helpers_validation__["a" /* default */].isAlreadyFilled;


/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      chargerCode: ''
    };
  },

  props: ['chargers'],
  methods: {
    filterSearchKeys: function filterSearchKeys(e) {
      if (shouldPrevent(e) || isAlreadyFilled(this.chargerCode, e)) {
        e.preventDefault();
      }
    },
    filterChargersOnClick: function filterChargersOnClick(e) {
      e.preventDefault();
      this.filterChargers();
    },
    filterChargers: function filterChargers() {
      this.$emit('filter-chargers', this.chargerCode);
    }
  }
});

/***/ }),
/* 24 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("form", [
    _c("input", {
      directives: [
        {
          name: "model",
          rawName: "v-model",
          value: _vm.chargerCode,
          expression: "chargerCode"
        }
      ],
      staticClass: "find-charger-input",
      attrs: { type: "text", placeholder: "Finde charger by code..." },
      domProps: { value: _vm.chargerCode },
      on: {
        keydown: _vm.filterSearchKeys,
        input: function($event) {
          if ($event.target.composing) {
            return
          }
          _vm.chargerCode = $event.target.value
        }
      }
    }),
    _vm._v(" "),
    _c("input", {
      staticClass: "find-charger-button",
      attrs: { type: "submit", value: "Find" },
      on: { click: _vm.filterChargersOnClick }
    })
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-5026ffd3", module.exports)
  }
}

/***/ }),
/* 25 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(33)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(26)
/* template */
var __vue_template__ = __webpack_require__(35)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-cae93240"
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/Chargers.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-cae93240", Component.options)
  } else {
    hotAPI.reload("data-v-cae93240", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 26 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Charger__ = __webpack_require__(19);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Charger___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__Charger__);
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    Charger: __WEBPACK_IMPORTED_MODULE_0__Charger___default.a
  },
  props: ["chargers", "terminals"]
});

/***/ }),
/* 27 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  props: ["charger", "terminals"],
  data: function data() {
    return {
      selected: false,
      shouldSave: false,
      updateChargerTerminal: {
        charger_id: this.charger.id,
        terminal_id: this.charger.terminal_id,
        report: this.charger.terminal_report
      }
    };
  },

  methods: {
    toggleChargerTab: function toggleChargerTab() {
      this.selected = !this.selected;
    },
    activateSaveButton: function activateSaveButton() {
      if (!this.shouldSave) {
        this.shouldSave = true;
      }
    },
    updateTerminalId: function updateTerminalId(e) {
      this.updateChargerTerminal.terminal_id = e.target.value;
      this.activateSaveButton();
    },
    updateReport: function updateReport(e) {
      this.updateChargerTerminal.report = e.target.value;
      this.activateSaveButton();
    },
    save: function save(e) {
      var _this = this;

      e.preventDefault();
      console.log(['body', this.updateChargerTerminal]);
      fetch('/nova-vendor/charger-terminals/save', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.updateChargerTerminal)
      }).then(function (response) {
        return response.json();
      }).then(function (data) {
        if (data.success) {
          _this.setTerminalName();
          _this.toggleChargerTab();
        }
      }).catch(function (err) {
        return console.log;
      });
    },
    setTerminalName: function setTerminalName() {
      var terminalId = this.updateChargerTerminal.terminal_id;
      var terminalName = this.getTerminalName(terminalId);
      this.charger.terminal_title = terminalName;
    },
    getTerminalName: function getTerminalName(id) {
      return this.terminals.find(function (el) {
        return el.id == id;
      }).title;
    }
  }
});

/***/ }),
/* 28 */,
/* 29 */,
/* 30 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(31);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("33505922", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-15620ea3\",\"scoped\":true,\"hasInlineConfig\":true}!../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Charger.vue", function() {
     var newContent = require("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-15620ea3\",\"scoped\":true,\"hasInlineConfig\":true}!../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Charger.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 31 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(6)(false);
// imports


// module
exports.push([module.i, "\n.charger-wrapper[data-v-15620ea3]{\n   background-color: #3f4857;\n   border-radius: 10px;\n   color: white;\n   margin-top: .8em;\n   height: 3em;\n   overflow: hidden;\n   -webkit-transition-duration: .4s;\n           transition-duration: .4s;\n}\n.full[data-v-15620ea3]{\n   height: 9em;\n}\n.blured[data-v-15620ea3] {\n   background-color: #607D8B;\n}\n.charger[data-v-15620ea3] {\n   display: -webkit-box;\n   display: -ms-flexbox;\n   display: flex;\n   -webkit-box-pack: justify;\n       -ms-flex-pack: justify;\n           justify-content: space-between;\n   height: 3em;\n}\n.charger-info[data-v-15620ea3] {\n   display: -webkit-box;\n   display: -ms-flexbox;\n   display: flex;\n   -webkit-box-align: center;\n       -ms-flex-align: center;\n           align-items: center;\n}\n.edit-btn-wrapper[data-v-15620ea3]{\n display: -webkit-box;\n display: -ms-flexbox;\n display: flex;\n -webkit-box-align: center;\n     -ms-flex-align: center;\n         align-items: center;\n}\n.charger-edit-btn[data-v-15620ea3] {\n   margin-right: 2em;\n   padding: .5em 1em;\n   background-color: white;\n   border-radius: 3px;\n}\n.charger-info p[data-v-15620ea3] {\n margin-left: 2em;\n}\n.charger-info p.id[data-v-15620ea3] {\n width: 3em;\n}\n.charger-info p.code[data-v-15620ea3] {\n width: 6em;\n}\n.charger-info p.location[data-v-15620ea3] {\n  width: 30em;\n}\n.charger-info p span[data-v-15620ea3] {\n font-weight: bold;\n}\n.edit form[data-v-15620ea3] {\n margin-left: 2em;\n margin-top: 2em;\n display: -webkit-box;\n display: -ms-flexbox;\n display: flex;\n -webkit-box-pack: justify;\n     -ms-flex-pack: justify;\n         justify-content: space-between;\n}\n.edit form select[data-v-15620ea3] {\n width: 10em;\n border-radius: 5px;\n background-color: #7e8ea1;\n color: white;\n padding: .15em .4em;\n margin-right: 1em;\n}\n.edit form input.report[data-v-15620ea3] {\n width: 33em;\n border-radius: 5px;\n padding: .3em .5em;\n outline: none;\n}\n.edit form input.save[data-v-15620ea3] {\n margin-right: 2em;\n padding: .5em 1em;\n color: white;\n border: 1px solid transparent;\n -webkit-transition-duration: .3s;\n         transition-duration: .3s;\n border-radius: 3px;\n background-color: darkgray;\n}\n.edit form input.active[data-v-15620ea3] {\n background-color: #7e8ea1;\n}\n.edit form input.active[data-v-15620ea3]:hover {\n border: 1px solid white;\n cursor: pointer;\n}\n", ""]);

// exports


/***/ }),
/* 32 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c(
      "div",
      {
        staticClass: "charger-wrapper",
        class: { full: _vm.selected, blured: !_vm.charger.terminal_title }
      },
      [
        _c("div", { staticClass: "charger" }, [
          _c("div", { staticClass: "charger-info" }, [
            _c("p", { staticClass: "id" }, [
              _c("span", [_vm._v("ID:")]),
              _vm._v(" " + _vm._s(_vm.charger.id) + " ")
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "code" }, [
              _c("span", [_vm._v("Code:")]),
              _vm._v(" " + _vm._s(_vm.charger.code) + " ")
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "location" }, [
              _c("span", [_vm._v("Location:")]),
              _vm._v(" " + _vm._s(_vm.charger.location) + " ")
            ]),
            _vm._v(" "),
            _c("p", [
              _c("span", [_vm._v("Terminal:")]),
              _vm._v(
                " " +
                  _vm._s(
                    !!_vm.charger.terminal_title
                      ? _vm.charger.terminal_title
                      : "X"
                  ) +
                  " "
              )
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "edit-btn-wrapper" }, [
            _c(
              "button",
              {
                staticClass: "charger-edit-btn",
                on: { click: _vm.toggleChargerTab }
              },
              [_vm._v(_vm._s(_vm.selected ? "Close" : "Edit"))]
            )
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "edit" }, [
          _c("form", [
            _c("div", { staticClass: "left" }, [
              _c(
                "select",
                {
                  attrs: { name: "terminals" },
                  on: { change: _vm.updateTerminalId }
                },
                [
                  _c("option", { attrs: { disabled: "", selected: "" } }, [
                    _vm._v("Choose Terminal")
                  ]),
                  _vm._v(" "),
                  _vm._l(_vm.terminals, function(terminal) {
                    return _c(
                      "option",
                      { key: terminal.id, domProps: { value: terminal.id } },
                      [_vm._v(_vm._s(terminal.title))]
                    )
                  })
                ],
                2
              ),
              _vm._v(" "),
              _c("input", {
                staticClass: "report",
                attrs: { type: "text", placeholder: "Report info..." },
                domProps: { value: _vm.updateChargerTerminal.report },
                on: { change: _vm.updateReport }
              })
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "right" }, [
              _c("input", {
                staticClass: "save",
                class: { active: _vm.shouldSave },
                attrs: { type: "submit", value: "Save" },
                on: { click: _vm.save }
              })
            ])
          ])
        ])
      ]
    )
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-15620ea3", module.exports)
  }
}

/***/ }),
/* 33 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(34);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(7)("1dec6164", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-cae93240\",\"scoped\":true,\"hasInlineConfig\":true}!../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Chargers.vue", function() {
     var newContent = require("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-cae93240\",\"scoped\":true,\"hasInlineConfig\":true}!../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Chargers.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 34 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(6)(false);
// imports


// module
exports.push([module.i, "\n.chargers[data-v-cae93240]{\n  margin-top: 1em;\n}\n", ""]);

// exports


/***/ }),
/* 35 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "chargers" },
    _vm._l(_vm.chargers, function(charger) {
      return _c("Charger", {
        key: charger.id,
        attrs: { charger: charger, terminals: _vm.terminals }
      })
    }),
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-cae93240", module.exports)
  }
}

/***/ }),
/* 36 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var validation = {
  isDigit: function isDigit(e) {
    return e.keyCode >= 48 && e.keyCode <= 57;
  },
  isEnter: function isEnter(e) {
    return e.keyCode === 13;
  },
  isBackspace: function isBackspace(e) {
    return e.keyCode === 8;
  },
  shouldPrevent: function shouldPrevent(e) {
    var isDigit = validation.isDigit,
        isEnter = validation.isEnter,
        isBackspace = validation.isBackspace;

    var shouldNotPrevent = isDigit(e) || isEnter(e) || isBackspace(e);

    return !shouldNotPrevent;
  },
  isAlreadyFilled: function isAlreadyFilled(chargerCode, e) {
    var isBackspace = validation.isBackspace,
        isEnter = validation.isEnter;

    return chargerCode.length >= 4 && !isBackspace(e) && !isEnter(e);
  }
};

/* harmony default export */ __webpack_exports__["a"] = (validation);

/***/ })
/******/ ]);