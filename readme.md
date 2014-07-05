hypeMaps
========

Google Maps integration for Elgg


## Getting Started

* You will require a valid Google API key. You can obtain one at
https://console.developers.google.com.
Configure your project to include the following APIs:
* Static Maps API
* Google Maps Javascript API
* Google Maps Geolocation API
* Geocoding API

* Geocoding features are not included with this plugin. You will need to install
another plugin fit for that purpose. You can use my hypeGeo plugin found here:
https://github.com/hypeJunction/hypeGeo

* Places features have been decoupled into a separate plugin, so grab a copy of that
if you are upgrading from previous versions:
https://github.com/hypeJunction/hypePlaces

* Make sure you restrict the use of your API Key to domains you administer, otherwise
someone else can copy them from public facing JS/static map URLs and reuse them


## Developer Notes

* Similar to how the gallery view is toggled, map view can be toggled by adding
```?list_type=mapbox``` to any page that has a list generated using core API

* To build and show a new dynamic map, use something along these lines:

```
// Display friends on the map
$params = array(
	'options' => array(
		'id' => 'friends', // List id / must be unique on the page
		'types' => 'user',
		'relationship' => 'friend',
		'relationship_guid' => elgg_get_logged_in_user_guid()
	),
	'getter' => 'elgg_get_entities_from_relationship',
);
echo \hypeJunction\Maps\ElggMap::showMap($params);
```
This example will render a map of user's friends. The generated map will be
filterable, as appose to the one you would have generated using
```elgg_list_entities_from_relationship()``` passing ```'list_type' => 'mapbox'```

Note that maps generated in this fashion will only include entities taht have
a geocoded location. If you need a list of all entities with an additional map
overview, use ```elgg_list_entities_*``` and pass a mapbox list type.


* To modify a list of sitewide or group maps, use ```'search:site','maps'``` and
```'search:group','maps'``` hooks. See the code for some examples.
You will need to add your custom views.


