@extends('layouts.app')

@section('content')

<?php
global $Locations;
$pins = find_locations();

?>
<script src="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=msb_map_pins" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxA2ubQryWiqWt2ZjECs67pBZ4-JgfYBM&hl=dk&libraries=place&libraries=visualization">
</script>
<script src="{{ \App\asset_path('scripts/markercluster.js') }}"></script>
<script src="{{ \App\asset_path('scripts/StyledMarker.js') }}"></script>

<script type="text/javascript">
    function initMap(data) {

        obj = Object.keys(data).length;
        if (obj > 0) {


      let mapDiv = document.getElementById('map');
      let map = new google.maps.Map(mapDiv, {
        center: {
          lat: 50.096798,
          lng: 18.542936,
        },
        zoom: 8,
        styles: [{
          'featureType': 'administrative',
          'elementType': 'all',
          'stylers': [{
            'saturation': '-100',
          }],
        }, {
          'featureType': 'administrative.province',
          'elementType': 'all',
          'stylers': [{
            'visibility': 'off',
          }],
        }, {
          'featureType': 'landscape',
          'elementType': 'all',
          'stylers': [{
            'saturation': -100,
          }, {
            'lightness': 65,
          }, {
            'visibility': 'on',
          }],
        }, {
          'featureType': 'poi',
          'elementType': 'all',
          'stylers': [{
            'saturation': -100,
          }, {
            'lightness': '50',
          }, {
            'visibility': 'simplified',
          }],
        }, {
          'featureType': 'road',
          'elementType': 'all',
          'stylers': [{
            'saturation': '-100',
          }],
        }, {
          'featureType': 'road.highway',
          'elementType': 'all',
          'stylers': [{
            'visibility': 'simplified',
          }],
        }, {
          'featureType': 'road.arterial',
          'elementType': 'all',
          'stylers': [{
            'lightness': '30',
          }],
        }, {
          'featureType': 'road.local',
          'elementType': 'all',
          'stylers': [{
            'lightness': '40',
          }],
        }, {
          'featureType': 'transit',
          'elementType': 'all',
          'stylers': [{
            'saturation': -100,
          }, {
            'visibility': 'simplified',
          }],
        }, {
          'featureType': 'water',
          'elementType': 'geometry',
          'stylers': [{
            'hue': '#ffff00',
          }, {
            'lightness': -25,
          }, {
            'saturation': -97,
          }],
        }, {
          'featureType': 'water',
          'elementType': 'labels',
          'stylers': [{
            'lightness': -25,
          }, {
            'saturation': -100,
          }],
        }],
      });
        //markers
        var markers = data.map(function (location, i) {

        var pos = new google.maps.LatLng(location.lat, location.lng);
        var image = {
                url: '<?php echo App\asset_path("images/bus-stop.png"); ?>',
            }

        var marker = new google.maps.Marker({
            position: pos,
            map: map,
            icon: image,
        });

        return marker;
        });

        // var latlong = data.map(function (longlats, i) {
        //     latlong = {location: new google.maps.LatLng(longlats.lat, longlats.lng), weight: 0.4}
        //     return latlong;
        // });
        // console.log(latlong)
        // var heatmap = new google.maps.visualization.HeatmapLayer({
        //     data: latlong,
        //     radius: 100,
        //     dissipating: true,
        // });
        // heatmap.setMap(map);
    }


}
function initMap2(data) {

obj = Object.keys(data).length;
if (obj > 0) {


let mapDiv = document.getElementById('map2');
let map = new google.maps.Map(mapDiv, {
center: {
  lat: 50.096798,
  lng: 18.542936,
},
zoom: 8,
styles: [{
  'featureType': 'administrative',
  'elementType': 'all',
  'stylers': [{
    'saturation': '-100',
  }],
}, {
  'featureType': 'administrative.province',
  'elementType': 'all',
  'stylers': [{
    'visibility': 'off',
  }],
}, {
  'featureType': 'landscape',
  'elementType': 'all',
  'stylers': [{
    'saturation': -100,
  }, {
    'lightness': 65,
  }, {
    'visibility': 'on',
  }],
}, {
  'featureType': 'poi',
  'elementType': 'all',
  'stylers': [{
    'saturation': -100,
  }, {
    'lightness': '50',
  }, {
    'visibility': 'simplified',
  }],
}, {
  'featureType': 'road',
  'elementType': 'all',
  'stylers': [{
    'saturation': '-100',
  }],
}, {
  'featureType': 'road.highway',
  'elementType': 'all',
  'stylers': [{
    'visibility': 'simplified',
  }],
}, {
  'featureType': 'road.arterial',
  'elementType': 'all',
  'stylers': [{
    'lightness': '30',
  }],
}, {
  'featureType': 'road.local',
  'elementType': 'all',
  'stylers': [{
    'lightness': '40',
  }],
}, {
  'featureType': 'transit',
  'elementType': 'all',
  'stylers': [{
    'saturation': -100,
  }, {
    'visibility': 'simplified',
  }],
}, {
  'featureType': 'water',
  'elementType': 'geometry',
  'stylers': [{
    'hue': '#ffff00',
  }, {
    'lightness': -25,
  }, {
    'saturation': -97,
  }],
}, {
  'featureType': 'water',
  'elementType': 'labels',
  'stylers': [{
    'lightness': -25,
  }, {
    'saturation': -100,
  }],
}],
});
//markers
var markers = data.map(function (location, i) {

var pos = new google.maps.LatLng(location.lat, location.lng);
var image = {
        url: '<?php echo App\asset_path("images/bus-stop.png"); ?>',
    }

var marker = new google.maps.Marker({
    position: pos,
    map: map,
    icon: image,
});

return marker;
});


}


}
</script>


