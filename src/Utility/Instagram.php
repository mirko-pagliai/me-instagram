<?php
/**
 * This file is part of MeInstagram.
 *
 * MeInstagram is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * MeInstagram is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with MeInstagram.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author		Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright	Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license		http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link		http://git.novatlantis.it Nova Atlantis Ltd
 */
namespace MeInstagram\Utility;

use MeTools\Cache\Cache;
use Cake\Network\Exception\NotFoundException;
use MeTools\Utility\Xml;

/**
 * An utility to get media from Instagram.
 * 
 * You can use this utility by adding:
 * <code>
 * use MeInstagram\Utility\Instagram;
 * </code>
 */
class Instagram {
	/**
	 * Gets latest photos from the recent media from Instagram
	 * @param int $limit Limit
	 * @return array Latest photos
	 * @uses getRecentUser()
	 */
	public static function getLatest($limit = 1) {
		//Tries to get data from the cache
		$photo = Cache::read($cache = sprintf('latest_%s', $limit), 'instagram');
		
		//If the data are not available from the cache
		if(empty($photo)) {
			//Gets the recent media from Instagram
			$photos = self::getRecentUser(NULL, 15)['data'];

			if(count($photos) > $limit)
				$photos = array_slice($photos, 0, $limit);
		}
		
		return $photos;
	}
	
	/**
	 * Gets a media from Instagram
	 * @param string $id Media ID
	 * @return object
	 * @uses MeTools\Utility\Xml::fromFile()
	 */
	public static function getMedia($id) {
		//Tries to get data from the cache
		$photo = Cache::read($cache = sprintf('media_%s', md5($id)), 'instagram');
		
		//If the data are not available from the cache
		if(empty($photo)) {
			//See https://www.instagram.com/developer/endpoints/media/#get_media
			$url = sprintf('https://api.instagram.com/v1/media/%s?access_token=%s', $id, config('Instagram.key'));
			$photo = @Xml::fromFile($url);
			
			if(empty($photo['data']))
				throw new NotFoundException(__d('me_cms', 'Record not found'));
			
			$photo = (object) ['path' => $photo['data']['images']['standard_resolution']['url']];
			
			Cache::write($cache, $photo, 'instagram');
		}
		
		return $photo;
	}
	
	/**
	 * Gets random photos from the recent media from Instagram
	 * @param int $limit Limit
	 * @return array Random photos
	 * @uses getRecentUser()
	 */
	public static function getRandom($limit = 1) {
		//Gets the recent media from Instagram
		$photos = self::getRecentUser(NULL, 15)['data'];
		
		//Shuffles
		shuffle($photos);
		
		//Returns random photos
		return array_slice($photos, 0, $limit);
	}

	/**
	 * Gets the recent media from Instagram
	 * @param string $id Request ID ("Next ID" for Istangram)
	 * @param int $limit Limit
	 * @return array
	 * @uses MeTools\Utility\Xml::fromFile()
	 */
	public static function getRecentUser($id = NULL, $limit = 15) {
		//Sets initial cache name
		$cache = sprintf('index_limit_%s', $limit);
		
		//Adds the request ID ("Next ID" for Istangram) to the cache name
		if(!empty($id))
			$cache = sprintf('%s_id_%s', $cache, $id);
		
		//Tries to get data from the cache
		$photos = Cache::read($cache, 'instagram');
		
		//If the data are not available from the cache
		if(empty($photos)) {
			//See https://www.instagram.com/developer/endpoints/users/#get_users_media_recent_self
			$url = sprintf('https://api.instagram.com/v1/users/self/media/recent/?count=%s&access_token=%s', $limit, config('Instagram.key'));
			
			//Adds the request ID ("Next ID" for Istangram) to the url
			if(!empty($id))
				$url = sprintf('%s&max_id=%s', $url, $id);

			//Gets photos
			$photos = @Xml::fromFile($url);
			
			if(empty($photos['data']))
				throw new NotFoundException(__d('me_cms', 'Record not found'));

			$photos['data'] = array_map(function($photo) {
				return (object) [
					'id'			=> $photo['id'],
					'description'	=> $photo['caption']['text'],
					'link'			=> $photo['link'],
					'path'			=> $photo['images']['standard_resolution']['url']
				];
			}, $photos['data']);
			
			Cache::write($cache, $photos, 'instagram');
		}
		
		return $photos;
	}
	
	/**
	 * Gets the user's profile
	 * @return object
	 * @uses MeTools\Utility\Xml::fromFile()
	 */
	public static function getUserProfile() {
		//Tries to get data from the cache
		$user = Cache::read($cache = 'user_profile', 'instagram');
		
		//If the data are not available from the cache
		if(empty($user)) {
			//See https://www.instagram.com/developer/endpoints/users/#get_users_self
			$url = sprintf('https://api.instagram.com/v1/users/self/?access_token=%s', config('Instagram.key'));
			$user = @Xml::fromFile($url);
			
			if(empty($user['data']))
				throw new NotFoundException(__d('me_cms', 'Record not found'));

			$user = (object) array_map(function($v) {
				return is_array($v) ? (object) $v : $v;
			}, $user['data']);
			
			Cache::write($cache, $user, 'instagram');
		}
		
		return $user;
	}
}