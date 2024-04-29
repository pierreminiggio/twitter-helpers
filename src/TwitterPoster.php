<?php

namespace PierreMiniggio\TwitterHelpers;

use Abraham\TwitterOAuth\TwitterOAuth;

use Exception;

class TwitterPoster
{
    public const VERSION_1 = '1';
    public const VERSION_2 = '2';

    private TwitterAPI $api;
    private TwitterOAuth $apiV2;

    public function __construct(
        string $oauthAccessToken,
        string $oauthAccessTokenSecret,
        string $consumerKey,
        string $consumerSecret
    )
    {
        $this->api = new TwitterAPI([
            'oauth_access_token' => $oauthAccessToken,
            'oauth_access_token_secret' => $oauthAccessTokenSecret,
            'consumer_key' => $consumerKey,
            'consumer_secret' => $consumerSecret
        ]);

        $this->apiV2 = new TwitterOAuth($consumerKey, $consumerSecret, $oauthAccessToken, $oauthAccessTokenSecret);
        $this->apiV2->setApiVersion('2');
    }

    /**
     * @throws Exception
     */
    public function updateStatus(string $status, string $version = self::VERSION_1): string
    {
        if ($version === self::VERSION_1) {
            return $this->api->setPostfields(['status' => $status])
                ->buildOauth('https://api.twitter.com/1.1/statuses/update.json', 'POST')
                ->performRequest()
            ;
        }

        if ($version === self::VERSION_2) {
            $tweetParams = [
                'text' => $status
            ];
        
            $status = $this->apiV2->post('tweets', $tweetParams);

            if (isset($status->status) && $status->status >= 400) {
                throw new Exception('API' . (isset($status->title) ? (' ' . $status->title) : '') . ' error ' . $status->status . ' : ' . $status->detail);
            }
            
            if (! isset($status->data)) {
                throw new Exception('No data in API Response');
            }

            return json_encode($status->data);
        }
        
        throw new Exception('Bad version');
    }
}
