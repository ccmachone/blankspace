<?php
class Geocoder_Google_Maps implements \Geocoder_Adapter_Interface{
    public function getMapUrlAtLatitudeLongitude($latitude, $longitude)
    {
        return "http://maps.google.com/?q=" . $latitude . "," . $longitude;
    }

    public function getAddressAtLatitudeLongitude($latitude, $longitude)
    {
        $ini = getProjectIni();
        if ($ini['global']['environment'] == "production") {
            $uri = "https://maps.googleapis.com/maps/api/geocode/json";
            $client = new \Zend\Http\Client(null, array('sslcapath' => '/etc/ssl/certs'));
            $client->setMethod(\Zend\Http\Request::METHOD_GET);
            $client->setUri($uri);
            $client->setParameterGet(array("latlng" => $latitude . "," . $longitude, "key" => $ini['google_maps_api']['api_key']));
            $result = $client->send();
            $body = $result->getBody();
            $last_request = $client->getLastRawRequest();
            $last_response = $body;
        } else {
            $body = $this->getTestResponse();
            $last_request = "";
            $last_response = $body;
        }

        $body = json_decode($body, true);
        if ($body['status'] == "OK") {
            $address = $body['results'][0]['formatted_address'];
        } else {
            $address = "";
        }
        $geocode_log = new \Geocode_Log();
        $geocode_log->setLatitude($latitude);
        $geocode_log->setLongitude($longitude);
        $geocode_log->setAddress($address);
        $geocode_log->setRequest(str_replace($ini['google_maps_api']['api_key'], "APIKEY", $last_request));
        $geocode_log->setResponse($last_response);
        $geocode_log->setGeocoder(__CLASS__);
        $geocode_log->setSource_ip($_SERVER['REMOTE_ADDR']);
        $geocode_log->setCreated_at(date("c"));
        $geocode_log->save();

        return $address;
    }