</div>
</div>
<script>
    google.maps.event.addDomListener(window, 'load', function () {
    initMap(data);
    initMap2(data);
});


</script>
<div class="head">
    {{-- <div id="map"></div> --}}
    <div class="container-fluid">
        <div class="row pt-3">
            <div class="col-6">
                <h2>Mapa wejść</h2>
                <div id="map"></div>
            </div>
            <div class="col-6">
                <h2>Mapa wyjść</h2>
                <div id="map2"></div>
            </div>
        </div>
    </div>
<div class="container">
    <div class="row pt-4">
        <div class="col-12">
            <div class="filter">
                <h2>Filtrowanie</h2>
                <p>Zakres dat rozpoczyna się od 01.09.2021 a kończy się na 6.09.2021. Dane zostały celowo ograniczone na potrzeby prezentacji.</p>
                <form action="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=map_search_form" class="small jn" method="GET" id="search-form">
        
                <label for="date-from">
                    Data startu
                    <input type="date" id="date-from" class="js--date-from" name="date_start">
                </label>
                <label for="date-to">
                    Data końca
                    <input type="date" id="date-to" class="js--date-to" name="date_end">
                </label>
                <label for="time-from">
                    Czas wejscia
                    <input type="time" id="time-from" class="js--time-from" name="time_start">
                </label>
                <label for="time-to">
                    Czas wyjscia
                    <input type="time" id="time-to" class="js--time-to" name="time_end">
                </label>
                <button type="submit">Pokaz</button>
             </form>
            </div>
        </div>
    </div>
</div>
</div>
<script>

