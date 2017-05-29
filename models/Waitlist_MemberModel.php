<?php namespace Craft;

/**
 * Class Waitlist_MemberModel
 *
 * @property int $ownerId
 * @property enum $status
 * @property string $email
 *
 * @package Craft
 */
class Waitlist_MemberModel extends BaseElementModel
{
  protected $elementType = 'Waitlist_Member';

  public function __toString ()
  {
    return (string)$this->email;
  }

  public function getStatus()
  {
    return $this->status;
  }

  protected function defineAttributes()
  {
    $memberStatuses = [
      Waitlist_MemberStatus::ACTIVE,
      Waitlist_MemberStatus::CANCELED,
      Waitlist_MemberStatus::FULFILLED
    ];

    return array_merge(parent::defineAttributes(), [
      'status' => [
        'type' => AttributeType::Enum,
        'values' => $memberStatuses,
        'default' => Waitlist_MemberStatus::ACTIVE,
        'required' => true
      ],
      'productId' => [
        'type' => AttributeType::Number,
        'required' => true
      ],
      'email' => [
        'type' => AttributeType::Email,
        'required' => true
      ]
    ]);
  }

  public function getProduct()
  {
    return craft()->commerce_products->getProductById($this->productId);
  }
}
