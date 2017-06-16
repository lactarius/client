/**
 * Addressor
 *
 * Google geocoding wrapper plugin
 *
 * @author Petr Blazicek 2016
 */
(function ($) {

  $.fn.addressor = function (options) {

    var opts = $.extend({
      form: {
        saveId: 'lc_save'
      },
      map: {
        mapId: 'lc_map',
        zoom: 17,
        chodska: {lat: 50.074805, lng: 14.445703}
      },
      marker: {
        crossIcon: {
          url: 'https://pb-soft.cz/src/www/images/cross.png',
          anchor: {xOffset: 24, yOffset: 24}
        }
      },
      geoFormAdapter: {
        route: {name: 'street', short: false},
        premise: {name: 'reg_nr', short: false},
        street_number: {name: 'house_nr', short: false},
        postal_code: {name: 'postal', short: false},
        sublocality_level_1: {name: 'district', short: false},
        neighborhood: {name: 'part', short: false},
        locality: {name: 'city', short: false},
        administrative_area_level_1: {name: 'region', short: false},
        country: {name: 'country', short: true}
      }

    }, options);

    var $el = $(this);
    var $elD = $el[0];
    var $mapD = document.getElementById(opts.map.mapId);
    var map;
    var nQ1 = '', nQ2 = '';
    var crossMarker, addressInfo, geocoder;

    checkContainer();
    initialize();

    /**
     * Initialize all
     */
    function initialize () {
      var field, lat, lng, pos, msg = '', info = '', mark = false, browserSupport = false;

      // data in form?
      field = 'input[name=\'' + nQ1 + 'lat' + nQ2 + '\']';
      lat = $(field).val();
      if (lat) {

        field = 'input[name=\'' + nQ1 + 'lng' + nQ2 + '\']';
        lng = $(field).val();
        lat = parseFloat(lat);
        lng = parseFloat(lng);
        mark = true;

        // current position allowed?
      } else if (isSecure() && navigator.geolocation) {
        browserSupport = true;
        navigator.geolocation.getCurrentPosition(function (loc) {

          lat = loc.coords.latitude;
          lng = loc.coords.longitude;
          msg = '<strong>Gotcha!</strong><br>';
          mark = true;

        }, function () {
          handleGeolocationError(browserSupport);
        });
        // default position
      } else {

        lat = opts.map.chodska.lat;
        lng = opts.map.chodska.lng;

      }

      pos = new google.maps.LatLng(lat, lng);
      // draw map
      map = new google.maps.Map($mapD, {
        zoom: opts.map.zoom,
        center: pos
      });
      map.panTo(map.getCenter());

      // initialize marker etc.
      crossMarker = addMarker(map.getCenter(), opts.marker.crossIcon);
      addressInfo = addInfo(crossMarker);
      geocoder = new google.maps.Geocoder();

      // marker?
      if (mark) {
        geocoder.geocode({latLng: pos}, function (responses) {
          if (responses && responses.length > 0) {
            info = responses[0].formatted_address;
          }
        });
        setMarker(crossMarker, pos, true, true);
        if (info) {
          setInfo(addressInfo, crossMarker, msg + info);
          saveAddress(responses[0]);
        }

      }

      initAddressListen(crossMarker, addressInfo);
      initDragListen(crossMarker, addressInfo);
      initFlashes();
    }

    function isSecure () {
      return location.protocol == 'https';
    }

    function checkContainer () {
      var name, nameSplit;
      name = $el.attr('name');
      nameSplit = name.split('[');
      if (nameSplit.length > 1) {
        nQ1 = nameSplit[0] + '[';
        nQ2 = ']';
      }
    }

    /**
     * Sets new location
     * & centers map
     *
     * @param {LatLng} pos
     * @param {int} zoom
     */
    function setMap (pos, zoom) {
      map.setCenter(pos);
      map.panTo(pos);
      map.setZoom(zoom ? zoom : opts.map.zoom);
    }

    /**
     * Adds new marker
     *
     * @param {LatLng} pos
     * @param {Object} icon
     * @param {boolean} draggable
     * @param {boolean} visible
     * @returns {google.maps.Marker}
     */
    function addMarker (pos, icon, draggable, visible) {
      var marker = new google.maps.Marker({
        position: pos,
        map: map,
        draggable: draggable
      });
      if (icon) {
        image = {
          url: icon.url,
          anchor: new google.maps.Point(icon.anchor.xOffset, icon.anchor.yOffset)
        };
        marker.setIcon(image);
      }
      if (!visible) marker.setVisible(false);

      return marker;
    }

    /**
     * Sets Marker position
     *
     * @param {google.maps.Marker} marker
     * @param {LatLng} pos
     * @param {boolean} draggable
     * @param {boolean} visible
     * @returns {google.maps.Marker}
     */
    function setMarker (marker, pos, draggable, visible) {
      marker.setPosition(pos);
      marker.setDraggable(draggable);
      marker.setVisible(visible);

      return marker;
    }

    /**
     * Adds new InfoWindow
     *
     * @param {google.maps.Marker} marker
     * @param {string} content
     * @param {boolean} open
     * @returns {google.maps.InfoWindow|info}
     */
    function addInfo (marker, content, open) {
      info = new google.maps.InfoWindow({
        map: map,
        marker: marker,
        content: content
      });
      if (!open) info.close();

      return info;
    }

    /**
     * Sets info content & marker
     *
     * @param {google.maps.InfoWindow} info
     * @param {google.maps.Marker} marker
     * @param {string} content
     * @returns {google.maps.InfoWindow|info}
     */
    function setInfo (info, marker, content) {
      info.setContent(content);
      info.open(map, marker);

      return info;
    }

    /**
     * Flash messages click listening initialization
     */
    function initFlashes () {
      $('#snippet-locForm-flash').click(function () {
        $(this).html('');
        $el.focus();
      });
    }

    /**
     * CurrenLocation error handler
     *
     * @param {boolean} errorFlag
     */
    function handleGeolocationError (errorFlag) {
      if (errorFlag === true) {
        //alert( "Geolocation service failed." );
      } else {
        alert('Your browser doesn\'t support geolocation.');
        setMap(opts.map.ust_bolchereckaya);
      }
    }

    /**
     * Address search with autocomplete listening initialization
     *
     * @param {google.maps.Marker} marker
     * @param {google.maps.InfoWindow} info
     */
    function initAddressListen (marker, info) {
      // prevent submit
      $el.keypress(function (e) {
        if (e.which == 13) return false;
      });

      // autocomplete
      var autocomplete = new google.maps.places.Autocomplete($elD);

      autocomplete.bindTo('bounds', map);
      autocomplete.addListener('place_changed', function () {
        addressInfo.close();

        var place = autocomplete.getPlace();
        if (!place.geometry) {
          alert('This place contains no geometry.');
          return;
        }

        if (place.geometry.viewport) {
          map.fitBounds(place.geometry.viewport);
        } else {
          setMap(place.geometry.location);
        }
        setMarker(marker, place.geometry.location, true, true);
        setInfo(info, marker, place.formatted_address);
        // erase form on click
        $el.click(function () {
          eraseForm();
        });

        if (place.address_components) {
          saveAddress(place);
        }
      });
    }

    /**
     * Drag search marker listening initialization
     *
     * @param {google.maps.Marker} marker
     * @param {google.maps.InfoWindow} info
     */
    function initDragListen (marker, info) {
      google.maps.event.addListener(marker, 'dragend', function (event) {
        geocoder.geocode({latLng: marker.getPosition()}, function (responses) {
          if (responses && responses.length > 0) {
            setInfo(info, marker, responses[0].formatted_address);
            saveAddress(responses[0]);
          } else {
            alert('No geocode data.');
          }
        });
        map.panTo(marker.getPosition());
      });

      google.maps.event.addListener(marker, 'dragstart', function () {
        info.close();
      });
    }

    /**
     * Analyzes & converts result data
     *
     * @param {Object} result
     */
    function saveAddress (result) {
      var trans = opts.geoFormAdapter;
      var compo = result.address_components;
      var geoKey, field, value;

      eraseForm();

      if (compo.length > 0) {
        $.each(compo, function (k, v) {

          geoKey = v.types[0];
          // set form element value
          if (trans[geoKey]) {
            field = 'input[name=\'' + nQ1 + trans[geoKey].name + nQ2 + '\']';
            value = trans[geoKey].short ? v.short_name : v.long_name;
            $(field).val(value);
          }

        });
        // lat / lng
        field = 'input[name=\'' + nQ1 + 'latlng' + nQ2 + '\']';
        value = JSON.stringify({lat: result.geometry.location.lat(), lng: result.geometry.location.lng()});
        $(field).val(value);

      }
    }

    /**
     * Erases address form
     */
    function eraseForm () {
      $el.val('');	// search field
      $('input[name=\'formatted\']').val('');

      $.each(opts.geoFormAdapter, function (k, v) {
        $('input[name=\'' + v.name + '\']').val('');
      });
    }

  };
})(jQuery);

$(function () {
  if ($('#lc_autocomplete').length) {
    $('#lc_autocomplete').addressor();
  }
});
