<?php

return [

    'ticket_number' => [
        'prefix' => env('HELPDESK_TICKET_PREFIX', '#'),
        'padding' => 4,
    ],

    'ai' => [
        'enabled' => env('HELPDESK_AI_ENABLED', true),
        'provider' => 'anthropic',
        'api_key' => env('ANTHROPIC_API_KEY'),
        'api_version' => env('ANTHROPIC_API_VERSION', '2023-06-01'),
        'model' => env('HELPDESK_AI_MODEL', 'claude-haiku-4-5-20251001'),
        'labeling_max_tokens' => 200,
        'skill_update_max_tokens' => 2000,
        'skill_path' => env('HELPDESK_AI_SKILL_PATH', 'ai-skill/labeling-skill.md'),
        'skill_update_threshold' => env('HELPDESK_AI_SKILL_UPDATE_THRESHOLD', 5),
    ],

    'motion' => [
        'enabled' => env('HELPDESK_MOTION_ENABLED', true),
        'api_key' => env('MOTION_API_KEY'),
        'workspace_id' => env('MOTION_WORKSPACE_ID'),
        'base_url' => env('MOTION_BASE_URL', 'https://api.usemotion.com/v1'),
    ],

    'graph' => [
        'enabled' => env('HELPDESK_GRAPH_ENABLED', true),
        'tenant_id' => env('MICROSOFT_TENANT_ID'),
        'client_id' => env('MICROSOFT_CLIENT_ID'),
        'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
        'mailbox' => env('MICROSOFT_MAILBOX'),
    ],

];
