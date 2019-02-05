/**
 * Scripts.
 *
 * @package    lsx-cf-zoho
 * @subpackage scripts
 */

var lsx_cf_zoho = Object.create( null );

;( function( $, window, document, undefined ) {

    'use strict';

    var $document    = $( document ),
        $window      = $( window ),
        windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
        windowWidth  = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

    lsx_cf_zoho.unblockOnError = function() {
        $( document ).on(  'cf.validate.FormError', function( event, obj ){
            //if ( false == obj.inst.validationResult ) {
                //$('body').each( function() {
                    console.log('unblocking');
                    $('body').unblock();
                //});
            //}
        });
    };

    lsx_cf_zoho.blockOnSubmit = function () {
        $( document ).on( 'cf.form.submit', function (event, data ) {
            var $form = data.$form;
            console.log('blocking');

            $( 'body').block({
                css: {
                    backgroundColor: 'white',
                    border: '1px solid white',
                    borderRadius: '5px',
                    position: 'fixed',
                    top : '50%',
                    left : '50%',
                    transform : 'translate(-50%, -50%)',
                },
                message: '<div class="spinner"><img alt="" src="data:image/svg+xml;base64,CjxzdmcgY2xhc3M9Imxkcy1zcGluIiB3aWR0aD0iMTAwcHgiICBoZWlnaHQ9IjEwMHB4IiAgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmlld0JveD0iMCAwIDEwMCAxMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIiBzdHlsZT0iYmFja2dyb3VuZDogbm9uZTsiPjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDgwLDUwKSI+CjxnIHRyYW5zZm9ybT0icm90YXRlKDApIj4KPGNpcmNsZSBjeD0iMCIgY3k9IjAiIHI9IjEwIiBmaWxsPSIjNWM1ZDVkIiBmaWxsLW9wYWNpdHk9IjEiIHRyYW5zZm9ybT0ic2NhbGUoMC44ODQ4NTMgMC44ODQ4NTMpIj4KICA8YW5pbWF0ZVRyYW5zZm9ybSBhdHRyaWJ1dGVOYW1lPSJ0cmFuc2Zvcm0iIHR5cGU9InNjYWxlIiBiZWdpbj0iLTEuMDVzIiB2YWx1ZXM9IjAuNSAwLjU7MSAxIiBrZXlUaW1lcz0iMDsxIiBkdXI9IjEuMnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIj48L2FuaW1hdGVUcmFuc2Zvcm0+CiAgPGFuaW1hdGUgYXR0cmlidXRlTmFtZT0iZmlsbC1vcGFjaXR5IiBrZXlUaW1lcz0iMDsxIiBkdXI9IjEuMnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiB2YWx1ZXM9IjE7MCIgYmVnaW49Ii0xLjA1cyI+PC9hbmltYXRlPgo8L2NpcmNsZT4KPC9nPgo8L2c+PGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNzEuMjEzMjAzNDM1NTk2NDMsNzEuMjEzMjAzNDM1NTk2NDMpIj4KPGcgdHJhbnNmb3JtPSJyb3RhdGUoNDUpIj4KPGNpcmNsZSBjeD0iMCIgY3k9IjAiIHI9IjEwIiBmaWxsPSIjNWM1ZDVkIiBmaWxsLW9wYWNpdHk9IjAuODc1IiB0cmFuc2Zvcm09InNjYWxlKDAuODIyMzUzIDAuODIyMzUzKSI+CiAgPGFuaW1hdGVUcmFuc2Zvcm0gYXR0cmlidXRlTmFtZT0idHJhbnNmb3JtIiB0eXBlPSJzY2FsZSIgYmVnaW49Ii0wLjg5OTk5OTk5OTk5OTk5OTlzIiB2YWx1ZXM9IjAuNSAwLjU7MSAxIiBrZXlUaW1lcz0iMDsxIiBkdXI9IjEuMnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIj48L2FuaW1hdGVUcmFuc2Zvcm0+CiAgPGFuaW1hdGUgYXR0cmlidXRlTmFtZT0iZmlsbC1vcGFjaXR5IiBrZXlUaW1lcz0iMDsxIiBkdXI9IjEuMnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiB2YWx1ZXM9IjE7MCIgYmVnaW49Ii0wLjg5OTk5OTk5OTk5OTk5OTlzIj48L2FuaW1hdGU+CjwvY2lyY2xlPgo8L2c+CjwvZz48ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSg1MCw4MCkiPgo8ZyB0cmFuc2Zvcm09InJvdGF0ZSg5MCkiPgo8Y2lyY2xlIGN4PSIwIiBjeT0iMCIgcj0iMTAiIGZpbGw9IiM1YzVkNWQiIGZpbGwtb3BhY2l0eT0iMC43NSIgdHJhbnNmb3JtPSJzY2FsZSgwLjc1OTg1MyAwLjc1OTg1MykiPgogIDxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIgdHlwZT0ic2NhbGUiIGJlZ2luPSItMC43NXMiIHZhbHVlcz0iMC41IDAuNTsxIDEiIGtleVRpbWVzPSIwOzEiIGR1cj0iMS4ycyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiPjwvYW5pbWF0ZVRyYW5zZm9ybT4KICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJmaWxsLW9wYWNpdHkiIGtleVRpbWVzPSIwOzEiIGR1cj0iMS4ycyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiIHZhbHVlcz0iMTswIiBiZWdpbj0iLTAuNzVzIj48L2FuaW1hdGU+CjwvY2lyY2xlPgo8L2c+CjwvZz48ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgyOC43ODY3OTY1NjQ0MDM1NzcsNzEuMjEzMjAzNDM1NTk2NDMpIj4KPGcgdHJhbnNmb3JtPSJyb3RhdGUoMTM1KSI+CjxjaXJjbGUgY3g9IjAiIGN5PSIwIiByPSIxMCIgZmlsbD0iIzVjNWQ1ZCIgZmlsbC1vcGFjaXR5PSIwLjYyNSIgdHJhbnNmb3JtPSJzY2FsZSgwLjY5NzM1MyAwLjY5NzM1MykiPgogIDxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIgdHlwZT0ic2NhbGUiIGJlZ2luPSItMC42cyIgdmFsdWVzPSIwLjUgMC41OzEgMSIga2V5VGltZXM9IjA7MSIgZHVyPSIxLjJzIiByZXBlYXRDb3VudD0iaW5kZWZpbml0ZSI+PC9hbmltYXRlVHJhbnNmb3JtPgogIDxhbmltYXRlIGF0dHJpYnV0ZU5hbWU9ImZpbGwtb3BhY2l0eSIga2V5VGltZXM9IjA7MSIgZHVyPSIxLjJzIiByZXBlYXRDb3VudD0iaW5kZWZpbml0ZSIgdmFsdWVzPSIxOzAiIGJlZ2luPSItMC42cyI+PC9hbmltYXRlPgo8L2NpcmNsZT4KPC9nPgo8L2c+PGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMjAsNTAuMDAwMDAwMDAwMDAwMDEpIj4KPGcgdHJhbnNmb3JtPSJyb3RhdGUoMTgwKSI+CjxjaXJjbGUgY3g9IjAiIGN5PSIwIiByPSIxMCIgZmlsbD0iIzVjNWQ1ZCIgZmlsbC1vcGFjaXR5PSIwLjUiIHRyYW5zZm9ybT0ic2NhbGUoMC42MzQ4NTMgMC42MzQ4NTMpIj4KICA8YW5pbWF0ZVRyYW5zZm9ybSBhdHRyaWJ1dGVOYW1lPSJ0cmFuc2Zvcm0iIHR5cGU9InNjYWxlIiBiZWdpbj0iLTAuNDQ5OTk5OTk5OTk5OTk5OTZzIiB2YWx1ZXM9IjAuNSAwLjU7MSAxIiBrZXlUaW1lcz0iMDsxIiBkdXI9IjEuMnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIj48L2FuaW1hdGVUcmFuc2Zvcm0+CiAgPGFuaW1hdGUgYXR0cmlidXRlTmFtZT0iZmlsbC1vcGFjaXR5IiBrZXlUaW1lcz0iMDsxIiBkdXI9IjEuMnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiB2YWx1ZXM9IjE7MCIgYmVnaW49Ii0wLjQ0OTk5OTk5OTk5OTk5OTk2cyI+PC9hbmltYXRlPgo8L2NpcmNsZT4KPC9nPgo8L2c+PGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMjguNzg2Nzk2NTY0NDAzNTcsMjguNzg2Nzk2NTY0NDAzNTc3KSI+CjxnIHRyYW5zZm9ybT0icm90YXRlKDIyNSkiPgo8Y2lyY2xlIGN4PSIwIiBjeT0iMCIgcj0iMTAiIGZpbGw9IiM1YzVkNWQiIGZpbGwtb3BhY2l0eT0iMC4zNzUiIHRyYW5zZm9ybT0ic2NhbGUoMC41NzIzNTMgMC41NzIzNTMpIj4KICA8YW5pbWF0ZVRyYW5zZm9ybSBhdHRyaWJ1dGVOYW1lPSJ0cmFuc2Zvcm0iIHR5cGU9InNjYWxlIiBiZWdpbj0iLTAuM3MiIHZhbHVlcz0iMC41IDAuNTsxIDEiIGtleVRpbWVzPSIwOzEiIGR1cj0iMS4ycyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiPjwvYW5pbWF0ZVRyYW5zZm9ybT4KICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJmaWxsLW9wYWNpdHkiIGtleVRpbWVzPSIwOzEiIGR1cj0iMS4ycyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiIHZhbHVlcz0iMTswIiBiZWdpbj0iLTAuM3MiPjwvYW5pbWF0ZT4KPC9jaXJjbGU+CjwvZz4KPC9nPjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDQ5Ljk5OTk5OTk5OTk5OTk5LDIwKSI+CjxnIHRyYW5zZm9ybT0icm90YXRlKDI3MCkiPgo8Y2lyY2xlIGN4PSIwIiBjeT0iMCIgcj0iMTAiIGZpbGw9IiM1YzVkNWQiIGZpbGwtb3BhY2l0eT0iMC4yNSIgdHJhbnNmb3JtPSJzY2FsZSgwLjUwOTg1MyAwLjUwOTg1MykiPgogIDxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIgdHlwZT0ic2NhbGUiIGJlZ2luPSItMC4xNXMiIHZhbHVlcz0iMC41IDAuNTsxIDEiIGtleVRpbWVzPSIwOzEiIGR1cj0iMS4ycyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiPjwvYW5pbWF0ZVRyYW5zZm9ybT4KICA8YW5pbWF0ZSBhdHRyaWJ1dGVOYW1lPSJmaWxsLW9wYWNpdHkiIGtleVRpbWVzPSIwOzEiIGR1cj0iMS4ycyIgcmVwZWF0Q291bnQ9ImluZGVmaW5pdGUiIHZhbHVlcz0iMTswIiBiZWdpbj0iLTAuMTVzIj48L2FuaW1hdGU+CjwvY2lyY2xlPgo8L2c+CjwvZz48ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSg3MS4yMTMyMDM0MzU1OTY0MywyOC43ODY3OTY1NjQ0MDM1NykiPgo8ZyB0cmFuc2Zvcm09InJvdGF0ZSgzMTUpIj4KPGNpcmNsZSBjeD0iMCIgY3k9IjAiIHI9IjEwIiBmaWxsPSIjNWM1ZDVkIiBmaWxsLW9wYWNpdHk9IjAuMTI1IiB0cmFuc2Zvcm09InNjYWxlKDAuOTQ3MzUzIDAuOTQ3MzUzKSI+CiAgPGFuaW1hdGVUcmFuc2Zvcm0gYXR0cmlidXRlTmFtZT0idHJhbnNmb3JtIiB0eXBlPSJzY2FsZSIgYmVnaW49IjBzIiB2YWx1ZXM9IjAuNSAwLjU7MSAxIiBrZXlUaW1lcz0iMDsxIiBkdXI9IjEuMnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIj48L2FuaW1hdGVUcmFuc2Zvcm0+CiAgPGFuaW1hdGUgYXR0cmlidXRlTmFtZT0iZmlsbC1vcGFjaXR5IiBrZXlUaW1lcz0iMDsxIiBkdXI9IjEuMnMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiB2YWx1ZXM9IjE7MCIgYmVnaW49IjBzIj48L2FuaW1hdGU+CjwvY2lyY2xlPgo8L2c+CjwvZz48L3N2Zz4=" />' +
                    '<h3 class="booking-form-title">' + lsxCfZohoArgs.blockMessage + '</h3></div>',
                baseZ: 1500,
                overlayCSS: {
                    opacity: 0.4,
                    cursor: 'wait'
                }
            });
            lsx_cf_zoho.shiftHeader( $('.blockUI.blockOverlay') );
            lsx_cf_zoho.centerMessage( $('.blockUI.blockMsg') );
        });
    };

    lsx_cf_zoho.unblockForms = function() {
        $( document ).on( 'click', '.alert.alert-success .close', function (event, data ) {
            //$('.caldera_forms_form').each( function() {
                $('body').unblock();
            //});
        });
    };

    lsx_cf_zoho.centerMessage = function ( messageDiv ) {
        messageDiv.css("position","fixed");
        messageDiv.css("top", '50%');
        messageDiv.css("left", '50%');
        messageDiv.css("z-index", '15110');
        return this;
    };

    lsx_cf_zoho.shiftHeader = function ( overlayDiv ) {
        var headerHeight = $(lsxCfZohoArgs.headerSelector).height();
        overlayDiv.css("top",headerHeight+'px');
        overlayDiv.css("z-index", '15000');
        return this;
    };

    /**
     * On document ready.
     */
    $document.ready( function() {
        lsx_cf_zoho.unblockOnError();
        lsx_cf_zoho.blockOnSubmit();
        lsx_cf_zoho.unblockForms();
    } );

} )( jQuery, window, document );