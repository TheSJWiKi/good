<?php
/*
 * @copyright Copyright (c) 2021 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Response;

class DocumentCreate extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('ai-writer') || !settings()->ai_writer->is_enabled) {
            redirect('dashboard');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.documents')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('documents');
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `documents` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->documents_limit != -1 && $total_rows >= $this->user->plan_settings->documents_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('documents');
        }

        $available_words = $this->user->plan_settings->words_per_month_limit - db()->where('user_id', $this->user->user_id)->getValue('users', '`ai_writer_words_current_month`');

        if($this->user->plan_settings->words_per_month_limit != -1 && $available_words <= 0) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('documents');
        }

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* AI Types */
        $ai_types = require \Altum\Plugin::get('ai-writer')->path . 'includes/ai_types.php';

        $values = [
            'name' => $_POST['name'] ?? '',
            'language' => $_POST['language'] ?? \Altum\Language::$name,
            'variants' => $_POST['variants'] ?? 1,
            'max_words_per_variant' => $_POST['max_words_per_variant'] ?? null,
            'creativity_level' => $_POST['creativity_level'] ?? 'optimal',
            'type' => $_POST['type'] ?? 'summarize',
            'summarize_input' => $_POST['summarize_input'] ?? null,
            'social_bio_input' => $_POST['social_bio_input'] ?? null,
            'seo_title_input' => $_POST['seo_title_input'] ?? null,
            'seo_description_input' => $_POST['seo_description_input'] ?? null,
            'seo_keywords_input_input' => $_POST['seo_keywords_input_input'] ?? null,
            'project_id' => $_POST['project_id'] ?? null,
        ];

        /* Prepare the View */
        $data = [
            'values' => $values,
            'available_words' => $available_words,
            'projects' => $projects ?? [],
            'ai_types' => $ai_types,
        ];

        $view = new \Altum\View(\Altum\Plugin::get('ai-writer')->path . 'views/document-create/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));

    }

    public function create_ajax() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        if(empty($_POST)) {
            redirect();
        }

        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('ai-writer') || !settings()->ai_writer->is_enabled) {
            redirect('dashboard');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.documents')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `documents` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->documents_limit != -1 && $total_rows >= $this->user->plan_settings->documents_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        $available_words = $this->user->plan_settings->words_per_month_limit - db()->where('user_id', $this->user->user_id)->getValue('users', '`ai_writer_words_current_month`');

        if($this->user->plan_settings->words_per_month_limit != -1 && $available_words <= 0) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* AI Types */
        $ai_types = require \Altum\Plugin::get('ai-writer')->path . 'includes/ai_types.php';

        $_POST['name'] = input_clean($_POST['name'], 64);
        $_POST['language'] = input_clean($_POST['language'], 64);
        $_POST['variants'] = (int) $_POST['variants'] < 0 || (int) $_POST['variants'] > 3 ? 1 : (int) $_POST['variants'];
        if(is_numeric($_POST['max_words_per_variant'])) {
            $_POST['max_words_per_variant'] = (int) $_POST['max_words_per_variant'] < 1 || (int) $_POST['max_words_per_variant'] > 1000 ? 10 : (int) $_POST['max_words_per_variant'];
        } else {
            $_POST['max_words_per_variant'] = null;
        }
        $_POST['creativity_level'] = $_POST['creativity_level'] && in_array($_POST['creativity_level'], ['none', 'low', 'optimal', 'high', 'maximum']) ? $_POST['creativity_level'] : 'optimal';
        $_POST['type'] = $_POST['type'] && array_key_exists($_POST['type'], $ai_types) ? $_POST['type'] : null;
        $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;

        /* Check for any errors */
        $required_fields = ['name', 'type'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
            }
        }

        if(!\Altum\Csrf::check('global_token')) {
            Response::json(l('global.error_message.invalid_csrf_token'), 'error');
        }

        /* OpenAI */
        $max_tokens = $_POST['max_words_per_variant'] ? (int) ceil(($_POST['max_words_per_variant'] * 1.333)) : 2048;

        /* Input */
        $input = null;
        $prompt = 'Write answer in ' . $_POST['language'] . '. ';
        switch($_POST['type']) {
            case 'summarize':

                $input = input_clean($_POST['summarize_input']);
                $prompt = sprintf($ai_types[$_POST['type']]['prompt'], $input);

                break;

            case 'social_bio':

                $input = input_clean($_POST['social_bio_input']);
                $prompt = sprintf($ai_types[$_POST['type']]['prompt'], $input);

                break;

            case 'seo_title':

                $input = input_clean($_POST['seo_title_input']);
                $prompt = sprintf($ai_types[$_POST['type']]['prompt'], $input);

                break;

            case 'seo_description':

                $input = input_clean($_POST['seo_description_input']);
                $prompt = sprintf($ai_types[$_POST['type']]['prompt'], $input);

                break;

            case 'seo_keywords':

                $input = input_clean($_POST['seo_keywords_input']);
                $prompt = sprintf($ai_types[$_POST['type']]['prompt'], $input);

                break;

            case 'blog_article_idea_and_outline':

                $input = input_clean($_POST['blog_article_idea_and_outline_input']);
                $prompt = sprintf($ai_types[$_POST['type']]['prompt'], $input);

                break;

            case 'blog_article_section':

                $title = input_clean($_POST['blog_article_idea_and_outline_title']);
                $keywords = input_clean($_POST['blog_article_idea_and_outline_keywords']);
                $input = json_encode([
                    'title' => $title,
                    'keywords' => $keywords
                ]);
                $prompt = sprintf($ai_types[$_POST['type']]['prompt'], $title, $keywords);

                break;

            case 'video_idea':

                $input = input_clean($_POST['video_idea_input']);
                $prompt = sprintf($ai_types[$_POST['type']]['prompt'], $input);

                break;

            case 'video_title':

                $input = input_clean($_POST['video_title_input']);
                $prompt = sprintf($ai_types[$_POST['type']]['prompt'], $input);

                break;

            case 'video_description':

                $input = input_clean($_POST['video_description_input']);
                $prompt = sprintf($ai_types[$_POST['type']]['prompt'], $input);

                break;
        }

        /* Temperature */
        $temperature = 0.6;
        switch($_POST['creativity_level']) {
            case 'none': $temperature = 0; break;
            case 'low': $temperature = 0.3; break;
            case 'optimal': $temperature = 0.6; break;
            case 'high': $temperature = 0.8; break;
            case 'maximum': $temperature = 1; break;
        }

        try {
            $response = \Unirest\Request::post(
                'https://api.openai.com/v1/completions',
                [
                    'Authorization' => 'Bearer ' . settings()->ai_writer->openai_api_key,
                    'Content-Type' => 'application/json',
                ],
                \Unirest\Request\Body::json([
                    'model' => 'text-davinci-003',
                    'prompt' => $prompt,
                    'max_tokens' => $max_tokens,
                    'temperature' => $temperature,
                    'n' => $_POST['variants'],
                ])
            );

            if($response->code >= 400) {
                Alerts::add_error($response->body->error->message);
            }

        } catch (\Exception $exception) {
            Response::json($exception->getMessage(), 'error');
        }

        $settings = json_encode([
            'language' => $_POST['language'],
            'variants' => $_POST['variants'],
            'max_words_per_variant' => $_POST['max_words_per_variant'],
            'creativity_level' => $_POST['creativity_level'],
        ]);

        /* Words */
        $words = 0;

        /* AI Content */
        if(count($response->body->choices) > 1) {
            $content = '';

            foreach($response->body->choices as $key => $choice) {
                $content .= ($key+1) . '. ' . trim($choice->text) . "\r\n\r\n";
                $words += count(explode(' ', (trim($choice->text))));
            }
        } else {
            $content = trim($response->body->choices[0]->text);
            $words += count(explode(' ', ($content)));
        }

        /* Prepare the statement and execute query */
        $document_id = db()->insert('documents', [
            'user_id' => $this->user->user_id,
            'name' => $_POST['name'],
            'type' => $_POST['type'],
            'input' => $input,
            'content' => $content,
            'words' => $words,
            'settings' => $settings,
            'datetime' => \Altum\Date::$date,
        ]);

        /* Prepare the statement and execute query */
        db()->where('user_id', $this->user->user_id)->update('users', [
            'ai_writer_words_current_month' => db()->inc($words)
        ]);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'), 'success', ['url' => url('document-update/' . $document_id)]);

    }

}
