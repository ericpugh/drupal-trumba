<?php

namespace Drupal\trumba\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;

/**
 * Provides a 'TrumbaMainCalendarSpudBlock' block.
 *
 * @Block(
 *  id = "trumba_main_calendar_spud_block",
 *  admin_label = @Translation("Trumba Main Calendar Spud"),
 * )
 */
class TrumbaMainCalendarSpudBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $default_trumba_web_name = \Drupal::config('trumba.trumbaconfiguration')->get('default_web_name');

    $form['trumba_main_calendar_web_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Web Name'),
      '#description' => $this->t('This is the unique identifier for your calendar account on Trumba.'),
      '#default_value' => isset($this->configuration['trumba_main_calendar_web_name']) ? $this->configuration['trumba_main_calendar_web_name'] : $default_trumba_web_name,
      '#maxlength' => 255,
      '#size' => 64,
      '#weight' => '1',
      '#required' => TRUE,
    );
    $form['trumba_main_calendar_calendar_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Calendar URL'),
      '#description' => $this->t('Enter the full path URL for this website where this calendar will be placed (e.g.: https://www.yoursite.com/calendar).'),
      '#default_value' => isset($this->configuration['trumba_main_calendar_calendar_url']) ? $this->configuration['trumba_main_calendar_calendar_url'] : '',
      '#maxlength' => 255,
      '#size' => 64,
      '#weight' => '2',
    );
    $form['trumba_main_calendar_open_events'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Open events in new window'),
      '#description' => $this->t(''),
      '#default_value' => isset($this->configuration['trumba_main_calendar_open_events']) ? $this->configuration['trumba_main_calendar_open_events'] : '',
      '#weight' => '3',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['trumba_main_calendar_web_name'] = $form_state->getValue('trumba_main_calendar_web_name');
    $this->configuration['trumba_main_calendar_calendar_url'] = $form_state->getValue('trumba_main_calendar_calendar_url');
    $this->configuration['trumba_main_calendar_open_events'] = $form_state->getValue('trumba_main_calendar_open_events');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $spud_id = Html::getUniqueId($this->getBaseId());

    $params = array(
      'webName' => $this->configuration['trumba_main_calendar_web_name'],
      'spudType' => 'main',
      'detailBase' => $this->configuration['trumba_main_calendar_calendar_url'],
      'openInNewWindow' => $this->configuration['trumba_main_calendar_open_events'],
      'spudId' => $spud_id,
    );

    return _trumba_spud_embed($spud_id, $params);
  }

}
