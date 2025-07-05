jQuery(function($) {
    'use strict';

    // استهداف نموذج الاتصال
    let form = $('.contact-form');
    
    form.submit(function (event) {
        event.preventDefault(); // منع إعادة تحميل الصفحة
        let $this = $(this);

        $.post("sendemail.php", $this.serialize(), function(result) {
            if (result && result.type === 'success') {
                $this.prev().text(result.message).fadeIn().delay(3000).fadeOut();
            } else {
                console.error("Error: Invalid response from server.");
            }
        }).fail(function() {
            console.error("Error: Could not connect to server.");
        });
    });
});

// إعداد خريطة جوجل
(function() {
    'use strict';

    if (typeof GMaps === 'undefined') {
        console.error("Error: GMaps library is not loaded.");
        return;
    }

    let map = new map({
        el: '#gmap',
        lat: 43.1580159,
        lng: -77.6030777,
        scrollwheel: false,
        zoom: 14,
        zoomControl: false,
        panControl: false,
        streetViewControl: false,
        mapTypeControl: false,
        overviewMapControl: false,
        clickable: false
    });

    // أيقونة العلامة على الخريطة
    map.addMarker({
        lat: 43.1580159,
        lng: -77.6030777,
        // eslint-disable-next-line no-undef
        animation: google.maps.Animation.DROP,
        verticalAlign: 'bottom',
        horizontalAlign: 'center',
        backgroundColor: '#ffffff'
    });

    // أنماط الخريطة المخصصة
    let styles = [
        {
            "featureType": "road",
            "stylers": [{ "color": "" }]
        },
        {
            "featureType": "water",
            "stylers": [{ "color": "#A2DAF2" }]
        },
        {
            "featureType": "landscape",
            "stylers": [{ "color": "#ABCE83" }]
        },
        {
            "elementType": "labels.text.fill",
            "stylers": [{ "color": "#000000" }]
        },
        {
            "featureType": "poi",
            "stylers": [{ "color": "#2ECC71" }]
        },
        {
            "elementType": "labels.text",
            "stylers": [
                { "saturation": 1 },
                { "weight": 0.1 },
                { "color": "#111111" }
            ]
        }
    ];

    map.addStyle({
        styledMapName: "Styled Map",
        styles: styles,
        mapTypeId: "map_style"
    });

    map.setStyle("map_style");
})();
