/** JS CK Framework **/
/*
* March 2019
* Cédric KEIFLIN
* https://www.joomlack.fr - https://www.template-creator.com
* @version		1.0.0 
*/

$ck = window.$ck || jQuery.noConflict();

CKApi = window.CKApi || {};

/**
 * 
 * Text
 */
(function () {
	'use strict';
	//BC compatibility with CKApi.Text
	var strings = strings || {}
	if (typeof CKApi.Text == 'undefined') {
//		CKApi.Text = {};
		CKApi.Text = {
			strings:   {},

			/**
			 * Translates a string into the current language.
			 *
			 * @param {String} key   The string to translate
			 * @param {String} def   Default string
			 *
			 * @returns {String}
			 */
			'_': function( key, def ) {

				// Check for new strings in the optionsStorage, and load them
				var newStrings = CKApi.Text.getOptions('ckapi.text');
				if ( newStrings ) {
					this.load(newStrings);

					// Clean up the optionsStorage from useless data
					CKApi.Text.loadOptions({'ckapi.text': null});
				}

				def = def === undefined ? '' : def;
				key = key.toUpperCase();

				return this.strings[ key ] !== undefined ? this.strings[ key ] : def;
			},

			/**
			 * Load new strings in to CKApi.Text.JText
			 *
			 * @param {Object} object  Object with new strings
			 * @returns {CKApi.Text.JText}
			 */
			load: function( object ) {
				for ( var key in object ) {
					if (!object.hasOwnProperty(key)) continue;
					this.strings[ key.toUpperCase() ] = object[ key ];
				}

				return this;
			}
		};
//		CKApi.Text.JText = CKApi.Text.Text;

		CKApi.Text.optionsStorage = CKApi.Text.optionsStorage || null;

		CKApi.Text.getOptions = function( key, def ) {
			// Load options if they not exists
			if (!CKApi.Text.optionsStorage) {
				CKApi.Text.loadOptions();
			}

			return CKApi.Text.optionsStorage[key] !== undefined ? CKApi.Text.optionsStorage[key] : def;
		};

		CKApi.Text.loadOptions = function( options ) {
			// Load form the script container
			if (!options) {
				var elements = document.querySelectorAll('.joomla-script-options.new'),
					str, element, option, counter = 0;

				for (var i = 0, l = elements.length; i < l; i++) {
					element = elements[i];
					str     = element.text || element.textContent;
					option  = JSON.parse(str);

					if (option) {
						CKApi.Text.loadOptions(option);
						counter++;
					}

					element.className = element.className.replace(' new', ' loaded');
				}

				if (counter) {
					return;
				}
			}

			// Initial loading
			if (!CKApi.Text.optionsStorage) {
				CKApi.Text.optionsStorage = options || {};
			}
			// Merge with existing
			else if ( options ) {
				for (var p in options) {
					if (options.hasOwnProperty(p)) {
						CKApi.Text.optionsStorage[p] = options[p];
					}
				}
			}
		};
	}
	else {
		CKApi.Text.load(strings);
	}

})();


/* ===========================================================
 * bootstrap-tooltip.js v2.3.2
 * http://twitter.github.com/bootstrap/javascript.html#tooltips
 * Inspired by the original jQuery.tipsy by Jason Frame
 * ===========================================================*/

/**
 * 
 * Tooltip
 */
