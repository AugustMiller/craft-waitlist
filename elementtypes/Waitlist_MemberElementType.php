<?php namespace Craft;

class Waitlist_MemberElementType extends BaseElementType
{
  public function getName()
  {
    return Craft::t('Waitlist Members');
  }

  public function hasContent()
  {
    return false;
  }

  public function hasTitles()
  {
    return false;
  }

  public function hasStatuses()
  {
    return true;
  }

  public function isLocalized()
  {
    return false;
  }

  public function getStatuses()
  {
    return [
      Waitlist_MemberStatus::ACTIVE => Craft::t('Active'),
      Waitlist_MemberStatus::CANCELED => Craft::t('Canceled'),
      Waitlist_MemberStatus::FULFILLED => Craft::t('Fulfilled'),
    ];
  }

  public function getSources($context = null)
  {

    return [
      '*' => [
        'label' => Craft::t('All members'),
        'defaultSort' => ['dateCreated', 'desc']
      ]
    ];
  }

  public function getAvailableActions($source = null)
  {
    $deleteAction = craft()->elements->getAction('Delete');
    $deleteAction->setParams([
      'confirmationMessage' => Craft::t('Are you sure you want to delete the selected waitlist members?'),
      'successMessage' => Craft::t('Members deleted.'),
    ]);

    $actions[] = $deleteAction;

    $actions[] = craft()->elements->getAction('Waitlist_ChangeStatus');


    return $actions;
  }

  public function defineCriteriaAttributes()
  {
    return [
      'email' => AttributeType::String,
      'subjectId' => AttributeType::Mixed,
      'status' => AttributeType::String
    ];
  }

  public function defineAvailableTableAttributes()
  {
    return [
      'email' => Craft::t('Email'),
      'subject' => Craft::t('Subject'),
      'dateCreated' => Craft::t('Date Created')
    ];
  }

  public function getDefaultTableAttributes($source = null)
  {
    return $this->defineAvailableTableAttributes();
  }

  public function defineSortableAttributes()
  {
    return [
      'email' => Craft::t('Email Address'),
      'elements.dateCreated' => Craft::t('Date Created')
    ];
  }

  public function defineSearchableAttributes()
  {
    return [
      'email'
    ];
  }

  public function getTableAttributeHtml(BaseElementModel $element, $attribute)
  {
    switch ($attribute) {
      case 'email':
        return $element->email;
        break;
      case 'subject':
        return craft()->templates->render('_elements/element', [
          'element' => $element->getSubjectElement()
        ]);
      default:
        return parent::getTableAttributeHtml($element, $attribute);
    }
  }

  public function modifyElementsQuery(DbCommand $query, ElementCriteriaModel $criteria)
  {
    $query
      ->addSelect('waitlist_members.*')
      ->join('waitlist_members waitlist_members', 'waitlist_members.id = elements.id');

    if ($criteria->status) {
      $query->andWhere(DbHelper::parseParam('waitlist_members.status', $criteria->status, $query->params));
    }
  }

  public function getEditorHtml(BaseElementModel $element)
  {
    return '';
  }

  public function populateElementModel($row)
  {
    return Waitlist_MemberModel::populateModel($row);
  }
}
