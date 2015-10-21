<?php

namespace ride\service;

use ride\library\config\Config;
use ride\library\event\Event;
use ride\library\mvc\view\HtmlView;
use ride\library\mvc\Response;
use ride\library\helpscout\HelpScoutBeacon;
use ride\library\i18n\I18n;
use ride\library\security\model\User;
use ride\library\security\SecurityManager;

/**
 * Service to add a Help Scout beacon to your response
 */
class HelpScoutBeaconService {

    /**
     * Name of the form id parameter
     * @var string
     */
    const PARAM_FORM_ID = 'helpscout.beacon.form.id';

    /**
     * Name of the color parameter
     * @var string
     */
    const PARAM_COLOR = 'helpscout.beacon.color';

    /**
     * Name of the icon parameter
     * @var string
     */
    const PARAM_ICON = 'helpscout.beacon.icon';

    /**
     * Constructs a new help scout beacon service
     * @param \ride\library\mvc\Response $response
     * @param \ride\library\i18n\I18n $i18n
     * @param \ride\library\security\SecurityManager $securityManager
     * @param \ride\library\config\Config $config
     * @return null
     */
    public function __construct(Response $response, I18n $i18n, SecurityManager $securityManager, Config $config) {
        $this->response = $response;
        $this->i18n = $i18n;
        $this->securityManager = $securityManager;
        $this->config = $config;
        $this->beacon = false;
    }

    /**
     * Gets the Help Scout beacon used by this server
     * @return \ride\library\helpscout\HelpScoutBeacon|null
     */
    public function getBeacon() {
        if ($this->beacon !== false) {
            return $this->beacon;
        }

        $formId = $this->config->get(self::PARAM_FORM_ID);
        if (!$formId) {
            $this->beacon = null;

            return null;
        }

        $color = $this->config->get(self::PARAM_COLOR);
        $icon = $this->config->get(self::PARAM_ICON);

        $this->beacon = new HelpScoutBeacon($formId, $color, $icon);

        return $this->beacon;
    }

    /**
     * Handler for the event
     * @param \ride\library\event\Event $event
     * @return null
     */
    public function handleEvent(Event $event) {
        $beacon = $this->getBeacon();
        $user = $this->securityManager->getUser();
        if ($beacon && $user) {
            $this->addJavascriptToResponse($beacon, $user);
        }
    }

    /**
     * Adds the javascript to the response
     * @return null
     */
    public function addJavascriptToResponse(HelpScoutBeacon $beacon, User $user = null) {
        $view = $this->response->getView();
        if (!$view instanceof HtmlView) {
            return;
        }

        $javascript = '});' . $beacon->generateJavascript($this->i18n->getTranslator(), $user) . '$(function() {';

        $view->addInlineJavascript($javascript);
    }

}
