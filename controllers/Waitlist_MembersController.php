<?php namespace Craft;

class Waitlist_MembersController extends BaseController
{
  protected $allowAnonymous = ['actionAdd'];

  public function actionAdd ()
  {
    $this->requirePostRequest();

    $member = new Waitlist_MemberModel;

    $email = craft()->request->getPost('email', null);
    $product = craft()->commerce_products->getProductById(craft()->request->getPost('productId'));

    if (!$product)
    {
      $this->returnErrorJson(Craft::t('The product does not exist.'));
    }

    $member->email = $email;
    $member->productId = $product->id;

    if (craft()->waitlist_members->saveMember($member))
    {
      $this->returnJson(['success' => true]);
    } else {
      $this->returnErrorJson($member->getErrors());
    }
  }

  public function actionIndex (array $variables = [])
  {
    $this->renderTemplate('waitlist/members/_index', $variables);
  }
}
