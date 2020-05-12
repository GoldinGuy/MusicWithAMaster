<?php

require_once plugin_dir_path(dirname(__FILE__)) . 'includes/rumbletalk-sdk.php';

use RumbleTalk\RumbleTalkSDK;

class RumbleTalk_AJAX
{
    /**
     * @const string - invalid JWT message on API call
     */
    const INVALID_JWT = 'invalid JWT';

    /**
     * @var string - RumbleTalk API token key
     */
    private $tokenKey;

    /**
     * @var string - RumbleTalk API token secret
     */
    private $tokenSecret;

    /**
     * @var RumbleTalkSDK - the API connection instance
     */
    private $rumbletalk;

    /**
     * RumbleTalkAJAX constructor.
     * @param $tokenKey
     * @param $tokenSecret
     */
    public function __construct($tokenKey, $tokenSecret)
    {
        $this->rumbletalk = new RumbleTalkSDK($tokenKey, $tokenSecret);

        $this->setToken($tokenKey, $tokenSecret);

        $this->updateAccessToken();
    }

    public function handleRequest()
    {
        $data = stripslashes_deep($_POST['data']['data']);
        switch ($_POST['data']['request']) {
            case 'GET_TOKEN':
                $this->getToken();
                break;

            case 'UPDATE_TOKEN':
                $this->updateToken($data);
                break;

            case 'CREATE_ACCOUNT':
                $this->createAccount($data);
                break;

            case 'RELOAD_CHATS':
                $this->reloadChats();
                break;

            case 'CREATE_CHAT':
                $this->createChat($data);
                break;

            case 'UPDATE_CHAT':
                $this->updateChat($data);
                break;

            case 'DELETE_CHAT':
                $this->deleteChat($data);
                break;

            default:
                $this->response(array(
                    'status' => false,
                    'message' => 'invalid request type'
                ));
        }
    }

    public function updateAccessToken()
    {
        $accessToken = get_option('rumbletalk_accesstoken');

        # if there's a saved and valid access token, set it
        if ($accessToken && !RumbleTalkSDK::renewalNeeded(RumbleTalkSDK::getTokenExpiration($accessToken))) {
            $this->rumbletalk->setAccessToken($accessToken);

        } else {
            # if the token is set, try fetching an access token
            if ($this->tokenKey && $this->tokenSecret) {
                try {
                    $accessToken = $this->rumbletalk->fetchAccessToken();
                } catch (Exception $e) {
                    $accessToken = '';
                }

                # no other option, clear the token
            } else {
                $accessToken = '';
            }

            update_option('rumbletalk_accesstoken', $accessToken);
        }

        return $accessToken;
    }

    /**
     * sets the token key and secret for the instance
     * @param string $tokenKey
     * @param string $tokenSecret
     * @param bool $updateWP
     * @return bool true if the token was set and not cleared
     */
    public function setToken($tokenKey, $tokenSecret, $updateWP = false)
    {
        $this->tokenKey = $tokenKey;
        $this->tokenSecret = $tokenSecret;
        $this->rumbletalk->setToken($tokenKey, $tokenSecret);

        if ($updateWP) {
            update_option('rumbletalk_chat_token_key', $this->tokenKey);
            update_option('rumbletalk_chat_token_secret', $this->tokenSecret);
        }

        return $this->tokenKey && $this->tokenSecret;
    }

