(function ( $ ) {

    $.fn.textSlider = function ( options ) {

        /* Default settings */
        var settings = $.extend({
            timeout:     7500,
            nextItem:    0,
            currentItem: 1,
            count:       1,
			overlay:     0,
			debug:       0,
			classItem: ''
        }, options );
		
		if ( settings.debug == 1 )
			console.log('Starting slider');
			
		if ( settings.overlay == 1 )
		{	
		
			if ( settings.debug == 1 )
				console.log('Creating overlay div');
				
			this.wrapInner('<div class="slide-overlay"></div>');
			
		}

		if ( settings.debug == 1 )
			console.log('Starting loop trough items');


        this.children((settings.classItem ? '.' + settings.classItem : '')).each(function () {
		
			if ( settings.debug == 1 )
				console.log('loop item: ' + $(this) );

            $(this).addClass( 'slide-' + settings.count ).css({opacity: 0, paddingTop: '100px', paddingBottom: '0px'}).hide();

            settings.count = settings.count + 1;

        });

        function transition ()
        {
		
			if ( settings.debug == 1)
				console.log('Starting new transition ' + settings.currentItem );

            settings.nextItem = settings.currentItem + 1;
            if ( settings.nextItem >= settings.count )
                settings.nextItem = 1;

            $('.slide-' + settings.currentItem ).animate({ paddingTop: '100px', paddingBottom: '0px', opacity: 0 }, 750, function () {
                $(this).hide();
                $('.slide-' + settings.nextItem ).show().animate({ paddingTop: '50px', paddingBottom: '50px', opacity: 1 }, 750);

                settings.currentItem = settings.nextItem;

            });

            setTimeout ( transition, settings.timeout );
        }

        return transition ();

    };

}( jQuery ));