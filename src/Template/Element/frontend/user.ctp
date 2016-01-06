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
?>

<?= $this->Html->css('MeInstagram.instagram', ['block' => 'css_bottom']) ?>

<?php if(!empty($user)): ?>
	<div id="instagram-user" class="row">
		<div class="col-sm-5">
			<?php echo $this->Thumb->img($user->profile_picture, ['id' => 'picture']); ?>
		</div>
		<div class="col-sm-7">
			<p id="username"><?= $user->username ?></p>
			<p id="fullname"><?= $user->full_name ?></p>
			<p id="counts">
				<span><?= __d('me_instagram', '{0} posts', $this->Html->strong($user->counts->media)) ?></span>
				<span><?= __d('me_instagram', '{0} followers', $this->Html->strong($user->counts->followed_by)) ?></span>
				<span><?= __d('me_instagram', '{0} following', $this->Html->strong($user->counts->follows)) ?></span>
			</p>
			<?= $this->Html->button(__d('me_instagram', 'Follow me on {0}', 'Instagram'), sprintf('//instagram.com/%s', $user->username), ['class' => 'btn-lg btn-success', 'icon' => 'instagram']) ?>
		</div>
	</div>
<?php endif; ?>