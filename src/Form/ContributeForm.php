<?php

/**
 * @file
 * @author Juan Ceballos
* Primarily Drupal hooks and global API functions.
*/

namespace Drupal\izertis\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\izertis\Controller\IzertisController;
use Drupal\Core\Link;
use Drupal\Core\Url;


/**
 * Contribute form.
 */

class ContributeForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'izertis_contribute_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $statistics = new IzertisController;


    $form['configuration'] = array(
      '#type' => 'details',
      '#title' => $this->t('Configuration'),
      '#open' => FALSE,
    );

     $form['domain_api_key'] = array(
      '#type' => 'textfield',
      '#title' => t('Domain Api Key'),
      '#required' => TRUE,
      '#default_value' =>  \Drupal::state()->get('domain_api_key'),
      '#description' => t('End Point: https://gateway.marvel.com'),
      '#group' => 'configuration',
    );

     $form['ts'] = array(
      '#type' => 'textfield',
      '#title' => t('Ts'),
      '#required' => TRUE,
      '#default_value' =>  \Drupal::state()->get('ts'),
      '#description' => t('Ts:1'),
      '#group' => 'configuration',
    );

     $form['public_key'] = array(
      '#type' => 'textfield',
      '#title' => t('Your public key'),
      '#required' => TRUE,
      '#default_value' =>  \Drupal::state()->get('public_key'),
      '#description' => t('Public Key: 2bcd09f088411e2acdd567eb83cfc5fb'), 
      '#group' => 'configuration',  
    );

     $form['private_key'] = array(
      '#type' => 'textfield',
      '#title' => t('Your private key'),
      '#required' => TRUE,
      '#default_value' =>  \Drupal::state()->get('private_key'),
      '#description' => t('Private Key: e68a355213763f121590edb34b716c7ddeab19d9'),
      '#group' => 'configuration',
    );


    $form['endpoints'] = array(
      '#type' => 'details',
      '#title' => $this->t('Endpoints'),
      '#open' => FALSE,
    );

     $form['endpoint_comics'] = array(
      '#type' => 'textfield',
      '#title' => t('Endpoint of Comics'),
      '#required' => TRUE,
      '#default_value' =>  \Drupal::state()->get('endpoint_comics'),
      '#description' => t('Endpoint of Comics: /v1/public/comics'),
      '#group' => 'endpoints',
    );

     $form['endpoint_characters'] = array(
      '#type' => 'textfield',
      '#title' => t('Endpoint of Characters'),
      '#required' => TRUE,
      '#default_value' =>  \Drupal::state()->get('endpoint_characters'),
      '#description' => t('Endpoint of Characters: /v1/public/characters'),
      '#group' => 'endpoints',
    );

    $form['hash'] = [
      '#type' => 'item',
      '#title' => t('Your hash is:'),
      '#markup' => $statistics->getToken(),
      '#group' => 'configuration',
    ];

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save'),
    );

    return $form;
  }

  /**
   * {@submitForm}
   */

  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      \Drupal::state()->set($key,$value);
    }
  }
}
?>