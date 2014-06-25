<?php if(array_key_exists('key', $_GET) && 'mariocamp' === $_GET['key']) {

    $strProjectPath = $_SERVER['DOCUMENT_ROOT'] . '/yoshi-app';

    require_once($strProjectPath . '/classes/Yoshi.class.php');
    $objYoshiClass = new Yoshi($strProjectPath);
    $arrPostion = $objYoshiClass->getLastKnownLocation();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <style type="text/css">
            html { height: 100%;
                position: relative; }
            body {
                height: 100%;
                margin: 0;
                padding: 0;
                text-align: center;
                position: relative;
            }
            #mapCurrentLocation {
                width:100%;
                height:100%;
            }
        </style>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwxgtjlvP-R3pb7Hy7W1t7epTBysDH-IA" >
        </script>
        <script type="text/javascript">

            var map;
            var myLatitude = 55.615973;
            var myLongitude = 12.078144;

            function initialize() {

                var mapOptions = {
                    center: new google.maps.LatLng(myLatitude, myLongitude),
                    zoom: 20,
                    mapTypeId: google.maps.MapTypeId.SATELLITE
                };
                map = new google.maps.Map(document.getElementById("mapCurrentLocation"), mapOptions);

                var myLatlng = new google.maps.LatLng(myLatitude,myLongitude);
                var myPostitionMarker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    title: 'My Location'
                });

                <?php if(!empty($arrPostion)) { ?>


                    var pinColor = "66FF66";
                    var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
                        new google.maps.Size(21, 34),
                        new google.maps.Point(0,0),
                        new google.maps.Point(10, 34));
                    var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
                        new google.maps.Size(40, 37),
                        new google.maps.Point(0, 0),
                        new google.maps.Point(12, 35));

                    var yoshiLatlng = new google.maps.LatLng(<? echo $arrPostion['latitude'] . ", " . $arrPostion['longitude']; ?>);
                    var yoshiPostitionMarker = new google.maps.Marker({
                        position: yoshiLatlng,
                        map: map,
                        title: 'YoshiBox',
                        icon: pinImage,
                        shadow: pinShadow
                    });

                    var accuracyOptions = {
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.6,
                        strokeWeight: 2,
                        fillColor: '#FF0000',
                        fillOpacity: 0.35,
                        map: map,
                        center: yoshiLatlng,
                        radius: <? echo $arrPostion['accuracy']; ?>
                    };
                    // Add the circle for this city to the map.
                    cityCircle = new google.maps.Circle(accuracyOptions);

                <? } ?>

            }

            function startTracker() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        myLongitude = position.coords.longitude;
                        myLatitude = position.coords.latitude;
                        initialize();
                    });
                }
                else {
                    initialize();
                }
            }
            google.maps.event.addDomListener(window, 'load', startTracker);
        </script>
    </head>
    <body>
        <?php if(!empty($arrPostion)) { ?>
            <h2 style="font-size:20px; color:#66FF66; margin-top: 5px; margin-bottom:10px;"><? echo $arrPostion['time']; ?></h2>
        <? } ?>
        <div id="mapCurrentLocation"></div>
    </body>
    </html>

<?php } else { ?>
    <h2>No Access!</h2>
<?php } ?>