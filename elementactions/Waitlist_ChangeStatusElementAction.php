<?php

namespace Craft;

class Waitlist_ChangeStatusElementAction extends BaseElementAction
{
  public function getName()
  {
    return Craft::t('Set status');
  }

  public function getTriggerHtml()
  {
    $statuses = craft()->waitlist_members->getAllStatuses();

    return craft()->templates->render('waitlist/_elementactions/changeStatus', [
      'statuses' => $statuses
    ]);
  }

  public function performAction(ElementCriteriaModel $criteria)
  {
    // Get the selected group
    $status = $this->getParams()->status;

    // Make sure it's a valid group
    if (!craft()->waitlist_members->statusExists($status)) {
      $this->setMessage(Craft::t('The selected status doesn’t exist.'));
      return false;
    }

    // Set group of the selected ads
    foreach ($criteria->find() as $member) {
      craft()->waitlist_members->setMemberStatus($member, $status);
    }

    // Success!
    $this->setMessage(Craft::t('Changed to') . ' "' . craft()->waitlist_members->getStatusLabel($status) . '"');
    return true;
  }

  protected function defineParams()
  {
    return [
      'status' => [
        AttributeType::String,
        'required' => true
      ],
    ];
  }
}
