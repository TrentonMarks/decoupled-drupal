<?php

namespace Drupal\webform_validation\Validate;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Utility\WebformElementHelper;
use Drupal\webform\Utility\WebformArrayHelper;

/**
 * Form API callback. Validate element value.
 */
class WebformValidateConstraint {

  /**
   * Validates Backend fields.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The form state.
   */
  public static function validateBackendComponents(array $form, FormStateInterface &$formState): void {
    $valid = FALSE;
    $values = $formState->cleanValues()->getValues();
    if (!empty($values['properties']['equal']) && $values['properties']['equal'] == '1') {
      $equalComponents = array_filter($values['properties']['equal_components']);
      if (empty($equalComponents)) {
        $formState->setErrorByName('equal_components', 'Please select atleast 1 Equal components.');
      }
    }

    if (!empty($values['properties']['some_of_several']) && $values['properties']['some_of_several'] == '1') {
      $sosComponents = array_filter($values['properties']['some_of_several_components']);
      $countSosComponents = count($sosComponents) + 1;
      if ($countSosComponents <= 1) {
        $formState->setErrorByName('some_of_several_components', 'You need to select at least 2 several components.');
      }
    }

    if (!empty($values['properties']['compare']) && $values['properties']['compare'] == '1') {
      $compareComponents = $values['properties']['compare_components'];
      if (empty($compareComponents)) {
        $formState->setErrorByName('compare_components', 'Please select compare components.');
      }
    }
  }

  /**
   * Validates form.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The form state.
   */
  public static function validate(array &$form, FormStateInterface $formState): void {
    self::validateElements($form['elements'], $form, $formState);
  }

  /**
   * Validates element.
   *
   * @param array $elements
   *   The form elements.
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The form state.
   */
  private static function validateElements(array $elements, array &$form, FormStateInterface $formState): void {
    foreach ($elements as $keyElement => &$keyValue) {
      if (!WebformElementHelper::isElement($keyValue, $keyElement)) {
        continue;
      }
      if (!empty($keyValue['#equal'])) {
        self::validateFrontEqualComponent($keyValue, $formState, $form);
      }
      if (!empty($keyValue['#compare'])) {
        self::validateFrontCompareComponent($keyValue, $formState, $form);
      }
      if (!empty($keyValue['#some_of_several'])) {
        self::validateFrontSomeSeveralComponent($keyValue, $formState, $form);
      }
      self::validateElements($keyValue, $form, $formState);
    }
  }

