<?php
declare(strict_types=1);
/**
 * This file is part of me-cms-instagram.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/me-cms-instagram
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
$this->extend('MeCms./common/index');
$this->assign('title', $title = $photo->filename);

if (getConfig('default.user_profile')) {
    echo $this->element('user');
}

/**
 * Breadcrumb
 */
$this->Breadcrumbs->add(__d('me_cms_instagram', 'Photos from {0}', 'Instagram'), ['_name' => 'instagramPhotos']);
$this->Breadcrumbs->add($title, ['_name' => 'instagramPhoto', $photo->id]);
?>

<div class="mb-4">
    <?= $this->Html->img($photo->path) ?>
</div>
