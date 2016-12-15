<?php
/**
 * This file is part of me-cms-instagram.
 *
 * me-cms-instagram is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * me-cms-instagram is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with me-cms-instagram.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */
namespace MeInstagram\Utility;

use Cake\Http\Client;
use Cake\Network\Exception\NotFoundException;

/**
 * An utility to get media from Instagram
 */
class Instagram
{
    /**
     * Gets a media object
     * @param string $id Media ID
     * @return object
     * @see https://www.instagram.com/developer/endpoints/media/#get_media
     * @throws NotFoundException
     */
    public static function media($id)
    {
        $url = sprintf('https://api.instagram.com/v1/media/%s?access_token=%s', $id, config('Instagram.key'));

        $response = (new Client())->get($url);
        $photo = json_decode($response->body(), true);

        if (empty($photo['data']['images']['standard_resolution']['url'])) {
            throw new NotFoundException(__d('me_cms', 'Record not found'));
        }

        return (object)am([
            'filename' => explode('?', basename($photo['data']['images']['standard_resolution']['url']), 2)[0],
            'path' => $photo['data']['images']['standard_resolution']['url'],
        ], compact('id'));
    }

    /**
     * Gets the most recent media published by the owner of token
     * @param string $id Request ID ("Next ID" for Istangram)
     * @param int $limit Limit
     * @return array Array with photos and "Next ID"
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_media_recent_self
     * @throws NotFoundException
     */
    public static function recent($id = null, $limit = 15)
    {
        $url = sprintf('https://api.instagram.com/v1/users/self/media/recent/?count=%s&access_token=%s', $limit, config('Instagram.key'));

        //Adds the request ID ("Next ID" for Istangram) to the url
        if (!empty($id)) {
            $url = sprintf('%s&max_id=%s', $url, $id);
        }

        $response = (new Client())->get($url);
        $photos = json_decode($response->body(), true);

        if (empty($photos['data'])) {
            throw new NotFoundException(__d('me_cms', 'Record not found'));
        }

        $nextId = empty($photos['pagination']['next_max_id']) ? null : $photos['pagination']['next_max_id'];

        $photos = array_map(function ($photo) {
            return (object)[
                'id' => $photo['id'],
                'description' => $photo['caption']['text'],
                'link' => $photo['link'],
                'path' => $photo['images']['standard_resolution']['url'],
            ];
        }, $photos['data']);

        return [$photos, $nextId];
    }

    /**
     * Gets information about the owner of the token.
     * @return object
     * @see https://www.instagram.com/developer/endpoints/users/#get_users_self
     * @throws NotFoundException
     */
    public static function user()
    {
        $url = sprintf('https://api.instagram.com/v1/users/self/?access_token=%s', config('Instagram.key'));

        $response = (new Client())->get($url);
        $user = json_decode($response->body(), true);

        if (empty($user['data'])) {
            throw new NotFoundException(__d('me_cms', 'Record not found'));
        }

        return (object)array_map(function ($v) {
            return is_array($v) ? (object)$v : $v;
        }, $user['data']);
    }
}
