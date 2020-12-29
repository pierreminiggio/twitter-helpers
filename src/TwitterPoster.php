<?php

namespace PierreMiniggio\TwitterHelpers;

use Exception;

class TwitterPoster
{

    /** @var TwitterAPI */
    private $api;

    /**
     * @param string $oauthAccessToken
     * @param string $oauthAccessToken_secret
     * @param string $consumerKey
     * @param string $consumerSecret
     */
    public function __construct(
        $oauthAccessToken,
        $oauthAccessToken_secret,
        $consumerKey,
        $consumerSecret
    )
    {
        $this->api = new TwitterAPI([
            'oauth_access_token' => $oauthAccessToken,
            'oauth_access_token_secret' => $oauthAccessToken_secret,
            'consumer_key' => $consumerKey,
            'consumer_secret' => $consumerSecret
        ]);
    }

    /**
     * @param string $status
     * 
     * @return string
     * 
     * @throws Exception
     */
    function updateStatus($status)
    {
        return $this->api->setPostfields(['status' => $status])
            ->buildOauth('https://api.twitter.com/1.1/statuses/update.json', 'POST')
            ->performRequest()
        ;
    }
}
