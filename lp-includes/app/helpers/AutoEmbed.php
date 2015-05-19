<?php

/**
 * Class to parse a string for URLs on their own line and use oEmbed to embed remote content based on the URL.
 * Ripped from WordPress 3.5 for use in other PHP projects.
 *
 * Usage:
 * $autoembed = new AutoEmbed();
 * $content = $autoembed->parse($content);
 * echo $content;
 *
 * @link http://oembed.com/ oEmbed Homepage
 *
 * @package AutoEmbed
 */
class AutoEmbed
{
    // List out some popular sites that support oEmbed.
    var $providers = array(
        '#http://(www\.)?youtube\.com/watch.*#i' => array('http://www.youtube.com/oembed', true),
        '#https://(www\.)?youtube\.com/watch.*#i' => array('http://www.youtube.com/oembed?scheme=https', true),
        '#http://(www\.)?youtube\.com/playlist.*#i' => array('http://www.youtube.com/oembed', true),
        '#https://(www\.)?youtube\.com/playlist.*#i' => array('http://www.youtube.com/oembed?scheme=https', true),
        '#http://youtu\.be/.*#i' => array('http://www.youtube.com/oembed', true),
        '#https://youtu\.be/.*#i' => array('http://www.youtube.com/oembed?scheme=https', true),
        'http://blip.tv/*' => array('http://blip.tv/oembed/', false),
        '#https?://(.+\.)?vimeo\.com/.*#i' => array('http://vimeo.com/api/oembed.{format}', true),
        '#https?://(www\.)?dailymotion\.com/.*#i' => array('http://www.dailymotion.com/services/oembed', true),
        'http://dai.ly/*' => array('http://www.dailymotion.com/services/oembed', false),
        '#https?://(www\.)?flickr\.com/.*#i' => array('https://www.flickr.com/services/oembed/', true),
        '#https?://flic\.kr/.*#i' => array('https://www.flickr.com/services/oembed/', true),
        '#https?://(.+\.)?smugmug\.com/.*#i' => array('http://api.smugmug.com/services/oembed/', true),
        '#https?://(www\.)?hulu\.com/watch/.*#i' => array('http://www.hulu.com/api/oembed.{format}', true),
        'http://i*.photobucket.com/albums/*' => array('http://photobucket.com/oembed', false),
        'http://gi*.photobucket.com/groups/*' => array('http://photobucket.com/oembed', false),
        '#https?://(www\.)?scribd\.com/doc/.*#i' => array('http://www.scribd.com/services/oembed', true),
        '#https?://wordpress.tv/.*#i' => array('http://wordpress.tv/oembed/', true),
        '#https?://(.+\.)?polldaddy\.com/.*#i' => array('https://polldaddy.com/oembed/', true),
        '#https?://poll\.fm/.*#i' => array('https://polldaddy.com/oembed/', true),
        '#https?://(www\.)?funnyordie\.com/videos/.*#i' => array('http://www.funnyordie.com/oembed', true),
        '#https?://(www\.)?twitter\.com/.+?/status(es)?/.*#i' => array('https://api.twitter.com/1/statuses/oembed.{format}', true),
        '#https?://vine.co/v/.*#i' => array('https://vine.co/oembed.{format}', true),
        '#https?://(www\.)?soundcloud\.com/.*#i' => array('http://soundcloud.com/oembed', true),
        '#https?://(.+?\.)?slideshare\.net/.*#i' => array('https://www.slideshare.net/api/oembed/2', true),
        '#https?://instagr(\.am|am\.com)/p/.*#i' => array('https://api.instagram.com/oembed', true),
        '#https?://(www\.)?rdio\.com/.*#i' => array('http://www.rdio.com/api/oembed/', true),
        '#https?://rd\.io/x/.*#i' => array('http://www.rdio.com/api/oembed/', true),
        '#https?://(open|play)\.spotify\.com/.*#i' => array('https://embed.spotify.com/oembed/', true),
        '#https?://(.+\.)?imgur\.com/.*#i' => array('http://api.imgur.com/oembed', true),
        '#https?://(www\.)?meetu(\.ps|p\.com)/.*#i' => array('http://api.meetup.com/oembed', true),
        '#https?://(www\.)?issuu\.com/.+/docs/.+#i' => array('http://issuu.com/oembed_wp', true),
        '#https?://(www\.)?collegehumor\.com/video/.*#i' => array('http://www.collegehumor.com/oembed.{format}', true),
        '#https?://(www\.)?mixcloud\.com/.*#i' => array('http://www.mixcloud.com/oembed', true),
        '#https?://(www\.|embed\.)?ted\.com/talks/.*#i' => array('http://www.ted.com/talks/oembed.{format}', true),
        '#https?://(www\.)?(animoto|video214)\.com/play/.*#i' => array('http://animoto.com/oembeds/create', true),
        '#https?://(.+)\.tumblr\.com/post/.*#i' => array('https://www.tumblr.com/oembed/1.0', true),
        '#https?://(www\.)?kickstarter\.com/projects/.*#i' => array('https://www.kickstarter.com/services/oembed', true),
        '#https?://kck\.st/.*#i' => array('https://www.kickstarter.com/services/oembed', true),
    );

    /**
     * Passes on any unlinked URLs that are on their own line for potential embedding.
     * @param string $content The content to be searched.
     * @return string Potentially modified $content.
     */
    function parse($content)
    {
        $reg_exUrl = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        return preg_replace_callback($reg_exUrl, array($this, 'autoembed_callback'), $content);
    }

    /**
     * Callback function for {@link AutoEmbed::parse()}.
     * @param array $match A regex match array.
     * @return string The embed HTML on success, otherwise the original URL.
     */
    function autoembed_callback($match)
    {
        $attr['discover'] = true;
        $return = $this->get_html($match[0], $attr);
        return "\n$return\n";
    }


    /**
     * The do-it-all function that takes a URL and attempts to return the HTML.
     * @param string $url The URL to the content that should be attempted to be embedded.
     * @param array $args Optional arguments.
     * @return bool|string False on failure, otherwise the UNSANITIZED (and potentially unsafe) HTML that should be used to embed.
     */
    function get_html($url, $args = '')
    {
        $provider = false;

        if (!isset($args['discover']))
            $args['discover'] = true;

        foreach ($this->providers as $matchmask => $data) {
            list($providerurl, $regex) = $data;

            // Turn the asterisk-type provider URLs into regex
            if (!$regex) {
                $matchmask = '#' . str_replace('___wildcard___', '(.+)', preg_quote(str_replace('*', '___wildcard___', $matchmask), '#')) . '#i';
                $matchmask = preg_replace('|^#http\\\://|', '#https?\://', $matchmask);
            }

            if (preg_match($matchmask, $url)) {
                $provider = str_replace('{format}', 'json', $providerurl); // JSON is easier to deal with than XML
                break;
            }
        }
        if (!$provider) {
            return $url;
        }

        $code = get_oembed_object($url)->code;

        if(trim($code)){
            return $code;
        }
        return $url;
    }

}