function mapForm() {

jQuery('#search-form').submit(function (event) {
  event.preventDefault();
  mapFormSubmit();
});
}
mapForm();
function mapFormSubmit() {
var form = jQuery('#search-form');

var formAction = jQuery(form).attr('action');
var formValues = jQuery(form).serialize();

jQuery.ajax({
  type: 'POST',
  url: formAction,
  data: formValues,

  success: function (response) {
    // console.log(response);
    let mapDiv = document.getElementById('map');
      let map = new google.maps.Map(mapDiv, {
        center: {
          lat: 50.096798,
          lng: 18.542936,
        },
        zoom: 11,
        styles: [{
          'featureType': 'administrative',
          'elementType': 'all',
          'stylers': [{
            'saturation': '-100',
          }],
        }, {
          'featureType': 'administrative.province',
          'elementType': 'all',
          'stylers': [{
            'visibility': 'off',
          }],
        }, {
          'featureType': 'landscape',
          'elementType': 'all',
          'stylers': [{
            'saturation': -100,
          }, {
            'lightness': 65,
          }, {
            'visibility': 'on',
          }],
        }, {
          'featureType': 'poi',
          'elementType': 'all',
          'stylers': [{
            'saturation': -100,
          }, {
            'lightness': '50',
          }, {
            'visibility': 'simplified',
          }],
        }, {
          'featureType': 'road',
          'elementType': 'all',
          'stylers': [{
            'saturation': '-100',
          }],
        }, {
          'featureType': 'road.highway',
          'elementType': 'all',
          'stylers': [{
            'visibility': 'simplified',
          }],
        }, {
          'featureType': 'road.arterial',
          'elementType': 'all',
          'stylers': [{
            'lightness': '30',
          }],
        }, {
          'featureType': 'road.local',
          'elementType': 'all',
          'stylers': [{
            'lightness': '40',
          }],
        }, {
          'featureType': 'transit',
          'elementType': 'all',
          'stylers': [{
            'saturation': -100,
          }, {
            'visibility': 'simplified',
          }],
        }, {
          'featureType': 'water',
          'elementType': 'geometry',
          'stylers': [{
            'hue': '#ffff00',
          }, {
            'lightness': -25,
          }, {
            'saturation': -97,
          }],
        }, {
          'featureType': 'water',
          'elementType': 'labels',
          'stylers': [{
            'lightness': -25,
          }, {
            'saturation': -100,
          }],
        }],
      });
        //markers
        var markers = data.map(function (location, i) {

        var pos = new google.maps.LatLng(location.lat, location.lng);
        var image = {
                url: '<?php echo App\asset_path("images/bus-stop.png"); ?>',
            }

        var marker = new google.maps.Marker({
            position: pos,
            map: map,
            icon: image,
        });

        return marker;
        });
        
        var latlong = data.map(function (longlats, i) {
            if (response[0][longlats.title]) {
                var waga = response[longlats.title];
                latlong = {location: new google.maps.LatLng(longlats.lat, longlats.lng), weight: waga}
            }
            return latlong;
        });
        // console.log(latlong.title)
        var heatmap = new google.maps.visualization.HeatmapLayer({
            data: latlong,
            radius: 50,
            dissipating: true,
        });
        heatmap.setMap(map);
    // jQuery('.search-results').html(response.list);
    // initialize(response.data);
    let mapDiv2 = document.getElementById('map2');
      let map2 = new google.maps.Map(mapDiv2, {
        center: {
          lat: 50.096798,
          lng: 18.542936,
        },
        zoom: 11,
        styles: [{
          'featureType': 'administrative',
          'elementType': 'all',
          'stylers': [{
            'saturation': '-100',
          }],
        }, {
          'featureType': 'administrative.province',
          'elementType': 'all',
          'stylers': [{
            'visibility': 'off',
          }],
        }, {
          'featureType': 'landscape',
          'elementType': 'all',
          'stylers': [{
            'saturation': -100,
          }, {
            'lightness': 65,
          }, {
            'visibility': 'on',
          }],
        }, {
          'featureType': 'poi',
          'elementType': 'all',
          'stylers': [{
            'saturation': -100,
          }, {
            'lightness': '50',
          }, {
            'visibility': 'simplified',
          }],
        }, {
          'featureType': 'road',
          'elementType': 'all',
          'stylers': [{
            'saturation': '-100',
          }],
        }, {
          'featureType': 'road.highway',
          'elementType': 'all',
          'stylers': [{
            'visibility': 'simplified',
          }],
        }, {
          'featureType': 'road.arterial',
          'elementType': 'all',
          'stylers': [{
            'lightness': '30',
          }],
        }, {
          'featureType': 'road.local',
          'elementType': 'all',
          'stylers': [{
            'lightness': '40',
          }],
        }, {
          'featureType': 'transit',
          'elementType': 'all',
          'stylers': [{
            'saturation': -100,
          }, {
            'visibility': 'simplified',
          }],
        }, {
          'featureType': 'water',
          'elementType': 'geometry',
          'stylers': [{
            'hue': '#ffff00',
          }, {
            'lightness': -25,
          }, {
            'saturation': -97,
          }],
        }, {
          'featureType': 'water',
          'elementType': 'labels',
          'stylers': [{
            'lightness': -25,
          }, {
            'saturation': -100,
          }],
        }],
      });
        //markers
        var markers2 = data.map(function (location, i) {

        var pos2 = new google.maps.LatLng(location.lat, location.lng);
        var image2 = {
                url: '<?php echo App\asset_path("images/bus-stop.png"); ?>',
            }

        var marker2 = new google.maps.Marker({
            position: pos2,
            map: map2,
            icon: image2,
        });

        return marker2;
        });
        
        var latlong2 = data.map(function (longlats, i) {
            if (response[1][longlats.title]) {
                var waga2 = response[longlats.title];
                latlong2 = {location: new google.maps.LatLng(longlats.lat, longlats.lng), weight: waga2}
            }
            return latlong2;
        });
        // console.log(latlong.title)
        var heatmap2 = new google.maps.visualization.HeatmapLayer({
            data: latlong2,
            radius: 50,
            dissipating: true,
        });
        heatmap2.setMap(map2);
  },
});
}
</script>

@endsection
