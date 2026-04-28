<?php

namespace App\Traits;

use App\Models\User;

trait ActivityDescriptionTrait
{
    /**
     * @param  string  $event The event type
     * @param  string  $model The model in display format
     * @param  User|null  $user The user that caused the event
     * @param  string  $createdAction The name of the action when the event is created
     * @param  string  $updatedAction The name of the action when the event is updated
     * @param  string  $deletedAction The name of the action when the event is deleted
     * @return string
     */
    public function descriptionForEvent(
        string $event,
        string $model,
        string $createdAction = 'created',
        string $updatedAction = 'updated',
        string $deletedAction = 'deleted'
    ) : string
    {
        if (auth()->user()){
            $user = auth()->user();

            return match ($event){
                'created' => "$user->name $createdAction a new $model",
                'updated' => "$user->name $updatedAction the $model",
                'deleted' => "$user->name $deletedAction the $model",
                default => "$user->name did something here"
            };
        }

        return match ($event){
            'created' => "A new $model has been $createdAction",
            'updated' => "The $model has been $updatedAction",
            'deleted' => "The $model has been $deletedAction",
            default => 'Something happened here'
        };
    }
}
