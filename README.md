NSM Short URL - EE2 URL shortner & permalink creator
====================================================

Overview
--------

### Requirements

NSM Short URL is an EE 2.0 plugin.

Technical requirements include:

* [ExpressionEngine 2.0][ee]
* PHP5
* A modern browser: [Firefox][firefox], [Safari][safari], [Google Chrome][chrome] or IE8+

### Installation

1. Download the latest version of NSM Short URL
2. Extract the .zip file to your desktop
3. Copy `system/expressionengine/third_party/nsm_short_url` to `system/expressionengine/third_party/`

Tag reference
------------

### `{exp:nsm_short_url:link}`

    {exp:nsm_short_url:link (entry_id | url_title) [, host, link_content, template_group, redirect_with_url_title]}

Creates a short url link wrapped in `<a>` tags.

#### Tag Parameters

The only required parameters are either `entry_id` _or_ `url_title`. All other parameters affect the link output.

##### `entry_id='2'` [required]

The entries ID. Using the `entry_id` parameter over `url_title` is preferred as it reduces the number of DB calls.

##### `url_title='a-news-article'` [required]

The entries url title. In cases where an `entry_id` cannot be determined it is possible to use the `url_title` parameter. This parameter adds an extra DB call every time the tag is encountered.

##### `host='http://l-g.me`' [optional, default: The current site URL]

If no host parameter is passed the link will be generated using the current sites url. This parameter may be used to create even shorter urls using a different domain. When this parameter is used `template_group` is ignored.

##### `link_content='Permalink'` [optional, default: The entry ID]

Manipulate the link content. The entry ID is outputted by default.

##### `template_group='short'` [optional, default: 's']

Override the default template group. This parameter is ignored if the `host` parameter is used.

#### `redirect_with_url_title='no'` [optional, default: 'yes']

If this attribute is set an additional character ('u') will be prepended to the short url. The `{exp:nsm_short_url:redirect}` tag recognises this and redirects the user to a url which appends the entries `url_title` to the weblogs comment path. Default behavior is to not add the extra character and therefore redirect to an `entry_id` based url.

### `{exp:nsm_short_url:link_url}`

    {exp:nsm_short_url:link_url (entry_id | url_title) [, host, template_group, redirect_with_url_title ]}

Returns the raw URL without the wrapping `<a>` tags. Accepts same tag parameters as `{exp:nsm_short_url:link}` minus `link_content`.

### `{exp:nsm_short_url:meta}`

    {exp:nsm_short_url:meta (entry_id | url_title) [, host,  template_group, redirect_with_url_title ]}

Returns a meta tag for sort URL hinting.  Accepts same tag parameters as `{exp:nsm_short_url:link}` minus `link_content`.

### `{exp:nsm_short_url:redirect}`

Place this tag in your redirect template group. The default template group is `s` however this can be modified using the `template_group` parameter when creating the short url.

Redirects the user to the actual URL using the weblog path preferences. If `redirect_with_url_title` was set when creating the short URL the `url_title` will be appended to the comment path otherwise the `enrtry_id` will be used.

User guide
----------

1. Create a new template group called 's' with a single index.html template.
2. Add `{exp:nsm_short_url:redirect}` to the template.
3. Add `{exp:nsm_short_url:link}` to your templates where you want your short links to be outputted
4. Optional: Add `{exp:nsm_short_url:meta}` to the head of your site to add a short url hint for search engines, social media sites.

### Examples of the `{exp:nsm_short_url:link}` tag

#### Using the `{entry_id}` parameter

    {exp:nsm_short_url:link entry_id="{entry_id}"}
    {exp:nsm_short_url:link entry_id="{entry_id}" link_content="Permalink"}
    {exp:nsm_short_url:link entry_id="{entry_id}" template_group="short"}
    {exp:nsm_short_url:link entry_id="{entry_id}" redirect_with_url_title="TRUE"}
    {exp:nsm_short_url:link entry_id="{entry_id}" host="http://l-g.me/"}
    {exp:nsm_short_url:link entry_id="{entry_id}" host="http://l-g.me/" redirect_with_url_title="TRUE" }

#### Using the `{url_title}` parameter

    {exp:nsm_short_url:link url_title="{url_title}"}
    {exp:nsm_short_url:link url_title="{url_title}" link_content="Permalink"}
    {exp:nsm_short_url:link url_title="{url_title}" template_group="short"}
    {exp:nsm_short_url:link url_title="{url_title}" redirect_with_url_title="TRUE"}
    {exp:nsm_short_url:link url_title="{url_title}" host="http://l-g.me/" }
    {exp:nsm_short_url:link url_title="{url_title}" host="http://l-g.me/" redirect_with_url_title="TRUE"}


Release Notes
-------------

### Upgrading?

* Before upgrading back up your database and site first, you can never be too careful.
* Never upgrade a live site, you're asking for trouble regardless of the addon.
* After an upgrade if you are experiencing errors re-save the extension settings for each site in your ExpressionEngine install.

There are no specific upgrade notes for this version.

### Change log

#### 1.0.0

* Initial release

Support
-------

Technical support is available primarily through the [ExpressionEngine forums][ee_forums]. [Leevi Graham][lg] and [Newism][newism] do not provide direct phone support. No representations or guarantees are made regarding the response time in which support questions are answered.

Although we are actively developing this addon, [Leevi Graham][lg] and [Newism][newism] makes no guarantees that it will be upgraded within any specific timeframe.

License
------

Ownership of this software always remains property of the author.

You may:

* Modify the software for your own projects
* Use the software on personal and commercial projects

You may not:

* Resell or redistribute the software in any form or manner without permission of the author
* Remove the license / copyright / author credits

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

[lg]: http://leevigraham.com

[nsm]: http://newism.com.au
[nsm_publish_plus]: http://leevigraham.com/cms-customisation/expressionengine/nsm-publish-plus/

[ee]: http://expressionengine.com/index.php?affiliate=newism
[ee_forums]: http://expressionengine.com/index.php?affiliate=newism&page=forums
[ee_cp]: http://expressionengine.com/index.php?affiliate=newism&page=docs/cp/index.html
[ee_cp_edit]: http://expressionengine.com/index.php?affiliate=newism&page=docs/cp/edit/index.html
[ee_cp_extensions_manager]: http://expressionengine.com/index.php?affiliate=newism&page=docs/cp/admin/utilities/extension_manager.html
[ee_msm]: http://expressionengine.com/index.php?affiliate=newism&page=downloads/details/multiple_site_manager/

[firefox]: http://firefox.com
[safari]: http://www.apple.com/safari/download/
[chrome]: http://www.google.com/chrome/

[lg_addon_updater]: http://leevigraham.com/cms-customisation/expressionengine/lg-addon-updater/
[gh_morphine_theme]: http://github.com/newism/nsm.morphine.theme
