<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('indexProject', function (BreadcrumbTrail $trail){
    $trail->push(__('Projects'), action(\App\Http\Controllers\Ampp\Projects\IndexProjectController::class));
});

Breadcrumbs::for('createProject', function (BreadcrumbTrail $trail){
    $trail
        ->parent('indexProject')
        ->push(__('Create new Project'), action(\App\Http\Controllers\Ampp\Projects\CreateProjectController::class))
    ;
});

Breadcrumbs::for('showProject', function (BreadcrumbTrail $trail, \App\Models\Project $project){
    $trail
        ->parent('indexProject')
        ->push($project->name, action(\App\Http\Controllers\Ampp\Projects\ShowProjectOverviewController::class, $project))
    ;
});

Breadcrumbs::for('editProject', function (BreadcrumbTrail $trail, \App\Models\Project $project){
    $trail
        ->parent('showProject', $project)
        ->push(__('Edit'), action(\App\Http\Controllers\Ampp\Projects\EditProjectController::class, $project))
    ;
});

Breadcrumbs::for('showProjectOverview', function (BreadcrumbTrail $trail, \App\Models\Project $project){
    $trail
        ->parent('showProject', $project)
        ->push(__('Overview'), action(\App\Http\Controllers\Ampp\Projects\ShowProjectOverviewController::class, $project))
    ;
});

Breadcrumbs::for('showProjectTodos', function (BreadcrumbTrail $trail, \App\Models\Project $project){
    $trail
        ->parent('showProject', $project)
        ->push(__('To do\'s'), action(\App\Http\Controllers\Ampp\Projects\ShowProjectTodoController::class, $project))
    ;
});

Breadcrumbs::for('showProjectCalendar', function (BreadcrumbTrail $trail, \App\Models\Project $project){
    $trail
        ->parent('showProject', $project)
        ->push(__('Calendar'), action(\App\Http\Controllers\Ampp\Projects\ShowProjectCalendarController::class, $project))
    ;
});

Breadcrumbs::for('showProjectTable', function (BreadcrumbTrail $trail, \App\Models\Project $project){
    $trail
        ->parent('showProject', $project)
        ->push(__('Table'), action(\App\Http\Controllers\Ampp\Projects\ShowProjectCalendarController::class, $project))
    ;
});

Breadcrumbs::for('showProjectFiles', function (BreadcrumbTrail $trail, \App\Models\Project $project){
    $trail
        ->parent('showProject', $project)
        ->push(__('Files'), action(\App\Http\Controllers\Ampp\Projects\ShowProjectFileController::class, $project))
    ;
});

Breadcrumbs::for('showProjectEmails', function (BreadcrumbTrail $trail, \App\Models\Project $project){
    $trail
        ->parent('showProject', $project)
        ->push(__('E-mails'), action(\App\Http\Controllers\Ampp\Projects\ShowProjectEmailController::class, $project))
    ;
});

Breadcrumbs::for('showProjectNotes', function (BreadcrumbTrail $trail, \App\Models\Project $project){
    $trail
        ->parent('showProject', $project)
        ->push(__('Notes'), action(\App\Http\Controllers\Ampp\Projects\ShowProjectNoteController::class, $project))
    ;
});

Breadcrumbs::for('showProjectLinks', function (BreadcrumbTrail $trail, \App\Models\Project $project){
    $trail
        ->parent('showProject', $project)
        ->push(__('Links'), action(\App\Http\Controllers\Ampp\ProjectLinks\ShowProjectLinkController::class, $project))
    ;
});
