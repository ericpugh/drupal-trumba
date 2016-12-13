<?php

namespace Drupal\trumba\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;

/**
 * Provides a 'OpenSpudBlock' block.
 *
 * @Block(
 *  id = "open_spud_block",
 *  admin_label = @Translation("Trumba Open Calendar Spud"),
 * )
 */
class OpenSpudBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $default_trumba_name = \Drupal::config('trumba.settings')->get('trumba_webname');
    $form['web_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Web Name'),
      '#description' => $this->t('This is the unique identifier for your calendar account on Trumba.'),
      '#default_value' => isset($this->configuration['web_name']) ? $this->configuration['web_name'] : $default_trumba_name,
      '#maxlength' => 128,
      '#size' => 64,
      '#weight' => '0',
      '#required' => TRUE,
    );
    $form['calendar_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Calendar URL'),
      '#description' => $this->t('Enter the full path URL for this website where this calendar will be placed (e.g.: https://ucdavis.edu/calendar)'),
      '#default_value' => isset($this->configuration['calendar_url']) ? $this->configuration['calendar_url'] : '',
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    );
    $form['spud_type'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Spud Type'),
      '#description' => $this->t('Enter the name for the type of spud this should be.'),
      '#default_value' => isset($this->configuration['spud_type']) ? $this->configuration['spud_type'] : '',
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#required' => TRUE,
    );
    $form['open_events_in_new_window'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Open events in new window'),
      '#description' => '',
      '#default_value' => isset($this->configuration['open_events_in_new_window']) ? $this->configuration['open_events_in_new_window'] : '',
      '#weight' => '0',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['web_name'] = $form_state->getValue('web_name');
    $this->configuration['calendar_url'] = $form_state->getValue('calendar_url');
    $this->configuration['open_events_in_new_window'] = $form_state->getValue('open_events_in_new_window');
    $this->configuration['spud_type'] = $form_state->getValue('spud_type');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $spud_id = Html::getUniqueId($this->getBaseId());

    $params = array(
      'webName' => $this->configuration['web_name'],
      'spudType' => $this->configuration['spud_type'],
      'detailBase' => $this->configuration['calendar_url'],
      'openInNewWindow' => $this->configuration['open_events_in_new_window'],
      'spudId' => $spud_id,
    );

    return _trumba_spud_embed($spud_id, $params);
  }

}