!function ($) {

  "use strict"; // jshint ;_;


 /* TOOLTIP PUBLIC CLASS DEFINITION
  * =============================== */

var Tooltipck = function (element, options) {
  this.init('cktooltip', element, options)
}

  Tooltipck.prototype = {

    constructor: Tooltipck

  , init: function (type, element, options) {
      var eventIn
        , eventOut
        , triggers
        , trigger
        , i

      this.type = type
      this.$element = $(element)
      this.options = this.getOptions(options)
      this.enabled = true

      triggers = this.options.trigger.split(' ')

      for (i = triggers.length; i--;) {
        trigger = triggers[i]
        if (trigger == 'click') {
          this.$element.on('click.' + this.type, this.options.selector, $.proxy(this.toggle, this))
        } else if (trigger != 'manual') {
          eventIn = trigger == 'hover' ? 'mouseenter' : 'focus'
          eventOut = trigger == 'hover' ? 'mouseleave' : 'blur'
          this.$element.on(eventIn + '.' + this.type, this.options.selector, $.proxy(this.enter, this))
          this.$element.on(eventOut + '.' + this.type, this.options.selector, $.proxy(this.leave, this))
        }
      }

      this.options.selector ?
        (this._options = $.extend({}, this.options, { trigger: 'manual', selector: '' })) :
        this.fixTitle()
    }

  , getOptions: function (options) {
      options = $.extend({}, $.fn[this.type].defaults, this.$element.data(), options)

      if (options.delay && typeof options.delay == 'number') {
        options.delay = {
          show: options.delay
        , hide: options.delay
        }
      }

      return options
    }

  , enter: function (e) {
      var defaults = $.fn[this.type].defaults
        , options = {}
        , self

      this._options && $.each(this._options, function (key, value) {
        if (defaults[key] != value) options[key] = value
      }, this)

      self = $(e.currentTarget)[this.type](options).data(this.type)

      if (!self.options.delay || !self.options.delay.show) return self.show()

      clearTimeout(this.timeout)
      self.hoverState = 'in'
      this.timeout = setTimeout(function() {
        if (self.hoverState == 'in') self.show()
      }, self.options.delay.show)
    }

  , leave: function (e) {
      var self = $(e.currentTarget)[this.type](this._options).data(this.type)

      if (this.timeout) clearTimeout(this.timeout)
      if (!self.options.delay || !self.options.delay.hide) return self.hide()

      self.hoverState = 'out'
      this.timeout = setTimeout(function() {
        if (self.hoverState == 'out') self.hide()
      }, self.options.delay.hide)
    }

  , show: function () {
      var $tip
        , pos
        , actualWidth
        , actualHeight
        , placement
        , tp
        , e = $.Event('show')

      if (this.hasContent() && this.enabled) {
        this.$element.trigger(e)
        if (e.isDefaultPrevented()) return
        $tip = this.tip()
        this.setContent()

        if (this.options.animation) {
          $tip.addClass('fade')
        }

        placement = typeof this.options.placement == 'function' ?
          this.options.placement.call(this, $tip[0], this.$element[0]) :
          this.options.placement

        $tip
          .detach()
          .css({ top: 0, left: 0, display: 'block' })

        this.options.container ? $tip.appendTo(this.options.container) : $tip.insertAfter(this.$element)

        pos = this.getPosition()

        actualWidth = $tip[0].offsetWidth
        actualHeight = $tip[0].offsetHeight

        switch (placement) {
          case 'bottom':
            tp = {top: pos.top + pos.height, left: pos.left + pos.width / 2 - actualWidth / 2}
            break
          case 'top':
            tp = {top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2}
            break
          case 'left':
            tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth}
            break
          case 'right':
            tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width}
            break
        }

        this.applyPlacement(tp, placement)
        this.$element.trigger('shown')
      }
    }

  , applyPlacement: function(offset, placement){
      var $tip = this.tip()
        , width = $tip[0].offsetWidth
        , height = $tip[0].offsetHeight
        , actualWidth
        , actualHeight
        , delta
        , replace

      $tip
        .offset(offset)
        .addClass(placement)
        .addClass('in')

      actualWidth = $tip[0].offsetWidth
      actualHeight = $tip[0].offsetHeight

      if (placement == 'top' && actualHeight != height) {
        offset.top = offset.top + height - actualHeight
        replace = true
      }

      if (placement == 'bottom' || placement == 'top') {
        delta = 0

        if (offset.left < 0){
          delta = offset.left * -2
          offset.left = 0
          $tip.offset(offset)
          actualWidth = $tip[0].offsetWidth
          actualHeight = $tip[0].offsetHeight
        }

        this.replaceArrow(delta - width + actualWidth, actualWidth, 'left')
      } else {
        this.replaceArrow(actualHeight - height, actualHeight, 'top')
      }

      if (replace) $tip.offset(offset)
    }

  , replaceArrow: function(delta, dimension, position){
      this
        .arrow()
        .css(position, delta ? (50 * (1 - delta / dimension) + "%") : '')
    }

  , setContent: function () {
      var $tip = this.tip()
        , title = this.getTitle()

		$tip.css('z-index', this.options.zindex);
      $tip.find('.cktooltip-inner')[this.options.html ? 'html' : 'text'](title)
      $tip.removeClass('fade in top bottom left right')
    }

  , hide: function () {
	  // JOOMLA JUI >>>
	  /* ORIGINAL:
      var that = this
        , $tip = this.tip()
        , e = $.Event('hide')
      */
      var that = this
        , $tip = this.tip()
        , e = $.Event('hideme')
      // < CKApi.Text JUI

      this.$element.trigger(e)
      if (e.isDefaultPrevented()) return

      $tip.removeClass('in')

      function removeWithAnimation() {
        var timeout = setTimeout(function () {
          $tip.off($.support.transition.end).detach()
        }, 500)

        $tip.one($.support.transition.end, function () {
          clearTimeout(timeout)
          $tip.detach()
        })
      }

      $.support.transition && this.$tip.hasClass('fade') ?
        removeWithAnimation() :
        $tip.detach()

      this.$element.trigger('hidden')

      return this
    }

  , fixTitle: function () {
      var $e = this.$element
      if ($e.attr('title') || typeof($e.attr('data-original-title')) != 'string') {
        $e.attr('data-original-title', $e.attr('title') || '').attr('title', '')
      }
    }

  , hasContent: function () {
      return this.getTitle()
    }

  , getPosition: function () {
      var el = this.$element[0]
      return $.extend({}, (typeof el.getBoundingClientRect == 'function') ? el.getBoundingClientRect() : {
        width: el.offsetWidth
      , height: el.offsetHeight
      }, this.$element.offset())
    }

  , getTitle: function () {
      var title
        , $e = this.$element
        , o = this.options

      title = $e.attr('data-original-title')
        || (typeof o.title == 'function' ? o.title.call($e[0]) :  o.title)

      return title
    }

  , tip: function () {
      return this.$tip = this.$tip || $(this.options.template)
    }

  , arrow: function(){
      return this.$arrow = this.$arrow || this.tip().find(".cktooltip-arrow")
    }

  , validate: function () {
      if (!this.$element[0].parentNode) {
        this.hide()
        this.$element = null
        this.options = null
      }
    }

  , enable: function () {
      this.enabled = true
    }

  , disable: function () {
      this.enabled = false
    }

  , toggleEnabled: function () {
      this.enabled = !this.enabled
    }

  , toggle: function (e) {
      var self = e ? $(e.currentTarget)[this.type](this._options).data(this.type) : this
      self.tip().hasClass('in') ? self.hide() : self.show()
    }

  , destroy: function () {
      this.hide().$element.off('.' + this.type).removeData(this.type)
    }

  }


 /* TOOLTIP PLUGIN DEFINITION
  * ========================= */

  var old = $.fn.cktooltip

  $.fn.cktooltip = function ( option ) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('cktooltip')
        , options = typeof option == 'object' && option
      if (!data) $this.data('cktooltip', (data = new Tooltipck(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.cktooltip.Constructor = Tooltipck

  $.fn.cktooltip.defaults = {
    animation: true
  , placement: 'top'
  , selector: false
  , template: '<div class="cktooltip"><div class="cktooltip-arrow"></div><div class="cktooltip-inner"></div></div>'
  , trigger: 'hover focus'
  , title: ''
  , delay: 100
  // JOOMLA JUI >>>
  /* ORIGINAL:
  , html: false
  */
  , html: true
  // < CKApi.Text JUI
  , container: "body"
  , zindex: "10030"
  }


 /* CK API CALL
  * =================== */
CKApi.Tooltip = function(el, options) { $(el).cktooltip(options); };

 /* TOOLTIP NO CONFLICT
  * =================== */

  $.fn.cktooltip.noConflict = function () {
    $.fn.cktooltip = old
    return this
  }

}(window.jQuery);