<?php

namespace Drupal\field_formatter_task\Plugin\Field\FieldFormatter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'custom_integer_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "custom_integer_formatter",
 *   label = @Translation("Custom Integer Formatter"),
 *   field_types = {
 *     "integer"
 *   }
 * )
 */
class IntegerFieldFormat extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'concat' => 'value after dividing by 100 :  ',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form['concat'] = [
      '#type' => 'textfield',
      '#title' => 'Concatenate with',
      '#default_value' => $this->getSetting('concat'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t("concatenate with : @concat", ["@concat" => $this->getSetting('concat')]);
    return $summary;
  }
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $value = $item->value / 100;
      $concat = $this->getSetting('concat');
      $elements[$delta] = [
        '#markup' => '<p>' . $concat . $value . '</p>',
      ];
    }

    return $elements;
  }
}
