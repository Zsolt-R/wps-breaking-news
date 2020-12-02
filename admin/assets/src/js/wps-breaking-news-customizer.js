(function($) {
    // Setting ID / Property
    const settings = {
        'wps_bn_customizer_options[text_color]': '--breaking-news-text-color',
        'wps_bn_customizer_options[background_color]': '--breaking-news-background-color',
        'wps_bn_customizer_options[link_color]': '--breaking-news-link-color',
    };

    // Run settings
    Object.entries(settings).forEach(entry => {

        const [settingId, property] = entry;

        wp.customize(settingId, function(value) {
                value.bind(function(newval) {
                        document.documentElement.style.setProperty(property, newval,
                        );
                    }
                );
            }
        );
    });
})(jQuery);