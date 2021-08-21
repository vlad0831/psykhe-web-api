<?php

namespace App\Services\MailChimp;

use DrewM\MailChimp\MailChimp as MailChimpApi;
use Illuminate\Support\Facades\Log;

class MailChimp
{
    protected $api;

    protected $enabled;

    protected $list_id;

    public function __construct(
        ?MailChimpApi $api,
        $enabled,
        $list_id
    ) {
        $this->api     = $api;
        $this->enabled = (bool) $enabled;
        $this->list_id = $list_id;
    }

    public function getSubscriberHash($email)
    {
        return md5(strtolower($email));
    }

    public function getTags($email)
    {
        if (! $this->enabled) {
            return false;
        }

        $response = $this->api->get("/lists/{$this->list_id}/members/{$this->getSubscriberHash($email)}/tags");

        if (! isset($response['tags'])) {
            // no tags set. This is probably an API failure.
            Log::warning('No tags set for MailChimp#getTags');

            return null;
        }

        if (isset($response['status']) && is_int($response['status'])) {
            if ($response['status'] === 404) {
                // subscriber not found. ie: they are not subscribed
                Log::debug("MailChimp#getTags Subscriber Not Found (${email})");

                return false;
            }
        }

        if (! is_array($response['tags'])) {
            // tags is anot an array. This is probably an API failure.
            Log::warning('MailChimp#getTags Unexpected non-array of tags Returned');

            return null;
        }

        $tags = [];
        foreach ($response['tags'] as $tag) {
            if (! is_array($tag) || ! isset($tag['name'])) {
                continue;
            }
            $tags[] = $tag['name'];
        }

        return $tags;
    }

    public function getSubscriptionStatus($email)
    {
        if (! $this->enabled) {
            return false;
        }
        $response = $this->api->get("/lists/{$this->list_id}/members/{$this->getSubscriberHash($email)}");

        if (! isset($response['status'])) {
            // no status set. This is probably an API failure.
            Log::warning('No status set for MailChimp#getSubscriptionStatus');

            return null;
        }

        if (is_int($response['status'])) {
            if ($response['status'] === 404) {
                // subscriber not found. ie: they are not subscribed
                Log::debug("MailChimp#getSubscriptionStatus Subscriber Not Found (${email})");

                return false;
            }

            // status unknown. This is probably an API failure.
            Log::warning('MailChimp#getSubscriptionStatus Unknown Status Returned');

            return null;
        }

        return $response['status'];
    }

    public function registerUnsubscribed($email, $merge = [])
    {
        if (! $this->enabled) {
            return false;
        }
        $data = [
            'email_address' => $email,
            'status'        => 'unsubscribed',
            'merge_fields'  => (object) $merge,
        ];
        Log::debug("MailChimp#registerUnsubscribed (${email})", $data);
        $response = $this->api->post("/lists/{$this->list_id}/members", $data);
        Log::debug("MailChimp#registerUnsubscribed (${email}) RESULT", $response);

        return isset($response['status']) && ! is_int($response['status']);
    }

    public function registerSubscribed($email, $merge = [])
    {
        if (! $this->enabled) {
            return false;
        }
        $data = [
            'email_address' => $email,
            'status'        => 'subscribed',
            'merge_fields'  => (object) $merge,
        ];
        Log::debug("MailChimp#registerSubscribed (${email})", $data);
        $response = $this->api->post("/lists/{$this->list_id}/members", $data);
        Log::debug("MailChimp#registerSubscribed (${email}) RESULT", $response);

        return isset($response['status']) && ! is_int($response['status']);
    }

    public function updateSubscribed($email, $merge = [])
    {
        if (! $this->enabled) {
            return false;
        }
        $data = [
            'status'       => 'subscribed',
            'merge_fields' => (object) $merge,
        ];
        Log::debug("MailChimp#updateSubscribed (${email})", $data);
        $response = $this->api->post("/lists/{$this->list_id}/members/{$this->getSubscriberHash($email)}", $data);
        Log::debug("MailChimp#updateSubscribed (${email}) RESULT", $response);

        return isset($response['status']) && ! is_int($response['status']);
    }

    public function update($email, $merge = [])
    {
        if (! $this->enabled) {
            return false;
        }
        $data = [
            'merge_fields' => (object) $merge,
        ];
        Log::debug("MailChimp#update (${email})", $data);
        $response = $this->api->patch("/lists/{$this->list_id}/members/{$this->getSubscriberHash($email)}", $data);
        Log::debug("MailChimp#update (${email}) RESULT", $response);

        return isset($response['status']) && ! is_int($response['status']);
    }

    public function addTag($email, $tag)
    {
        if (! $this->enabled) {
            return false;
        }
        $response = $this->api->post("/lists/{$this->list_id}/members/{$this->getSubscriberHash($email)}/tags", [
            'tags' => [
                [
                    'name'   => $tag,
                    'status' => 'active',
                ],
            ],
        ]);

        return isset($response['status']) && $response['status'] === 200;
    }
}
