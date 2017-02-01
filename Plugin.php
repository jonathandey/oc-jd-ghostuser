<?php namespace JD\GhostUser;

use Auth;
use Event;
use Session;
use Backend;
use Redirect;
use BackendAuth;
use System\Classes\PluginBase;
use RainLab\User\Models\User as UserModel;
use October\Rain\Exception\ApplicationException;
use RainLab\User\Controllers\Users as UsersController;

/**
 * GhostUser Plugin Information File
 */
class Plugin extends PluginBase
{

    public $require = ['RainLab.User'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Ghost User',
            'description' => 'From the backend users list, login (ghostin) to any user account.',
            'author'      => 'JD',
            'icon'        => 'icon-user-secret'
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        UsersController::extend(function($controller) {
            $controller->addDynamicMethod('onGhostUser', function() {

                if($this->userHasGhostingPermissions() !== true) {
                    throw new ApplicationException('Ghosting access denied. Ghosting requires explicit permissions. Please set these in your Settings.');
                }

                $frontendUser = UserModel::findOrFail(post('userId'));
                Auth::login($frontendUser);

                Session::put('jd.ghostuser.ghosting', true);

                return Redirect::to('/');
            });
        });

        Event::listen('backend.list.extendColumns', function($widget) {

            // Only for the User controller
            if (!$widget->getController() instanceof \RainLab\User\Controllers\Users) {
                return;
            }

            // Only for the User model
            if (!$widget->model instanceof \RainLab\User\Models\User) {
                return;
            }

            // Require ghosting permission to see this
            if($this->userHasGhostingPermissions() !== true) {
                return;
            }

            // Add an extra birthday column
            $widget->addColumns([
                'jd.ghostuser.column' => [
                    'label' => 'Ghost',
                    'type' => 'partial',
                    'path' => '$/jd/ghostuser/partials/_ghost.htm',
                ]
            ]);
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            'JD\GhostUser\Components\GhostBanner' => 'ghostBanner',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'jd.ghostuser.allow_ghosting' => [
                'tab' => 'Ghost User',
                'label' => 'Allow Ghosting',
            ],
        ];
    }

    protected function userHasGhostingPermissions()
    {
        $backendUser = BackendAuth::getUser();
        return (! is_null($backendUser) && $backendUser->hasAccess('jd.ghostuser.allow_ghosting'));
    }

}