    public function getTestResponse()
    {
        $body = <<<EOT
{
   "results" : [
      {
         "address_components" : [
            {
               "long_name" : "8501",
               "short_name" : "8501",
               "types" : [ "street_number" ]
            },
            {
               "long_name" : "North Lake Dasha Drive",
               "short_name" : "N Lake Dasha Dr",
               "types" : [ "route" ]
            },
            {
               "long_name" : "Plantation",
               "short_name" : "Plantation",
               "types" : [ "locality", "political" ]
            },
            {
               "long_name" : "Broward County",
               "short_name" : "Broward County",
               "types" : [ "administrative_area_level_2", "political" ]
            },
            {
               "long_name" : "Florida",
               "short_name" : "FL",
               "types" : [ "administrative_area_level_1", "political" ]
            },
            {
               "long_name" : "United States",
               "short_name" : "US",
               "types" : [ "country", "political" ]
            },
            {
               "long_name" : "33324",
               "short_name" : "33324",
               "types" : [ "postal_code" ]
            },
            {
               "long_name" : "3140",
               "short_name" : "3140",
               "types" : [ "postal_code_suffix" ]
            }
         ],
         "formatted_address" : "8501 N Lake Dasha Dr, Plantation, FL 33324, USA",
         "geometry" : {
            "location" : {
               "lat" : 26.114186,
               "lng" : -80.262182
            },
            "location_type" : "ROOFTOP",
            "viewport" : {
               "northeast" : {
                  "lat" : 26.1155349802915,
                  "lng" : -80.26083301970849
               },
               "southwest" : {
                  "lat" : 26.1128370197085,
                  "lng" : -80.26353098029151
               }
            }
         },
         "place_id" : "ChIJezU1ZsIH2YgRWR8c7KIVNeA",
         "types" : [ "street_address" ]
      },
      {
         "address_components" : [
            {
               "long_name" : "Plantation",
               "short_name" : "Plantation",
               "types" : [ "locality", "political" ]
            },
            {
               "long_name" : "Broward County",
               "short_name" : "Broward County",
               "types" : [ "administrative_area_level_2", "political" ]
            },
            {
               "long_name" : "Florida",
               "short_name" : "FL",
               "types" : [ "administrative_area_level_1", "political" ]
            },
            {
               "long_name" : "United States",
               "short_name" : "US",
               "types" : [ "country", "political" ]
            }
         ],
         "formatted_address" : "Plantation, FL, USA",
         "geometry" : {
            "bounds" : {
               "northeast" : {
                  "lat" : 26.160857,
                  "lng" : -80.197469
               },
               "southwest" : {
                  "lat" : 26.0924999,
                  "lng" : -80.33031389999999
               }
            },
            "location" : {
               "lat" : 26.1275862,
               "lng" : -80.23310359999999
            },
            "location_type" : "APPROXIMATE",
            "viewport" : {
               "northeast" : {
                  "lat" : 26.160857,
                  "lng" : -80.197469
               },
               "southwest" : {
                  "lat" : 26.0924999,
                  "lng" : -80.33031389999999
               }
            }
         },
         "place_id" : "ChIJwxO77w8H2YgRFnnNjY2Cd10",
         "types" : [ "locality", "political" ]
      },
      {
         "address_components" : [
            {
               "long_name" : "33324",
               "short_name" : "33324",
               "types" : [ "postal_code" ]
            },
            {
               "long_name" : "Fort Lauderdale",
               "short_name" : "Fort Lauderdale",
               "types" : [ "locality", "political" ]
            },
            {
               "long_name" : "Broward County",
               "short_name" : "Broward County",
               "types" : [ "administrative_area_level_2", "political" ]
            },
            {
               "long_name" : "Florida",
               "short_name" : "FL",
               "types" : [ "administrative_area_level_1", "political" ]
            },
            {
               "long_name" : "United States",
               "short_name" : "US",
               "types" : [ "country", "political" ]
            }
         ],
         "formatted_address" : "Fort Lauderdale, FL 33324, USA",
         "geometry" : {
            "bounds" : {
               "northeast" : {
                  "lat" : 26.135731,
                  "lng" : -80.24194
               },
               "southwest" : {
                  "lat" : 26.001485,
                  "lng" : -80.298017
               }
            },
            "location" : {
               "lat" : 26.1076663,
               "lng" : -80.27115879999999
            },
            "location_type" : "APPROXIMATE",
            "viewport" : {
               "northeast" : {
                  "lat" : 26.135731,
                  "lng" : -80.24780799999999
               },
               "southwest" : {
                  "lat" : 26.0786479,
                  "lng" : -80.298017
               }
            }
         },
         "place_id" : "ChIJv9s2_eAH2YgRqtHtwTXjYY4",
         "postcode_localities" : [ "Davie", "Fort Lauderdale", "Plantation" ],
         "types" : [ "postal_code" ]
      },
      {
         "address_components" : [
            {
               "long_name" : "Broward County",
               "short_name" : "Broward County",
               "types" : [ "administrative_area_level_2", "political" ]
            },
            {
               "long_name" : "Florida",
               "short_name" : "FL",
               "types" : [ "administrative_area_level_1", "political" ]
            },
            {
               "long_name" : "United States",
               "short_name" : "US",
               "types" : [ "country", "political" ]
            }
         ],
         "formatted_address" : "Broward County, FL, USA",
         "geometry" : {
            "bounds" : {
               "northeast" : {
                  "lat" : 26.3346979,
                  "lng" : -80.07472949999999
               },
               "southwest" : {
                  "lat" : 25.9567499,
                  "lng" : -80.88123299999999
               }
            },
            "location" : {
               "lat" : 26.190096,
               "lng" : -80.365865
            },
            "location_type" : "APPROXIMATE",
            "viewport" : {
               "northeast" : {
                  "lat" : 26.3346979,
                  "lng" : -80.07472949999999
               },
               "southwest" : {
                  "lat" : 25.9567499,
                  "lng" : -80.88123299999999
               }
            }
         },
         "place_id" : "ChIJFUaCG35_2YgRFNYLv44fMaA",
         "types" : [ "administrative_area_level_2", "political" ]
      },
      {
         "address_components" : [
            {
               "long_name" : "Florida",
               "short_name" : "FL",
               "types" : [ "administrative_area_level_1", "political" ]
            },
            {
               "long_name" : "United States",
               "short_name" : "US",
               "types" : [ "country", "political" ]
            }
         ],
         "formatted_address" : "Florida, USA",
         "geometry" : {
            "bounds" : {
               "northeast" : {
                  "lat" : 31.000968,
                  "lng" : -80.0311371
               },
               "southwest" : {
                  "lat" : 24.5210795,
                  "lng" : -87.634896
               }
            },
            "location" : {
               "lat" : 27.6648274,
               "lng" : -81.5157535
            },
            "location_type" : "APPROXIMATE",
            "viewport" : {
               "northeast" : {
                  "lat" : 31.000968,
                  "lng" : -80.03134299999999
               },
               "southwest" : {
                  "lat" : 24.5236278,
                  "lng" : -87.634896
               }
            }
         },
         "place_id" : "ChIJvypWkWV2wYgR0E7HW9MTLvc",
         "types" : [ "administrative_area_level_1", "political" ]
      },
      {
         "address_components" : [
            {
               "long_name" : "United States",
               "short_name" : "US",
               "types" : [ "country", "political" ]
            }
         ],
         "formatted_address" : "United States",
         "geometry" : {
            "bounds" : {
               "northeast" : {
                  "lat" : 71.3867745,
                  "lng" : -66.9502861
               },
               "southwest" : {
                  "lat" : 18.9106768,
                  "lng" : 172.4458955
               }
            },
            "location" : {
               "lat" : 37.09024,
               "lng" : -95.712891
            },
            "location_type" : "APPROXIMATE",
            "viewport" : {
               "northeast" : {
                  "lat" : 49.38,
                  "lng" : -66.94
               },
               "southwest" : {
                  "lat" : 25.82,
                  "lng" : -124.39
               }
            }
         },
         "place_id" : "ChIJCzYy5IS16lQRQrfeQ5K5Oxw",
         "types" : [ "country", "political" ]
      }
   ],
   "status" : "OK"
}
EOT;
    return $body;
    }


}