<?php

abstract class ClimbingConcepts_PageView {
    /* user action for the screen */
    protected $action = '';
    /* data for the pageview */
    protected $data = array();
    protected $admin_page;
    protected $textboxes = array();
    /* number of screens for post boxes */
    protected $screen_columns = 0;

    public function __construct() {
        $screen = get_current_screen();
        if ($this->screen_columns !== 0) {
            $screen->add_option('layout_columns', array('max' => $this->screen_columns));
        }

        add_filter("get_user_option_screen_layout_{$screen->id}",
                   array($this, 'set_current_screen_layout_columns'));

        $screen->add_help_tab(
            array('id' => 'climbing-concepts-help',
                  'title' => __('Climbing Concepts Help', 'climbingconcepts'),
                  'content' => '<p>' . $this->help_tab_content() . '</p>')
        );
    }

    public function set_current_screen_layout_columns($result) {
        if ($result === false ||
            $result > $this->screen_columns) {
            $result = $this->screen_columns;
        }
        return $result;
    }

    public function setup($action, array $data) {
        $this->action = $action;
        $this->data = $data;

        // Set page title.
        $GLOBALS['title'] = sprintf( __( '%1$s &lsaquo; %2$s', 'tablepress' ), $this->data['view_actions'][ $this->action ]['page_title'], 'TablePress' );

    }

    protected function help_tab_content() {
        return '';
    }
}
