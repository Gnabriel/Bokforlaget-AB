var isAdminBar = false,
        isEditMode = false;
(function ($) {

    var elementSettingsMap = {};
    var map;
    var bounds;

    var DyncontEl_GoogleMapsHandler = function ($scope, $) {
        //console.log( $scope );

        elementSettingsMap = get_Dyncontel_ElementSettings($scope);
        var id_scope = $scope.attr('data-id');
        //alert(id_scope);
        var indirizzo = $scope.find('#el-wgt-map-' + id_scope).data('address');
        var lati = $scope.find('#el-wgt-map-' + id_scope).data('lat') || 0;
        var long = $scope.find('#el-wgt-map-' + id_scope).data('lng') || 0;
        var infoWindow = $scope.find('#el-wgt-map-' + id_scope).data('infowindow') || 'Empty Info Window';

        //alert(indirizzo+' '+lati+' - '+long);
        var imageMarker = $scope.find('#el-wgt-map-' + id_scope).data('imgmarker') || '';

        //
        var zoom = elementSettingsMap.zoom.size;

        //alert('GoogleMaps '+elementSettingsMap.zoom.size+' '+indirizzo);
        //alert('dce js '+$($scope.context).find('#map').attr('data-height'));
        //alert(indirizzo);
        //$($scope.context).find('#map').height($($scope.context).find('#map').attr('data-height'));

        var elements = $scope.find('#el-wgt-map-' + id_scope)[0]; //document.getElementById('el-wgt-map');
        debugHolder = $scope.find('#debug')[0]; //document.getElementById("debug");
        var centroMappa = {lat: lati, lng: long};
        //setTimeout(function(){ alert('Testtt: ' + $scope.find('#el-wgt-map-'+id_scope).length) }, 500 );;
        //

        //
        var mapParams = {
            zoom: zoom,
            scrollwheel: 'yes' === elementSettingsMap.prevent_scroll,
            mapTypeControl: 'yes' === elementSettingsMap.maptypecontrol,
            panControl: 'yes' === elementSettingsMap.pancontrol,
            rotateControl: 'yes' === elementSettingsMap.rotaterontrol,
            scaleControl: 'yes' === elementSettingsMap.scalecontrol,
            streetViewControl: 'yes' === elementSettingsMap.streetviewcontrol,
            zoomControl: 'yes' === elementSettingsMap.zoomcontrol,
            fullscreenControl: 'yes' === elementSettingsMap.fullscreenControl,
            center: centroMappa
        };

        if (elementSettingsMap.map_type != "") {
            mapParams['mapTypeId'] = elementSettingsMap.map_type;
        }
        if (elementSettingsMap.style_map != "" && elementSettingsMap.style_select == 'custom') {
            mapParams['styles'] = eval(elementSettingsMap.style_map);

            fireMap(elements, mapParams);

        } else if (elementSettingsMap.style_select == 'prestyle') {

            var fileStyle = elementSettingsMap.snazzy_select;
            //alert(fileStyle+".json");
            $.getJSON(fileStyle + ".json", function (json) {
                //console.log(json); // this will show the info it in firebug console
                mapParams['styles'] = json;

                fireMap(elements, mapParams);
            });

        } else {
            fireMap(elements, mapParams);
        }

        function fireMap(elements_map, mapParams_map) {
            map = new google.maps.Map(elements_map, mapParams_map);

            var mapDataType = elementSettingsMap.map_data_type;

            // ---- se è una query su posts che usano acf ----------- 
            if (elementSettingsMap.use_query == 'yes') {
                bounds = new google.maps.LatLngBounds();

                for (i = 0; i < address_list.length; i++) {

                    if (mapDataType == 'address') {
                        addressToLocation(
                                address_list[i]['address'],
                                address_list[i]['marker'],
                                address_list[i]['infoWindow'],
                                address_list[i]['postLink'],
                                changeMapLocation);

                    } else if (mapDataType == 'latlng') {

                        //alert('mapLatLong: '+address_list[i]['lat']+' '+address_list[i]['lng']);
                        var latLng = new google.maps.LatLng(address_list[i]['lat'], address_list[i]['lng']); //Makes a latlng
                        map.panTo(latLng); //Make map global
                        //alert(address_list[i]['postlink']);

                        var marker = new google.maps.Marker({
                            position: latLng,
                            map: map,
                            icon: address_list[i]['marker'],
                            animation: google.maps.Animation.DROP, //.DROP.BOUNCE,
                        });
                        console.log(marker);
                        //
                        bounds.extend(marker.position);
                        //

                        if (elementSettingsMap.enable_infoWindow == 'yes') {



                            google.maps.event.addListener(marker, 'click', (function (marker, k) {
                                //
                                return function () {

                                    if (elementSettingsMap.infoWindow_click_to_post == 'yes') {
                                        if (isEditMode) {
                                            alert('You have clicked: ' + address_list[k]['postLink']);
                                            return false;
                                        } else {
                                            window.location = address_list[k]['postLink'];
                                        }
                                    } else {
                                        var infoWindowMap = new google.maps.InfoWindow({
                                            content: address_list[k]['infoWindow']
                                        });
                                        infoWindowMap.open(map, marker);
                                    }

                                }
                                //
                            })(marker, i));

                        }
                    }
                    map.fitBounds(bounds);
                    //alert(address_list[i]['marker']);
                    //alert('query');
                }

            } else {
                // ---- se non è una query... pesco dal singolo -----------

                if (mapDataType == 'address') {
                    addressToLocation(indirizzo, elementSettingsMap.imageMarker['url'], infoWindow, null, changeMapLocation);
                } else if (mapDataType == 'latlng') {

                    //alert('mapLatLong: '+lati+' '+long);

                    var latLng = new google.maps.LatLng(lati, long); //Makes a latlng
                    map.panTo(latLng); //Make map global


                    var infoWindowMap = new google.maps.InfoWindow({
                        content: infoWindow
                    });


                    var marker = new google.maps.Marker({
                        position: latLng, //centroMappa,
                        map: map,
                        icon: imageMarker,
                        animation: google.maps.Animation.DROP,
                        //title:"Hello World!",
                        //label: 'aaaaaa'
                        //infowindow: 'CIAO'
                    });

                    if (elementSettingsMap.enable_infoWindow == 'yes') {
                        marker.addListener('click', function () {
                            infoWindowMap.open(map, this);
                        });
                    }

                }
                //alert(elementSettingsMap.map_data_type);
            }
        }
    };

    function changeMapLocation(locations) {

        if (locations && locations.length) {

            /*var numOfLocations = locations.length;
             for(var i=0; i<numOfLocations; i++) {	
             //log("- " + locations[i].text + " / <strong>" + locations[i].location.toString() + "</strong>");
             var marker = new google.maps.Marker({
             map: map,
             position: locations[i].location
             });
             }
             */
            //alert(elementSettingsMap.imageMarker['url']);
            //alert(locations[0].marker);
            console.log(locations[0]);

            var image = {
                url: locations[0].marker, //elementSettingsMap.imageMarker['url'],
                // This marker is 20 pixels wide by 32 pixels high.
                //size: new google.maps.Size(190, 190),
                // The origin for this image is (0, 0).
                //origin: new google.maps.Point(0, 0),
                // The anchor for this image is the base of the flagpole at (0, 32).
                //anchor: new google.maps.Point(0, 32)
            };
            var shape = {
                coords: [1, 1, 1, 20, 18, 20, 18, 1],
                type: 'poly'
            };
            var objMarker = {
                map: map,
                position: locations[0].location,
                //icon: image,
                //shape: shape,
            };
            //alert(locations[0].location);
            if (elementSettingsMap.imageMarker['url'] != "") {
                objMarker['icon'] = image;
            }
            var marker = new google.maps.Marker(objMarker);



            var infoWindowMap = new google.maps.InfoWindow({
                content: locations[0].infoWindow,
            });
            map.panTo(locations[0].location);

            if (elementSettingsMap.enable_infoWindow == 'yes') {
                marker.addListener('click', function () {
                    //alert(locations[0].infoWindow);
                    //alert(locations[0].postLink);


                    if (elementSettingsMap.infoWindow_click_to_post == 'yes') {
                        if (isEditMode) {
                            alert('You have clicked: ' + locations[0].postLink);
                            return false;
                        } else {
                            window.location = locations[0].postLink;
                        }
                    } else {
                        infoWindowMap.open(map, marker);
                    }
                });
            }
            if (elementSettingsMap.use_query == 'yes') {
                bounds.extend(marker.position);
                map.fitBounds(bounds);
            }
            //
        } else {
            log("Num of results: 0");
        }
    }

    function addressToLocation(address, markerimg, iw, pl, callback) {
        //alert('addressToLocation '+address+' '+markerimg);

        // ********* geocoder converte l'indirizzo in posizioni lat-lon **********************************
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode(
                {
                    address: address
                },
                function (results, status) {

                    var resultLocations = [];

                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results) {
                            var numOfResults = results.length;
                            for (var i = 0; i < numOfResults; i++) {
                                var result = results[i];
                                resultLocations.push(
                                        {
                                            text: result.formatted_address,
                                            addressStr: result.formatted_address,
                                            location: result.geometry.location,
                                            marker: markerimg,
                                            postLink: pl,
                                            infoWindow: iw
                                        }
                                );
                            }
                            ;
                        }
                    } else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {
                        // address not found
                    }

                    if (resultLocations.length > 0) {
                        callback(resultLocations);
                        //console.log(resultLocations);
                    } else {
                        callback(null);
                    }
                }
        );
        // ***********************************************************************************************

    }
    // debugging
    function log(str, clear) {

        if (clear) {
            debugHolder.innerHTML = "";
        }
        debugHolder.innerHTML = debugHolder.innerHTML + "<br />" + str;
    }
    // Make sure you run this code under Elementor..
    $(window).on('elementor/frontend/init', function () {
        if (elementorFrontend.isEditMode()) {
            isEditMode = true;
        }

        if ($('body').is('.admin-bar')) {
            isAdminBar = true;
        }
        //alert('inittt');
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-acf-google-maps.default', DyncontEl_GoogleMapsHandler);
    });

})(jQuery);