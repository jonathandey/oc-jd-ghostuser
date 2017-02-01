<?php namespace JD\GhostUser\Components;

use Session;
use Cms\Classes\ComponentBase;

class GhostBanner extends ComponentBase
{

    public $userGhosting = false;

    public function componentDetails()
    {
        return [
            'name'        => 'Ghost Banner',
            'description' => 'Places a banner at the top of the page to indicate your ghostly activities',
        ];
    }

    public function init()
    {
        if(Session::has('jd.ghostuser.ghosting')) {
            $this->userGhosting = Session::get('jd.ghostuser.ghosting');
        }
    }

    public function onRun()
    {
        if($this->userGhosting === true) {
            $this->addJs('assets/js/jd-ghostuser.js');
            $this->addCss('assets/css/jd-ghostuser.css');
        }
    }

}