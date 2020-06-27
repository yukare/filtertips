<?php

namespace Drupal\filtertips\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigureTips.
 *
 * @package Drupal\filtertips\Form
 */
class Settings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'filtertips.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'filtertips_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('filtertips.settings');
    $text_formats = \Drupal::entityTypeManager()->getStorage('filter_format')->loadMultiple();
    foreach($text_formats as $text_format) {
      $id = $text_format->id();
      $name = $text_format->get('name');
      $form['details_' . $id ] = array(
        '#type' => 'details',
        '#title' => $name,
        // '#description' => t('Lorem ipsum.'),
        '#open' => FALSE, 
      );
      $form['details_' . $id ][$id . '_title'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Title'),
        '#default_value' => $config->get($id . '_title'),
        '#size' => 60,
      );
      $form['details_' . $id ][$id . '_tips'] = [
        '#type' => 'text_format',
        '#title' => $this->t('Filter guidelines for @format', array('@format' => $name)),
        '#default_value' => $config->get($id . '_tips')['value'],
        '#format' => $config->get($id . '_tips')['format'],
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->config('filtertips.settings');

    $text_formats = \Drupal::entityTypeManager()->getStorage('filter_format')->loadMultiple();
    foreach($text_formats as $text_format) {
      $id = $text_format->id();
      $config->set($id . '_title', $form_state->getValue($id . '_title'));
      $config->set($id . '_tips', $form_state->getValue($id . '_tips'));
    } 
    $config->save();
  }

}
