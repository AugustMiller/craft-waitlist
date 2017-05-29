<?php namespace Craft;

class WaitlistPlugin extends BasePlugin
{
  public function getName()
  {
    return Craft::t('Waitlist');
  }

  public function getVersion()
  {
    return '1.0';
  }

  public function getDeveloper()
  {
    return 'oof. Studio';
  }

  public function getDeveloperUrl()
  {
    return 'https://oof.studio/';
  }

  public function getDescription()
  {
    return 'A basic plugin for store managers that allows them to collect email addresses and notify customers when products are available.';
  }

  public function hasCpSection()
  {
    return true;
  }

  public function registerCpRoutes()
  {
    return [
      'waitlist' => ['action' => 'waitlist/members/index']
    ];
  }

  public function init()
  {
    Craft::import('plugins.waitlist.enums.Waitlist_MemberStatus');

    if (craft()->request->isCpRequest() && craft()->userSession->isLoggedIn())
    {
      craft()->templates->includeCssResource('waitlist/css/styles.css');
    }
  }
}