    private function response($data = array())
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($data['status'])) {
            $data['status'] = true;
        }

        echo json_encode($data);
        exit;
    }

    public function getToken($return = false)
    {
        $response = array(
            'key' => $this->tokenKey,
            'secret' => $this->tokenSecret
        );

        if (!$return) {
            $this->response($response);
        }

        return $response;
    }

    private function updateToken($data)
    {
        $set = $this->setToken($data['key'], $data['secret'], true);

        update_option('rumbletalk_accesstoken', '');

        RumbleTalk_Admin::removeChats();

        if ($set) {
            $this->updateAccessToken();

            $this->reloadChats(true);
        }

        $this->response();
    }

    private function createAccount($data)
    {
        $data['referrer'] = 'WordPress';

        $result = $this->rumbletalk->createAccount($data);

        if ($result['status']) {
            $this->setToken(
                $result['token']['key'],
                $result['token']['secret'],
                true
            );

            $chats = array(
                $result['hash'] => array(
                    'id' => $result['chatId'],
                    'name' => 'New Chat',
                    'width' => '',
                    'height' => '',
                    'membersOnly' => false,
                    'floating' => false
                )
            );

            RumbleTalk_Admin::updateChats($chats);

            $result['accessToken'] = $this->updateAccessToken();
        }

        $this->response(
            array(
                'status' => $result['status'],
                'message' => @$result['message']
            )
        );
    }

    private function createChat($data)
    {
        if ($data['membersOnly'] == 'true') {
            $data['forceSDKLogin'] = true;
            $data['allowListeners'] = false;
            $data['autoInvite'] = false;
            $data['inviteFriends'] = false;
        }
        $result = $this->rumbletalk->post('chats', $data);

        if ($result['status']) {
            $chats = RumbleTalk_Admin::getChats();
            $chats[$result['hash']] = array(
                'id' => $result['chatId'],
                'name' => $data['name'],
                'width' => $data['width'],
                'height' => $data['height'],
                'membersOnly' => $data['membersOnly'] == 'true',
                'loginName' => $data['loginName'],
                'floating' => $data['floating'] == 'true'
            );

            RumbleTalk_Admin::updateChats($chats);
        }

        $this->response($result);
    }

    private function updateChat($data)
    {
        $chats = RumbleTalk_Admin::getChats();
        $hash = $data['hash'];
        unset($data['hash']);

        if (!$chats[$hash]['id']) {
            $chats = $this->reloadChats(true);
        }

        # save as boolean
        $data['membersOnly'] = $data['membersOnly'] == 'true';
        $data['floating'] = $data['floating'] == 'true';

        $membersChanged = $data['membersOnly'] != $chats[$hash]['membersOnly'];
        $chats[$hash] = array_merge($chats[$hash], $data);

        RumbleTalk_Admin::updateChats($chats);

        $postData = array(
            'name' => $chats[$hash]['name'],
            'forceSDKLogin' => !!$chats[$hash]['membersOnly']
        );
        if ($postData['forceSDKLogin']) {
            $postData['allowListeners'] = false;
            $postData['autoInvite'] = false;
            $postData['inviteFriends'] = false;
        } elseif ($membersChanged) {
            $postData['facebookLogin'] = true;
            $postData['twitterLogin'] = true;
            $postData['rumbleTalkLogin'] = true;
            $postData['anonymous'] = true;
        }

        $result = $this->rumbletalk->put("chats/{$chats[$hash]['id']}", $postData);

        $this->response($result);
    }

    private function deleteChat($data)
    {
        $result = $this->rumbletalk->delete("chats/{$data['id']}");

        if ($result['status']) {
            $chats = RumbleTalk_Admin::getChats();
            foreach ($chats as $hash => $chat) {
                if ($chat['id'] == $data['id']) {
                    unset($chats[$hash]);
                    break;
                }
            }

            RumbleTalk_Admin::updateChats($chats);
        }

        $this->response($result);
    }

    public function reloadChats($return = false)
    {
        $chatsOld = RumbleTalk_Admin::getChats();

        $result = $this->rumbletalk->get('chats');

        $chats = array();
        if ($result['status']) {
            foreach ($result['data'] as $chat) {
                $chats[$chat['hash']] = array(
                    'id' => $chat['id'],
                    'name' => $chat['name'],
                    'width' => @$chatsOld[$chat['hash']]['width'],
                    'height' => @$chatsOld[$chat['hash']]['height'],
                    'membersOnly' => @$chatsOld[$chat['hash']]['membersOnly'],
                    'loginName' => @$chatsOld[$chat['hash']]['loginName'],
                    'floating' => @$chatsOld[$chat['hash']]['floating']
                );
            }
        }

        RumbleTalk_Admin::updateChats($chats);

        if (!$return) {
            $this->response(
                array(
                    'chats' => $chats,
                    'status' => $result['status']
                )
            );
        }

        return $chats;
    }

    public function getAccountInfo($return = false)
    {
        $result = $this->rumbletalk->get('account');

        if (!$return) {
            $this->response($result);
        }

        return $result;
    }

    public function getAccessToken()
    {
        return get_option('rumbletalk_accesstoken');
    }
}

