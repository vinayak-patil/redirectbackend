<?php
/**
  * @copyright   Copyright (C) 2017 - 2020 pageup.gr, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\Route;

class plgSystemRedirectbackend extends CMSPlugin
{
    /**
     * Event triggered after a user logs in.
     *
     * @return  void
     */
    public function onUserAfterLogin()
    {
        $app = Factory::getApplication();

        if ($app->isClient('administrator')) 
        {
            $user = Factory::getUser();
            $groups = array_values($user->get('groups', []));
            // Get the plugin parameters
            $plugin = $this->params;
            $groupsToRedirect = $plugin->get('usergroup_dashboard');
            if (!empty($groupsToRedirect)) {
                foreach ($groupsToRedirect as $item) {
                    $usergroup = $item->params->usergroup;
                    $dashboard = $item->params->dashboard;                    
                    if (in_array($usergroup,$groups)) 
                    {
                        $url = Route::_('index.php?' . $dashboard);
                        $app->redirect(html_entity_decode($url));
                        break; // Redirect once for the first matching group
                    }
                }
            }
        }
    }
}
