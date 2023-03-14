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

class Documents extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('ai-writer') || !settings()->ai_writer->is_enabled) {
            redirect('dashboard');
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id', 'type'], ['name'], ['last_datetime', 'datetime', 'name', 'words']));
        $filters->set_default_order_by('document_id', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `documents` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('documents?' . $filters->get_get() . '&page=%d')));

        /* Get the documents */
        $documents = [];
        $documents_result = database()->query("
            SELECT
                *
            FROM
                `documents`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
            {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");
        while($row = $documents_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $documents[] = $row;
        }

        /* Export handler */
        process_export_csv($documents, 'include', ['document_id', 'project_id', 'user_id', 'name', 'type', 'content', 'words', 'datetime', 'last_datetime'], sprintf(l('documents.title')));
        process_export_json($documents, 'include', ['document_id', 'project_id', 'user_id', 'name', 'type', 'content', 'words', 'settings', 'datetime', 'last_datetime'], sprintf(l('documents.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Available words */
        $available_words = $this->user->plan_settings->words_per_month_limit - db()->where('user_id', $this->user->user_id)->getValue('users', '`ai_writer_words_current_month`');

        /* AI Types */
        $ai_types = require \Altum\Plugin::get('ai-writer')->path . 'includes/ai_types.php';

        /* Prepare the View */
        $data = [
            'projects' => $projects,
            'documents' => $documents,
            'total_documents' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'available_words' => $available_words,
            'ai_types' => $ai_types,
        ];

        $view = new \Altum\View(\Altum\Plugin::get('ai-writer')->path . 'views/documents/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

    public function delete() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.documents')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('documents');
        }

        if(empty($_POST)) {
            redirect('documents');
        }

        $document_id = (int) query_clean($_POST['document_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$document = db()->where('document_id', $document_id)->where('user_id', $this->user->user_id)->getOne('documents', ['document_id', 'name'])) {
            redirect('documents');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('document_id', $document_id)->delete('documents');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $document->name . '</strong>'));

            redirect('documents');
        }

        redirect('documents');
    }

}
