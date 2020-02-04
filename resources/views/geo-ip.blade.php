                    <?php
                        use PulkitJalan\GeoIP\GeoIP;
                        $geoip = new GeoIP();
                        $lat = $geoip->getLatitude(); // 51.5141
                        $lon = $geoip->getLongitude(); // -3.1969

                        dd($geoip);
                    ?>