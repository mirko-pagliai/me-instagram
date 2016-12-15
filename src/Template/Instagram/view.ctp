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

$this->extend('MeCms./Common/index');
$this->assign('title', $title = $photo->filename);

if (config('default.user_profile')) {
    echo $this->element('user');
}

/**
 * Breadcrumb
 */
$this->Breadcrumbs->add(__d('me_cms_instagram', 'Photos from {0}', 'Instagram'), ['_name' => 'instagramPhotos']);
$this->Breadcrumbs->add($title, ['_name' => 'instagramPhoto', $photo->id]);

echo $this->Html->img($photo->path);
