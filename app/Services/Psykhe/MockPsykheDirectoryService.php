<?php

namespace App\Services\Psykhe;

use App\Models\User;

class MockPsykheDirectoryService implements Contracts\PsykheDirectoryService
{
    protected static string $prefix = '/api/v3/directory/';

    protected Contracts\PsykheService $psykhe;

    public function __construct(Contracts\PsykheService $psykhe)
    {
        $this->psykhe = $psykhe;
    }

    /**
     * @param User $user
     *
     * @return array|mixed
     */
    public function createUser(User $user)
    {
        $userIdentifier = $user->getIdentifierAttribute();

        return [
            'status'  => 201,
            'message' => "user ${userIdentifier} created successfully", ];
    }

    /**
     * @param User $user
     *
     * @return array|mixed
     */
    public function deleteUser(User $user)
    {
        $userIdentifier = $user->getIdentifierAttribute();

        return [
            'status'  => 200,
            'message' => "user ${userIdentifier} deleted successfully", ];
    }

    /**
     * @param User   $user
     * @param string $name
     *
     * @return array|mixed
     */
    public function createSavelist(User $user, string $name)
    {
        $slug = str_replace(' ', '-', strtolower($name));

        return [
            'status' => 201,
            'name'   => $name,
            'slug'   => $slug, ];
    }

    /**
     * @param User $user
     *
     * @return array|mixed
     */
    public function listSavelists(User $user)
    {
        $savelists = [
            ['name' => 'likes', 'slug' => 'likes'],
            ['name' => 'Top Summer', 'slug' => 'top-summer'],
            ['name' => 'Dislikes', 'slug' => 'dislikes'],
        ];

        return [
            'status'    => 200,
            'savelists' => $savelists, ];
    }

    /**
     * @param User   $user
     * @param string $savelistSlug
     *
     * @return array|mixed
     */
    public function getSavelistBySlug(User $user, string $savelistSlug)
    {
        $products = [
            2001,
            2002,
            2000004,
            42,
        ];

        return [
            'status'   => 200,
            'name'     => 'name',
            'slug'     => $savelistSlug,
            'products' => $products, ];
    }

    /**
     * @param User   $user
     * @param string $slug
     * @param string $name
     *
     * @return array|mixed
     */
    public function renameSavelist(User $user, string $slug, string $name)
    {
        return [
            'status' => 200,
            'name'   => 'My new savelist',
            'slug'   => 'my-new-savelist', ];
    }

    /**
     * @param User   $user
     * @param string $slug
     *
     * @return array|mixed
     */
    public function deleteSavelist(User $user, string $slug)
    {
        return [
            'status'  => 200,
            'message' => "user savelist ${slug} deleted", ];
    }

    /**
     * @param User   $user
     * @param string $savelistSlug
     * @param string $productIdentifier
     *
     * @return array|mixed
     */
    public function addToSavelist(User $user, string $savelistSlug, string $productIdentifier)
    {
        $userIdentifier = $user->getIdentifierAttribute();

        switch ($productIdentifier) {
            case 401401401:
                $mock_response = [
                    'status'  => 401,
                    'message' => 'Authorization token is missing/wrong', ];
                break;
            case 404404404:
                $mock_response = [
                    'status'  => 404,
                    'message' => "identifier ${userIdentifier} doesn't exist", ];
                break;
            case 409409409:
                $mock_response = [
                    'status'  => 409,
                    'message' => "Product with identifier ${productIdentifier} already exists in the savelist ${savelistSlug}",
                ];
                break;
            case 500500500:
                $mock_response = [
                    'status'  => 500,
                    'message' => 'StatusInternalServerError', ];
                break;
            default:
                $mock_response = [
                    'status'  => 200,
                    'message' => "product ${productIdentifier} successfully added to savelist ${savelistSlug}", ];
        }

        return $mock_response;
    }

    /**
     * @param User   $user
     * @param string $savelistSlug
     * @param string $productIdentifier
     *
     * @return array|mixed
     */
    public function removeFromSavelist(User $user, string $savelistSlug, string $productIdentifier)
    {
        $userIdentifier = $user->getIdentifierAttribute();

        switch ($productIdentifier) {
            case 401401401:
                $mock_response = [
                    'status'  => 401,
                    'message' => 'Authorization token is missing/wrong', ];
                break;
            case 404404404:
                $mock_response = [
                    'status'  => 404,
                    'message' => "identifier ${userIdentifier} doesn't exist", ];
                break;
            case 500500500:
                $mock_response = [
                    'status'  => 500,
                    'message' => 'StatusInternalServerError', ];
                break;
            default:
                $mock_response = [
                    'status'  => 200,
                    'message' => "product ${productIdentifier} successfully deleted from savelist ${savelistSlug}", ];
        }

        return $mock_response;
    }
}
