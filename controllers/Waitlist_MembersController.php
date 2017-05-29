<?php namespace Craft;

class Waitlist_MembersController extends BaseController
{
  protected $allowAnonymous = ['actionAdd'];

  public function actionAdd ()
  {
    $this->requirePostRequest();

    $member = new Waitlist_MemberModel;

    $email = craft()->request->getPost('email', null);
    $subject = craft()->elements->getElementById(craft()->request->getPost('subjectId'));

    if (!$subject)
    {
      $this->returnErrorJson(Craft::t('The element does not exist.'));
    }

    $member->email = $email;
    $member->subjectId = $subject->id;

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
