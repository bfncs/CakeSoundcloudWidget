# CakePHP 2.x Soundcloud-Widget Helper

Embed Soundcloud-Players quick and easy

## Requirements

* CakePHP 2.x

## Installation

### Git Clone


In your app directory:

    git clone git@github.com:boundaryfunctions/CakeSoundcloudWidget.git Plugin/Soundcloud

### Git Submodule

In your app directory:

    git submodule add git@github.com:boundaryfunctions/CakeSoundcloudWidget.git Plugin/Soundcloud
    git update --init

### Without Git:

Download the [zipball](https://github.com/boundaryfunctions/CakeSoundcloudWidget/zipball/master "Download the repository zipped") and extract it's contents to `app/Plugin/Soundcloud`.

## Usage


### Controller

Include the helper in your Controller:

    <?php
    class MyController extends AppController {
      public $helpers = array('Soundcloud.SoundcloudWidget');
    }

### View

After including the helper in your controller, you can use it like this in your views:

    $this->Soundcloud->widget($url);

Where `$url` is a valid Soundcloud URL (i.e. `http://soundcloud.com/forss/flickermood`) of a song or a set.

Additionaly, you can add custom options for the current Player by passing an array iFrame options (as you would pass it as the third argument `$options` to the (`HtmlHelper::tag()`)[http://api20.cakephp.org/class/html-helper#method-HtmlHelpertag] function) as second argument and an array of [parameters for the player](http://developers.soundcloud.com/docs/html5-widget#widget-params) as third argument:

    $this->Soundcloud->widget($url, array('width' => 640), array('show_artwork' => 'false'));

## License

Copyright (c) 2012 Marc Löhe

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
