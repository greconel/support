<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

/**
 * @authenticated
 * @group Users
 *
 * Manage users
 */
class UserController extends Controller
{
    /**
     * Get all users
     *
     * Get all users from AMPP
     */
    public function __invoke()
    {
        return UserResource::collection(User::all());
    }
}
