<?php

namespace hypeJunction\Maps;

/**
 * Handle map pages and markers
 *
 * @param array $page
 * @param string $handler
 * @return boolean
 */
function page_handler($page, $handler) {

	elgg_push_breadcrumb(elgg_echo('maps'), 'maps');

	switch ($page[0]) {

		default :
		case 'search' :

			$maps = get_site_search_maps();

			$ids = array_keys($maps);
			$id = elgg_extract(1, $page, $ids[0]);

			$map = elgg_extract($id, $maps, false);

			if (!$map) {
				return false;
			}

			$map['filter_context'] = 'search';

			$title = elgg_extract('title', $map, elgg_echo('maps:untitled'));
			elgg_push_breadcrumb($title);

			$filter = elgg_view("framework/maps/filters/site", $map);
			if (elgg_view_exists("framework/maps/search/$id/map")) {
				$content = elgg_view("framework/maps/search/$id/map", $map);
			} else {
				$content = elgg_view("framework/maps/search/_default/map", $map);
			}
			if (elgg_view_exists("framework/maps/search/$id/sidebar")) {
				$sidebar = elgg_view("framework/maps/search/$id/sidebar", $map);
			} else {
				$sidebar = elgg_view("framework/maps/search/_default/sidebar", $map);
			}
			break;

		case 'group' :

			$group_guid = elgg_extract(1, $page);
			$group  = get_entity($group_guid);

			if (!elgg_instanceof($group, 'group')) {
				return false;
			}

			$maps = get_group_search_maps($group);

			$ids = array_keys($maps);
			$id = elgg_extract(2, $page, $ids[0]);

			$map = elgg_extract($id, $maps, false);

			if (!$map) {
				return false;
			}

			$title = elgg_extract('title', $map, elgg_echo('maps:untitled'));
			elgg_push_breadcrumb($title);

			$filter = false;
			if (elgg_view_exists("framework/maps/search/$id/map")) {
				$content = elgg_view("framework/maps/search/$id/map", $map);
			} else {
				$content = elgg_view("framework/maps/search/_default/map", $map);
			}
			if (elgg_view_exists("framework/maps/search/$id/sidebar")) {
				$sidebar = elgg_view("framework/maps/search/$id/sidebar", $map);
			} else {
				$sidebar = elgg_view("framework/maps/search/_default/sidebar", $map);
			}
			break;
	}

	if ($content) {
		if (elgg_is_xhr()) {
			echo elgg_view('output/content', array(
				'content' => $content
			));
			exit;
		} else {
			$layout = elgg_view_layout('content', array(
				'title' => $title,
				'content' => $content,
				'filter' => $filter,
				'sidebar' => $sidebar,
			));
			echo elgg_view_page($title, $layout);
		}
		return true;
	}

	return false;
}

/**
 * Handle entity UrLs
 * @param ElggObject $entity
 * @return string
 */
function url_handler($entity) {
	$container = $entity->getContainerEntity();
	if (elgg_instanceof($container, 'user')) {
		return elgg_normalize_url(PAGEHANDLER . '/owner/' . $container->username . '/' . $entity->guid);
	} else {
		return elgg_normalize_url(PAGEHANDLER . '/group/' . $container->guid . '/' . $entity->guid);
	}
}