  /**
   * Validates Equal components on front end.
   *
   * @param array $element
   *   The form element to process.
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The form state.
   * @param array $form
   *   The form array.
   */
  public static function validateFrontEqualComponent(array &$element, FormStateInterface $formState, array &$form): void {
    $webformKey = $element['#webform_key'];
    $equalComponents = $element['#equal_components'];
    $thisValue = is_array($formState->getValue($webformKey)) ? $formState->getValue($webformKey) : [$formState->getValue($webformKey)];
    $submittedValues = $formState->cleanValues()->getValues();
    $storage = $formState->getStorage();
    $visitedElements = !empty($storage['visited']) ? $storage['visited'] : [];
    $error = FALSE;
    foreach ($equalComponents as $key => $value) {
      // Equal component key.
      if (isset($form['elements'][$key])) {
        $fieldElement = $form['elements'][$key];
        $fieldElement['access'] = !empty($form['elements'][$key]['#access']);
        $fieldElement['multiple'] = !empty($form['elements'][$key]['#webform_multiple']);
      }
      elseif (!isset($form['elements'][$key])) {
        $found = FALSE;
        $fieldElement = [];
        self::getFormElementAccess($form['elements'], $key, $found, $fieldElement);
      }
      if (!empty($fieldElement['access']) && !in_array($key, $visitedElements)) {
        $visitedElements[] = $key;
      }
      if (!empty($element['#access']) && !in_array($webformKey, $visitedElements)) {
        $visitedElements[] = $webformKey;
      }
      // Equal component validation.
      if (!empty($thisValue[0]) && !empty($submittedValues[$key]) && array_key_exists($key, $submittedValues) && in_array($key, $visitedElements) && in_array($webformKey, $visitedElements)) {
        // Many to Many.
        if (!empty($fieldElement['multiple']) && $element['#webform_multiple'] == TRUE) {
          $result = array_intersect($thisValue, $submittedValues[$key]);
          if (empty($result) && !empty($submittedValues[$key])) {
            $error = TRUE;
            break;
          }
        }
        // One to Many.
        elseif (!empty($fieldElement['multiple'])) {
          if (!in_array($thisValue[0], $submittedValues[$key])) {
            $error = TRUE;
            break;
          }
        }
        // Many to One.
        elseif (!in_array($submittedValues[$key], $thisValue) && !empty($element['#webform_multiple'])) {
          $error = TRUE;
          break;
        }
        // One to One.
        elseif (!in_array($submittedValues[$key], $thisValue) && empty($element['#webform_multiple']) && empty($fieldElement['multiple'])) {
          $error = TRUE;
          break;
        }
      }
      elseif ($value != '0' && !empty($thisValue[0]) && in_array($key, $visitedElements) && empty($submittedValues[$key])) {
        $error = TRUE;
        break;
      }
      elseif ($value != '0' && empty($thisValue[0]) && !empty($submittedValues[$key]) && in_array($webformKey, $visitedElements)) {
        $error = TRUE;
        break;
      }
    }
    $storage['visited'] = $visitedElements;
    $formState->setStorage($storage);
    if ($error) {
      if (empty($fieldElement['access']) || (!empty($element['#access']) && empty($thisValue[0]))) {
        $fieldElement = $element;
        $content = "should not be Empty";
      }
      if (isset($fieldElement['#title'])) {
        $tArgs = [
          '%name' => empty($fieldElement['#title']) ? $fieldElement['#parents'][0] : $fieldElement['#title'],
          '%value' => $value,
          '%content' => !empty($content) ? $content : 'does not match',
          '%sourceName' => empty($content) ? empty($element['#title']) ? $element['#parents'][0] : $element['#title'] : '',
        ];
        $formState->setError(
            $fieldElement,
            t('%name %content %sourceName', $tArgs)
        );
      }
      else {
        $formState->setError($element);
      }
    }
  }

  /**
   * Validates compare fields.
   *
   * @param array $element
   *   The form elements.
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The form state.
   * @param array $form
   *   The form array.
   */
  public static function validateFrontCompareComponent(array &$element, FormStateInterface $formState, array &$form): void {
    $webformKey = $element['#webform_key'];
    $compareWithField = $element['#compare_components'];
    $compareOperator = $element['#compare_components_operator'];
    $compareErrorMsg = $element['#compare_components_custom_error'];
    $thisValue = is_array($formState->getValue($webformKey)) ? $formState->getValue($webformKey) : [$formState->getValue($webformKey)];
    $submittedValues = $formState->cleanValues()->getValues();
    $compareWithValue = is_array($submittedValues[$compareWithField]) ? $submittedValues[$compareWithField] : [$submittedValues[$compareWithField]];
    $storage = $formState->getStorage();
    $visitedElements = !empty($storage['visited']) ? $storage['visited'] : [];
    $found = FALSE;
    $fieldElement = [];
    self::getFormElementAccess($form['elements'], $compareWithField, $found, $fieldElement);
    if (!empty($fieldElement['access']) && !in_array($compareWithField, $visitedElements)) {
      $visitedElements[] = $compareWithField;
    }
    if (!empty($element['#access']) && !in_array($webformKey, $visitedElements)) {
      $visitedElements[] = $webformKey;
    }
    $error = FALSE;
    if ((!empty($compareWithValue[0]) || !empty($thisValue[0])) && in_array($compareWithField, $visitedElements) && in_array($webformKey, $visitedElements)) {
      if (empty($compareWithValue[0]) || empty($thisValue[0])) {
        $error = TRUE;
      }
      elseif (!empty($compareWithValue[0]) && !empty($thisValue[0])) {
        switch ($compareOperator) {
          case '>':
            if (!(min($compareWithValue) > max($thisValue))) {
              $error = TRUE;
            }
            break;

          case '>=':
            if (!(min($compareWithValue) >= max($thisValue))) {
              $error = TRUE;
            }
            break;

          case '<':
            if (!(max($compareWithValue) < min($thisValue))) {
              $error = TRUE;
            }
            break;

          case '<=':
            if (!(max($compareWithValue) <= min($thisValue))) {
              $error = TRUE;
            }
            break;
        }
      }
    }
    $storage['visited'] = $visitedElements;
    $formState->setStorage($storage);
    if ($error) {
      if (empty($fieldElement['access']) || (!empty($element['#access']) && empty($thisValue[0]))) {
        $fieldElement = $element;
      }
      if (isset($fieldElement['#title'])) {
        $formState->setError($fieldElement, $compareErrorMsg);
      }
      else {
        $formState->setError($fieldElement);
      }
    }
  }

