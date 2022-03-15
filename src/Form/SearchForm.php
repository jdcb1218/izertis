<?php

namespace Drupal\izertis\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\izertis\Controller\IzertisController;

/**
 * Class SearchForm.
 */
class SearchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'marvel_ajax_form_select';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['option_marvel'] = [
      '#title' => $this->t('Please Select'),
      '#type' => 'select',
      '#options' => [
        'comics' => 'Comics',
        'characters' => 'Characters',
      ],
      '#empty_option' => $this->t('- Select a item-'),
      '#ajax' => [
        'callback' => '::updateDataService',
        'wrapper' => 'marvel-wrapper',
      ],
    ];

    $form['#cache'] = ['max-age' => 0];

    $form['ws_wrapper'] = [
      '#type' => 'table',
      '#attributes' => ['id' => 'marvel-wrapper'],
      '#header' => array('Favority','Count', 'Id', 'Title', 'Url', 'Date'),
    ];

    $option_marvel = $form_state->getValue('option_marvel');
   
    if (!empty($option_marvel)) {
        $izertis = new IzertisController;

        switch ($option_marvel) {
            case 'comics':
                $data['commics'] =$izertis->get_comics();
                $storage = json_decode($data['commics'], true);                
                break;
            case 'characters':
                $data['characters'] =$izertis->get_characters();
                $storage = json_decode($data['characters'], true);                
                break;            
        }

      $uid = \Drupal::currentUser()->id();
        
      foreach ($storage['data']['results'] as $key => $value) {

        $form['ws_wrapper'][$key][$option_marvel]['favorite'] = [
          '#type' => 'checkbox',
          '#default_value' => \Drupal::state()->get($uid.$option_marvel.'ws_wrapper'.$key),
         ];

          $form['ws_wrapper'][$key]['count'] = [
            '#type' => 'item',
            '#markup' => $key+1,
          ];

          $form['ws_wrapper'][$key]['id'] = [
            '#type' => 'item',
            '#markup' => $value['id'],
          ];

          $form['ws_wrapper'][$key]['title'] = [
            '#type' => 'item',
            '#markup' => isset($value['title']) ? $value['title'] : $value['name'],
          ];

          $form['ws_wrapper'][$key]['url'] = [
            '#type' => 'item',
            '#markup' => $value['resourceURI'],
          ];

          $form['ws_wrapper'][$key]['date'] = [
            '#type' => 'item',
            '#markup' => $value['modified'],
          ];
      }
    }

    // Add a submit button that handles the submission of the form.
    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
      ],
    ];

    return $form;
  }


  /**
   * {@inheritdoc}
   */

  public function submitForm(array &$form, FormStateInterface $form_state) {
      
      $uid = \Drupal::currentUser()->id();

      $user = \Drupal\user\Entity\User::load($uid);
      $current_user = \Drupal::currentUser();
      $name =  $current_user->getDisplayName();
    
      \Drupal::messenger()->addStatus(t('Updated data for the user:') .$name);

      foreach ($form_state->getValues() as $key => $value) {
        if ($key == 'option_marvel') {
            $option_marvel = $value;
        }
        if ($key == 'ws_wrapper') {
           foreach ($value as $nkey => $result) {
                $hash = $uid.$option_marvel.$key.$nkey;
                \Drupal::state()->set($hash,$result[$option_marvel]['favorite']);
           }
         }
      }
  }

  /**
   * Ajax callback.
   */

  public function updateDataService(array $form, FormStateInterface $form_state) {
    return $form['ws_wrapper'];
  }
}

?>