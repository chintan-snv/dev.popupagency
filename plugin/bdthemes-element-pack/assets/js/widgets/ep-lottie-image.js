(function ($, elementor) {

    'use strict';

    // Accordion
    var widgetLottieImage = function ($scope, $) {

        var $lottie = $scope.find('.bdt-lottie-container'),
            $settings = $lottie.data('settings');

        if (!$lottie.length) {
            return;
        }

        var lottieContainer = document.getElementById( $($lottie).attr('id') );

        //console.log($settings);
        function lottieRun(lottieContainer) {

            var animation = lottie.loadAnimation({
                container: lottieContainer, // Required
                path: $settings.json_url, // Required
                renderer: 'svg', // Required
                autoplay: ('autoplay' === $settings.play_action), // Optional
                loop: $settings.loop // Optional
            });

            if (1 >= $settings.speed) {
                animation.setSpeed($settings.speed);
            }

            if ( $settings.play_action) {


                if ( 'column' === $settings.play_action ) {
                    lottieContainer = $scope.closest('.elementor-widget-wrap')[0];
                }

                if ( 'section' === $settings.play_action ) {
                    lottieContainer = $scope.closest('.elementor-section')[0];
                }


                if ( 'click' === $settings.play_action ) {
                    lottieContainer.addEventListener('click', function () {
                        animation.goToAndPlay(0);
                    });

                } else if ( 'autoplay' !== $settings.play_action )  {

                    lottieContainer.addEventListener('mouseenter', function () {
                        animation.goToAndPlay(0);
                    });
                    // lottieContainer.addEventListener('mouseleave', function () {
                    //     animation.stop();
                    // });


                }

            }

        }


        if ('scroll' === $settings.view_type) {
            elementorFrontend.waypoint($lottie, function () {
                lottieRun(lottieContainer);
            });
        } else {
            lottieRun(lottieContainer);
        }


    };


    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/bdt-lottie-image.default', widgetLottieImage);
    });

}(jQuery, window.elementorFrontend));