  /**
   * Validates Some of Several in fields.
   *
   * @param array $element
   *   The form elements.
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The form state.
   * @param array $form
   *   The form array.
   */
  public static function validateFrontSomeSeveralComponent(array &$element, FormStateInterface $formState, array &$form): void {
    $flatValues = array_filter($formState->cleanValues()->getValues());
    $formObject = $formState->getFormObject();
    $webform = $formObject->getWebform();
    $storage = $formState->getStorage();
    $webformKey = $element['#webform_key'];
    $sosComponents = array_filter($element['#some_of_several_components']);
    $sosCompletedComponents = $element['#some_of_several_components_completed'];
    $sosFinalValidation = $element['#some_of_several_final_validation'];
    $performValidation = TRUE;
    if (!empty($sosFinalValidation)) {
      $pages = $webform->getPages();
      if (!empty($pages)) {
        $currentPage = $formState->get('current_page');
        $nextPage = WebformArrayHelper::getNextKey($pages, $currentPage);
        if ($nextPage != 'webform_confirmation') {
          $performValidation = FALSE;
        }
      }
    }
    unset($flatValues['submit']);
    $currentArray = [$webformKey => $webformKey];
    $sosComponentsRev = array_merge_recursive($sosComponents, $currentArray);
    $items = [];
    $itemsfound = 0;
    $visitedElements = !empty($storage['visited']) ? $storage['visited'] : [];
    foreach ($sosComponentsRev as $cid => $component) {
      if (in_array($cid, $visitedElements)) {
        if (!empty($flatValues[$cid])) {
          $items[$cid] = $flatValues[$cid];
        }
        $itemsfound++;
      }
      else {
        $found = $flag = FALSE;
        $fieldElement = [];
        self::getFormElementAccess($form['elements'], $cid, $found, $fieldElement, $flag);
        if ((!empty($fieldElement['access']))) {
          if (!empty($flatValues[$cid])) {
            $items[$cid] = $flatValues[$cid];
          }
          $visitedElements[] = $cid;
          $itemsfound++;
        }
      }
    }

    $storage['visited'] = $visitedElements;
    $formState->setStorage($storage);

    $compareNumber = (int) preg_replace('/[^0-9]+/', '', $element['#some_of_several_components_completed']);
    if ($compareNumber < 1) {
      $compareNumber = 1;
    }
    elseif ($compareNumber > count($sosComponentsRev)) {
      $compareNumber = count($sosComponentsRev);
    }
    $validationFlag = FALSE;
    if (substr($sosCompletedComponents, 0, 1) === '=') {
      if (($itemsfound >= $compareNumber)) {
        $validationFlag = TRUE;
      }
    }
    elseif (substr($sosCompletedComponents, 0, 1) === '<=') {
      if (($itemsfound > 0)) {
        $validationFlag = TRUE;
      }
    }
    else {
      if (($itemsfound >= $compareNumber)) {
        $validationFlag = TRUE;
      }
    }
    if ($validationFlag &&  $performValidation) {
      $numberCompleted = count($items);
      $severalComponents = [];
      $elements = $webform->getElementsInitializedAndFlattened();
      foreach ($elements as $elementKey => &$elementComponent) {
        if (in_array($elementComponent['#webform_key'], $sosComponentsRev)) {
          $severalComponents[$elementKey] = $elementComponent['#admin_title'];
        }
      }
      // Parse the comparision operator and do comparison.
      $error = FALSE;
      if (substr($sosCompletedComponents, 0, 1) === '=') {
        if (!($numberCompleted === $compareNumber)) {
          $verb = t('exactly');
          $error = TRUE;
        }
      }
      elseif (substr($sosCompletedComponents, 0, 2) === '<=') {
        if (!($numberCompleted <= $compareNumber)) {
          $verb = t('at most');
          $error = TRUE;
        }
      }
      else {
        if (!($numberCompleted >= $compareNumber)) {
          $verb = t('at least');
          $error = TRUE;
        }
      }
      if ($error) {
        $renderable = [
          '#theme' => 'item_list',
          '#items' => $severalComponents,
        ];
        $items = \Drupal::service('renderer')->render($renderable);
        $errorMessage = t('You must complete %verb %compare_number of these items: %items', [
          '%verb' => $verb,
          '%compare_number' => $compareNumber,
          '%items' => $items,
        ]);
        $formState->setError($element, $errorMessage);
      }
    }
  }

