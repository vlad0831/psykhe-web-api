<?php

namespace App\Services\Configuration;

use App\Models\User;

class ComponentConfiguration
{
    public static function ComponentEnabledForRequest($component, User $user = null)
    {
        if (! config("psykhe.{$component}.enabled")) {
            return false;
        }

        if (config("psykhe.{$component}.nologin.enabled")) {
            return true;
        }

        if (! config("psykhe.{$component}.gated")) {
            return true;
        }

        return true;
    }

    // ComponentMaybeEnabledWithRequest returns whether or not there is some
    // additional request which may be made in order to enable access. This is
    // intentionally vague, as the specifics of how that request will be
    // implemented will change over time.
    public static function ComponentMaybeEnabledWithRequest($component, User $user = null)
    {
        // if it's not enabled, it's never enabled
        if (! config("psykhe.{$component}.enabled")) {
            return false;
        }

        if (config("psykhe.{$component}.nologin.enabled")) {
            // if nologin is enabled, then the status won't change with a login
            return false;
        }

        // if it's not gated, no request will help.
        if (! config("psykhe.{$component}.gated")) {
            return false;
        }

        // if the user is already through the gate, a request won't help
        if ($user) {
            return false;
        }

        return true;
    }
}
