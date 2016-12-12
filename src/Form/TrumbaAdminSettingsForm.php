<?php

namespace Drupal\trumba\Form;

use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Process admin settings for Trumba.
 */
class TrumbaAdminSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'trumba_admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('trumba.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['trumba.settings'];
  }

  /**
   * Collect the webname.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Used to identify the organization/account that the spuds belong to.
    $form = [];

    $form['trumba_webname'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => t('Default Web Name'),
      '#description' => t('This is the default unique identifier for your account on Trumba.'),
      '#default_value' => \Drupal::config('trumba.settings')->get('trumba_webname'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Make sure submitted form is not an attack.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Make sure this not an attack.
    $form['trumba_webname']['#value'] = SafeMarkup::checkPlain($form['trumba_webname']['#value']);
    // If it is, complain.
    if (empty($form['trumba_webname']['#value'])) {
      $form_state->setErrorByName('trumba_webname', 'Please enter a valid Trumba webname');
    }
  }

}