  /**
   * Check Element Access.
   *
   * @param array $elements
   *   The form elements.
   * @param string $searchKey
   *   Key of the equal component.
   * @param bool $found
   *   The key.
   * @param array $element
   *   Result array.
   */
  private static function getFormElementAccess(array &$elements, string $searchKey, bool &$found, array &$element): void {
    if (!$found) {
      $element['access'] = $element['multiple'] = FALSE;
      foreach ($elements as $keyElement => &$keyValue) {
        if (!WebformElementHelper::isElement($keyValue, $keyElement)) {
          continue;
        }
        if (!empty($keyElement) && $keyElement == $searchKey) {
          $found = TRUE;
          $element = $keyValue;
          if (!empty($keyValue['#access']) || !empty($keyValue['#visited'])) {
            $element['access'] = TRUE;
          }
          if (!empty($keyValue['#webform_multiple'])) {
            $element['multiple'] = TRUE;
          }
        }
        elseif (!$found) {
          self::getFormElementAccess($keyValue, $searchKey, $found, $element);
        }
      }
    }
  }

  /**
   * Go previous page.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The form state.
   */
  public static function formSubmitPervious(array &$form, FormStateInterface $formState): void {
    $storage = $formState->getStorage();
    $visitedElements = !empty($storage['visited']) ? $storage['visited'] : [];
    self::formElementUnset($form['elements'], $formState, $visitedElements);
    $storage['visited'] = $visitedElements;
    $formState->setStorage($storage);
  }

  /**
   * Removing the values in visited array while going back to previous page.
   *
   * @param array $elements
   *   The form elements.
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   The form state.
   * @param array $visitedElements
   *   Group of visited elements.
   */
  private static function formElementUnset(array &$elements, FormStateInterface $formState, array &$visitedElements): void {
    foreach ($elements as $key => &$value) {
      if (!WebformElementHelper::isElement($value, $key)) {
        continue;
      }
      if (!empty($value['#access'])) {
        if (($searchKey = array_search($key, $visitedElements)) !== FALSE) {
          array_splice($visitedElements, $searchKey, 1);
        }
      }
      self::formElementUnset($value, $formState, $visitedElements);
    }
  }

}
