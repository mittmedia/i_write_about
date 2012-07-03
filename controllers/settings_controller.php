<?php

namespace IWriteAbout
{
  class SettingsController extends \WpMvc\BaseController
  {
    public function index()
    {
      global $current_site;
      global $site;
      global $subjects;
      global $content;

      $site = \WpMvc\Site::find( $current_site->id );

      if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
        if ( isset( $_POST['site']['sitemeta']['i_write_about'] ) && trim( $_POST['site']['sitemeta']['i_write_about']['meta_value'] ) != '' ) {
          $websafe_name = 'i_write_about_';
          $websafe_name .= \WpMvc\ApplicationHelper::unique_identifier( $_POST['site']['sitemeta']['i_write_about']['meta_value'] );

          $site->sitemeta->{$websafe_name} = \WpMvc\SiteMeta::virgin();
          $site->sitemeta->{$websafe_name}->site_id = $site->id;
          $site->sitemeta->{$websafe_name}->meta_key = $websafe_name;
          $site->sitemeta->{$websafe_name}->meta_value = $_POST['site']['sitemeta']['i_write_about']['meta_value'];

          $site->sitemeta->{$websafe_name . '_link'} = \WpMvc\SiteMeta::virgin();
          $site->sitemeta->{$websafe_name . '_link'}->site_id = $site->id;
          $site->sitemeta->{$websafe_name . '_link'}->meta_key = $websafe_name . '_link';
          $site->sitemeta->{$websafe_name . '_link'}->meta_value = $_POST['site']['sitemeta']['i_write_about_link']['meta_value'];
        }
        unset( $_POST['site']['sitemeta']['i_write_about'] );
        unset( $_POST['site']['sitemeta']['i_write_about_link'] );

        $site->takes_post( $_POST['site'] );

        $site->save();
        static::redirect_to( "{$_SERVER['REQUEST_URI']}&i_write_about_updated=1" );
      }

      $subjects = array();

      $this->get_subjects_from_sitemeta( $subjects, $site );

      $content = array();

      $this->make_form_content_from_subjects( $content, $subjects, $site );

      $this->make_form_content_from_new_subject( $content, $site );

      $this->render( $this, "index" );
    }

    private function get_subjects_from_sitemeta( &$subjects, $site )
    {
      $sitemeta_vars = get_object_vars( $site->sitemeta );

      foreach ( $sitemeta_vars as $key => $value ) {
        if ( preg_match( '/^((?!.*_link)i_write_about.*)$/', $key ) )
          array_push( $subjects, $site->sitemeta->{$key} );
      }
    }
    private function make_form_content_from_subjects( &$content, $subjects, $site )
    {
      foreach ( $subjects as $subject ) {
        $content[] = array(
          'title' => __( 'Name' ),
          'name' => $site->sitemeta->{$subject->meta_key}->meta_key,
          'type' => 'text',
          'object' => $site->sitemeta->{$subject->meta_key},
          'default_value' => $site->sitemeta->{$subject->meta_key}->meta_value,
          'key' => 'meta_value'
        );

        $content[] = array(
          'title' => __( 'Link' ),
          'name' => $site->sitemeta->{$subject->meta_key . '_link'}->meta_key,
          'type' => 'text',
          'object' => $site->sitemeta->{$subject->meta_key . '_link'},
          'default_value' => $site->sitemeta->{$subject->meta_key . '_link'}->meta_value,
          'key' => 'meta_value'
        );

        $content[] = array(
          'title' => __( 'Delete' ),
          'type' => 'delete_action',
          'delete_objects' => array(
            $site->sitemeta->{$subject->meta_key}->meta_key,
            $site->sitemeta->{$subject->meta_key . '_link'}->meta_key
            ),
          'object' => $site->sitemeta->{$subject->meta_key}
        );

        $content[] = array( 'type' => 'spacer' );
      }
    }

    private function make_form_content_from_new_subject( &$content, &$site )
    {
      $site->sitemeta->i_write_about = \WpMvc\SiteMeta::virgin();
      $site->sitemeta->i_write_about->site_id = $site->id;
      $site->sitemeta->i_write_about->meta_key = 'i_write_about';
      $site->sitemeta->i_write_about->meta_value = '';

      $site->sitemeta->i_write_about_link = \WpMvc\SiteMeta::virgin();
      $site->sitemeta->i_write_about_link->site_id = $site->id;
      $site->sitemeta->i_write_about_link->meta_key = 'i_write_about_link';
      $site->sitemeta->i_write_about_link->meta_value = '';

      $content[] = array(
        'title' => __( 'Name' ),
        'name' => $site->sitemeta->i_write_about->meta_key,
        'type' => 'text',
        'object' => $site->sitemeta->i_write_about,
        'default_value' => $site->sitemeta->i_write_about->meta_value,
        'key' => 'meta_value'
      );

      $content[] = array(
        'title' => __( 'Link' ),
        'name' => $site->sitemeta->i_write_about_link->meta_key,
        'type' => 'text',
        'object' => $site->sitemeta->i_write_about_link,
        'default_value' => $site->sitemeta->i_write_about_link->meta_value,
        'key' => 'meta_value'
      );
    }
  }
}
