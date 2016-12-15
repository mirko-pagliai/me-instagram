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

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Cake\Utility\Hash;

//Sets the default MeInstagram name
if (!defined('MEINSTAGRAM')) {
    define('MEINSTAGRAM', 'MeInstagram');
}

/**
 * Loads the MeInstagram configuration
 */
Configure::load(sprintf('%s.me_instagram', MEINSTAGRAM));

//Merges with the configuration from application, if exists
if (is_readable(CONFIG . 'me_instagram.php')) {
    Configure::load('me_instagram');
}

//Merges with the MeCms configuration
Configure::write(
    MECMS,
    Hash::merge(config(MECMS), Configure::consume(MEINSTAGRAM))
);

if (!config('Instagram.key') || config('Instagram.key') === 'your-key-here') {
    throw new InternalErrorException('Instagram API access token is missing');
}

/**
 * Loads the cache configuration
 */
Configure::load(sprintf('%s.cache', MEINSTAGRAM));

//Merges with the configuration from application, if exists
if (is_readable(CONFIG . 'cache.php')) {
    Configure::load('cache');
}

//Adds all cache configurations
foreach (Configure::consume('Cache') as $key => $config) {
    //Drops cache configurations that already exist
    if (Cache::config($key)) {
        Cache::drop($key);
    }

    Cache::config($key, $config);
}
