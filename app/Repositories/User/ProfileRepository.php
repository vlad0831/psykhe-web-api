<?php

namespace App\Repositories\User;

use App\Models\User\Profile;
use App\Repositories\CoreRepository;
use Illuminate\Support\Facades\DB;

class ProfileRepository extends CoreRepository
{
    /**
     * @param Profile $model
     *
     * @return void
     */
    public function __construct(Profile $model)
    {
        parent::__construct($model);
    }

    /**
     * Retrieve the options for the provided profile.
     *
     * @param Profile $model
     *
     * @return array[]
     */
    public function getOptions(Profile $model)
    {
        $options = $model->options;

        return array_map(function ($key, $value) {
            return [
                'key'   => $key,
                'value' => $value,
            ];
        }, array_keys($options), $options);
    }

    /**
     * Update the value of the option with the specified key.
     *
     * @param Profile $model
     * @param string  $key
     * @param $value
     */
    public function updateOption(Profile $model, string $key, $value)
    {
        DB::table($model->getTable())
            ->where('id', $model->id)
            ->update([
                "options->{$key}" => $value,
            ])
        ;
    }
}
