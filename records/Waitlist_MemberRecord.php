<?php namespace Craft;

class Waitlist_MemberRecord extends BaseRecord
{
  const TABLE_NAME = 'waitlist_members';

  public function getTableName()
  {
    return static::TABLE_NAME;
  }

  protected function defineAttributes()
  {
    $memberStatuses = [
      Waitlist_MemberStatus::ACTIVE,
      Waitlist_MemberStatus::CANCELED,
      Waitlist_MemberStatus::FULFILLED
    ];

    return [
      'status' => [
        'type' => AttributeType::Enum,
        'values' => $memberStatuses,
        'default' => Waitlist_MemberStatus::ACTIVE,
        'required' => true
      ],
      'email' => [
        'type' => AttributeType::Email,
        'required' => true
      ]
    ];
  }

  public function defineIndexes()
  {
    return [];
  }

  public function defineRelations()
  {
    return [
      'element'  => [
        static::BELONGS_TO,
        'ElementRecord',
        'id',
        'required' => true,
        'onDelete' => static::CASCADE
      ],
      'subject' => [
        static::BELONGS_TO,
        'ElementRecord',
        'required' => true,
        'onDelete' => static::CASCADE
      ]
    ];
  }

}
