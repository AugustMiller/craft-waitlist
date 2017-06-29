<?php namespace Craft;

class Waitlist_MembersService extends BaseApplicationComponent
{
  public function getAllMembers()
  {
    $criteria = craft()->elements->getCriteria('Waitlist_Member');
    $criteria->setAttribute('limit', 0);

    return $criteria;
  }

  public function saveMember(Waitlist_MemberModel $member)
  {
    $isNewMember = !$member->id;

    // Ad data
    if (!$isNewMember)
    {
      $record = Waitlist_MemberRecord::model()->findById($member->id);

      if (!$record)
      {
        throw new Exception(Craft::t('No member exists with the ID “{id}”', [
          'id' => $member->id
        ]));
      }
    }
    else
    {
      $record = new Waitlist_MemberRecord();
    }

    $record->email = $member->email;
    $record->subjectId = $member->subjectId;

    $record->validate();
    $member->addErrors($record->getErrors());

    if (!$member->hasErrors())
    {
      $this->onBeforeSaveMember(new Event($this, [
        'member' => $member,
        'isNewMember' => $isNewMember
      ]));

      if (craft()->elements->saveElement($member))
      {
        $record->id = $member->id;
        $record->status = $member->status;
        $record->save(false);

        $this->onSaveMember(new Event($this, [
          'member' => $member,
          'isNewMember' => $isNewMember
        ]));

        return true;
      }
    }

    return false;
  }

  public function setMemberStatus(Waitlist_MemberModel $member, $status)
  {
    $member->status = $status;
    return $this->saveMember($member);
  }

  public function onBeforeSaveMember(Event $event)
  {
    $this->raiseEvent('onBeforeSaveMember', $event);
  }

  public function onSaveMember(Event $event)
  {
    $this->raiseEvent('onSaveMember', $event);
  }

  public function getAllStatuses()
  {
    return craft()->elements->getElementType('waitlist_member')->getStatuses();
  }

  public function statusExists($status)
  {
    $statuses = $this->getAllStatuses();

    return in_array($status, array_keys($statuses));
  }

  public function getStatusLabel($status)
  {
    if ($this->statusExists($status))
    {
      return $this->getAllStatuses()[$status];
    }
  }

  public function getTotalMembers($status = null)
  {
    return $this->getAllMembers()->count();
  }
}
