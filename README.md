# MeInstagram, plugin for MeCms plugin

[![Build Status](https://travis-ci.org/mirko-pagliai/me-instagram.svg?branch=master)](https://travis-ci.org/mirko-pagliai/me-instagram)

This plugin allows you to manage Instagram photos on the 
[//github.com/mirko-pagliai/cakephp-for-mecms](MeCms platform).

To install:

    $ composer require --prefer-dist mirko-pagliai/me-instagram
    $ bin/cake me_instagram.install all -v

Then you need to get an 
[API access token for Instagram](//www.instagram.com/developer/clients/manage) 
and edit `APP/config/instagram_keys.php`.

For widgets provided by this plugin, see 
[here](//github.com/mirko-pagliai/me-instagram/wiki/Widgets).

## Versioning
For transparency and insight into our release cycle and to maintain backward 
compatibility, MeInstagram will be maintained under the 
[Semantic Versioning guidelines](http://semver.org).