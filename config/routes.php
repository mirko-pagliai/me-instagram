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
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 * @link        http://git.novatlantis.it Nova Atlantis Ltd
 */
use Cake\Routing\Router;

Router::defaultRouteClass('DashedRoute');
Router::extensions('rss');

/**
 * MeInstagram routes
 */
Router::scope('/', ['plugin' => 'MeInstagram'], function ($routes) {
    //Instagram
    if (!routeNameExists('instagramPhotos')) {
        $routes->connect(
            '/instagram',
            ['controller' => 'Instagram', 'action' => 'index'],
            ['_name' => 'instagramPhotos']
        );
    }
    
    //Instagram (with ID)
    $routes->connect(
        '/instagram/:id',
        ['controller' => 'Instagram', 'action' => 'index'],
        ['id' => '\d+_\d+', 'pass' => ['id']]
    );
    
    //Instragram photo
    if (!routeNameExists('instagramPhoto')) {
        $routes->connect(
            '/instagram/view/:id',
            ['controller' => 'Instagram', 'action' => 'view'],
            ['_name' => 'instagramPhoto', 'id' => '\d+_\d+', 'pass' => ['id']]
        );
    }
});
