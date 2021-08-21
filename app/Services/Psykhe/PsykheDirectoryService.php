<?php

namespace App\Services\Psykhe;

use App\Models\User;

class PsykheDirectoryService implements Contracts\PsykheDirectoryService
{
    protected static string $prefix = '/api/v3/directory/';

    protected Contracts\PsykheService $psykhe;

    /**
     * PsykheDirectoryService constructor.
     *
     * @param Contracts\PsykheService $psykhe
     */
    public function __construct(Contracts\PsykheService $psykhe)
    {
        $this->psykhe = $psykhe;
    }

    /**
     * @param User $user
     */
    public function createUser(User $user)
    {
        $url = static::$prefix.'users';

        try {
            $data = [
                'identifier' => $user->identifier,
                'responses'  => [
                    'pt'     => $user->formatted_survey_responses,
                    'visual' => $user->formatted_visual_responses,
                ],
            ];

            $response = $this->psykhe->Request('POST', $url, [
                'json' => $data,
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // convert 409 into update requests
            // This isn't the best option, but should work-around some edge cases
            if ($e->getCode() === 409) {
                return $this->updateUser($user);
            }

            throw new \Exception('Unexpected Response during CreateUser with '.json_encode($data).': '.$e->getMessage(), $e->getCode(), $e);
        }

        return $response;
    }

    /**
     * @param User $user
     */
    public function updateUser(User $user)
    {
        $url = static::$prefix."users/{$user->identifier}";

        try {
            $data = [
                'identifier' => $user->identifier,
                'responses'  => [
                    'visual' => $user->formatted_mutable_visual_responses,
                ],
            ];

            return $this->psykhe->Request('PATCH', $url, [
                'json' => $data,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Unexpected Response during UpdateUser with '.json_encode($data).': '.$e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param User $user
     */
    public function deleteUser(User $user)
    {
        $url = static::$prefix."users/{$user->identifier}";

        return $this->psykhe->Request('delete', $url);
    }

    /**
     * @param User   $user
     * @param string $name
     */
    public function createSavelist(User $user, string $name)
    {
        $url = static::$prefix."user/{$user->identifier}/savelist";

        return $this->psykhe->Request('POST', $url, [
            'json' => [
                'name' => $name,
            ],
        ]);
    }

    /**
     * @param User $user
     */
    public function listSavelists(User $user)
    {
        $url = static::$prefix."user/{$user->identifier}/savelist";

        return $this->psykhe->Request('GET', $url);
    }

    /**
     * @param User   $user
     * @param string $savelistSlug
     */
    public function getSavelistBySlug(User $user, string $savelistSlug)
    {
        $url = static::$prefix."user/{$user->identifier}/savelist/{$savelistSlug}";

        return $this->psykhe->Request('GET', $url);
    }

    /**
     * @param User   $user
     * @param string $slug
     * @param string $name
     */
    public function renameSavelist(User $user, string $slug, string $name)
    {
        $url = static::$prefix."user/{$user->identifier}/savelist/{$slug}";

        return $this->psykhe->Request('PATCH', $url, [
            'json' => [
                'name' => $name,
            ],
        ]);
    }

    /**
     * @param User   $user
     * @param string $slug
     */
    public function deleteSavelist(User $user, string $slug)
    {
        $url = static::$prefix."user/{$user->identifier}/savelist/{$slug}";

        return $this->psykhe->Request('DELETE', $url);
    }

    /**
     * @param User   $user
     * @param string $savelistSlug
     * @param string $productIdentifier
     */
    public function addToSavelist(User $user, string $savelistSlug, string $productIdentifier)
    {
        $url = static::$prefix."user/{$user->identifier}/savelist/{$savelistSlug}/{$productIdentifier}";

        return $this->psykhe->Request('PUT', $url);
    }

    /**
     * @param User   $user
     * @param string $savelistSlug
     * @param string $productIdentifier
     */
    public function removeFromSavelist(User $user, string $savelistSlug, string $productIdentifier)
    {
        $url = static::$prefix."user/{$user->identifier}/savelist/{$savelistSlug}/{$productIdentifier}";

        return $this->psykhe->Request('DELETE', $url);
    }
}
