<?php
defined('ALTUMCODE') || die();

return (object) [
    'plugin_id' => 'ai-writer',
    'name' => 'AI Writer',
    'description' => 'This plugin implements the OpenAI API system for content writing.',
    'version' => '1.0.0',
    'url' => 'https://altumco.de/ai-writer-plugin',
    'author' => 'AltumCode',
    'author_url' => 'https://altumcode.com/',
    'status' => 'inexistent',
    'actions'=> true,
    'settings_url' => url('admin/settings/ai_writer'),
    'avatar_style' => 'background: #4CA1AF;background: -webkit-linear-gradient(to right, #C4E0E5, #4CA1AF); background: linear-gradient(to right, #C4E0E5, #4CA1AF);',
    'icon' => '🤖',
];
