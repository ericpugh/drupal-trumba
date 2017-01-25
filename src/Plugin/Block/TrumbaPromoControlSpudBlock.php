<?php

namespace Drupal\trumba\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a 'TrumbaPromoControlSpudBlock' block.
 *
 * @Block(
 *  id = "trumba_promo_control_spud_block",
 *  admin_label = @Translation("Trumba Promotional or Control Calendar Spud"),
 * )
 */
class TrumbaPromoControlSpudBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $default_trumba_web_name = \Drupal::config('trumba.trumbaconfiguration')->get('default_web_name');

    $form['trumba_promo_control_spud_type'] = array(
      '#type' => 'select',
      '#title' => $this->t('Spud Type'),
      '#description' => $this->t('Select the type of spud this should be.'),
      '#options' => array('upcoming' => $this->t('upcoming'), 'datefinder' => $this->t('datefinder'), 'daysummary' => $this->t('daysummary'), 'filter' => $this->t('filter')),
      '#default_value' => isset($this->configuration['trumba_promo_control_spud_type']) ? $this->configuration['trumba_promo_control_spud_type'] : '',
      '#size' => 1,
      '#weight' => '1',
      '#required' => TRUE,
      '#empty_value' => '',
      '#empty_option' => t('- Select -')
    );
    $form['trumba_promo_control_spud_configuration'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Spud Configuration'),
      '#description' => $this->t('If the spud type requires configuration text enter it here.'),
      '#default_value' => isset($this->configuration['trumba_promo_control_spud_configuration']) ? $this->configuration['trumba_promo_control_spud_configuration'] : '',
      '#maxlength' => 255,
      '#size' => 64,
      '#weight' => '2',
    );
    $form['trumba_promo_control_web_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Web Name'),
      '#description' => $this->t('This is the unique identifier for your calendar account on Trumba.'),
      '#default_value' => isset($this->configuration['trumba_promo_control_web_name']) ? $this->configuration['trumba_promo_control_web_name'] : $default_trumba_web_name,
      '#maxlength' => 255,
      '#size' => 64,
      '#weight' => '3',
      '#required' => TRUE,
    );
    $form['trumba_promo_control_calendar_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Calendar URL'),
      '#description' => '<strong>' . $this->t('Only necessary if this spud will NOT be on the same page as the main calendar spud! '). '</strong>' . $this->t('Enter the full path URL for this website where this calendar will be placed (e.g.: https://ucdavis.edu/calendar).'),
      '#default_value' => isset($this->configuration['trumba_promo_control_calendar_url']) ? $this->configuration['trumba_promo_control_calendar_url'] : '',
      '#maxlength' => 255,
      '#size' => 64,
      '#weight' => '4',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['trumba_promo_control_spud_type'] = $form_state->getValue('trumba_promo_control_spud_type');
    $this->configuration['trumba_promo_control_spud_configuration'] = $form_state->getValue('trumba_promo_control_spud_configuration');
    $this->configuration['trumba_promo_control_web_name'] = $form_state->getValue('trumba_promo_control_web_name');
    $this->configuration['trumba_promo_control_calendar_url'] = $form_state->getValue('trumba_promo_control_calendar_url');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $spud_id = Html::getUniqueId($this->getBaseId());

    $params = array(
      'webName' => $this->configuration['trumba_promo_control_web_name'],
      'spudType' => $this->configuration['trumba_promo_control_spud_type'],
      'spudConfig' => $this->configuration['trumba_promo_control_spud_configuration'],
      'teaserBase' => $this->configuration['trumba_promo_control_calendar_url'],
      'spudId' => $spud_id,
    );

    return _trumba_spud_embed($spud_id, $params);
  }

  /**
   * Checks to see if the block should be shown per permissions.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   * @return \Drupal\Core\Access\AccessResult
   */
  protected function blockAccess(AccountInterface $account) {
    // The block is visible to those that have permission to view trumba
    // spud blocks.
    return AccessResult::allowedIfHasPermission($account,'view trumba spud blocks');
  }

}
