<?php

namespace ride\library\helpscout;

use ride\library\i18n\translator\Translator;
use ride\library\security\model\User;

/**
 * Javascript generator of a Help Scout beacon
 */
class HelpScoutBeacon {

    /**
     * Default color for the beacon
     * @var string
     */
    const DEFAULT_COLOR = '#2980B9';

    /**
     * Default icon for the beacon
     * @var string
     */
    const DEFAULT_ICON = 'question';

    /**
     * Form id of the beacon
     * @var string
     */
    private $formId;

    /**
     * Hex value for the base color of the beacon
     * @var string
     */
    private $color;

    /**
     * Icon type for the beacon
     * @var string
     */
    private $icon;

    /**
     * Constructs a new help scout beacon
     * @param string $formId
     * @param string $color
     * @param string $icon
     * @return null
     */
    public function __construct($formId, $color = null, $icon = null) {
        $this->setFormId($formId);
        $this->setColor($color);
        $this->setIcon($icon);
    }

    /**
     * Sets the form id
     * @param string $formId
     * @return null
     */
    public function setFormId($formId) {
        if (empty($formId) || !is_string($formId)) {
            throw new InvalidArgumentException('Could not set form id: invalid value provided');
        }

        $this->formId = $formId;
    }

    /**
     * Gets the form id
     * @return string
     */
    public function getFormId() {
        return $this->formId;
    }

    /**
     * Sets the color
     * @param string $color Hex value for the base color
     * @return null
     */
    public function setColor($color) {
        if (empty($color)) {
            $color = self::DEFAULT_COLOR;
        }

        $this->color = $color;
    }

    /**
     * Gets the color
     * @return string Hex value of the base color
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * Sets the icon
     * @param string $icon Icon value: beacon, bouy, message, question or search
     * @return null
     */
    public function setIcon($icon) {
        if (empty($icon)) {
            $icon = self::DEFAULT_ICON;
        } elseif ($icon !== 'beacon' && $icon !== 'bouy' && $icon !== 'message' && $icon !== 'question' && $icon !== 'search') {
            throw new InvalidArgumentException('Could not set icon: invalid value provided, try beacon, buoy, message, question or search');
        }

        $this->icon = $icon;
    }

    /**
     * Gets the icon
     * @return string
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * Generates the javascript for this beacon
     * @param ride\library\i18n\translation\Translator $translator Translator
     * for the labels
     * @param ride\library\security\model\User $user User to prefill the form
     * @return string
     */
    public function generateJavascript(Translator $translator, User $user = null) {
        $javascript = '$(function (e, o, n) {
            window.HSCW = o, window.HS = n, n.beacon = n.beacon || {};
            var t = n.beacon;
            t.userConfig = {}, t.readyQueue = [], t.config = function (e) {
                this.userConfig = e
            },
            t.ready = function (e) {
                this.readyQueue.push(e);
            },
            o.config = {
                docs: {enabled: 0, baseUrl: "dummy"},
                contact: {enabled: 1, formId: "' . $this->formId . '"},
            };
            var r = e.getElementsByTagName("script")[0], c = e.createElement("script");
            c.type = "text/javascript", c.async = !0, c.src = "https://djtflbt20bdde.cloudfront.net/",
                r.parentNode.insertBefore(c, r)
        }(document, window.HSCW || {}, window.HS || {}
        ));
        HS.beacon.config({
            icon: "' . $this->icon . '",
            color: "' . $this->color . '",
            poweredBy: false,
            attachments: true,
            instructions: "' . $translator->translate('beacon.instructions')  .'",
            translation: {
                "searchLabel": "' . $translator->translate('beacon.searchLabel') . '",
                "searchErrorLabel": "' . $translator->translate('beacon.searchErrorLabel') . '",
                "noResultsLabel": "' . $translator->translate('beacon.noResultsLabel') . '",
                "contactLabel": "' . $translator->translate('beacon.contactLabel') . '",
                "attachFileLabel": "' . $translator->translate('beacon.attachFileLabel') . '",
                "attachFileError": "' . $translator->translate('beacon.attachFileError') . '",
                "nameLabel": "' . $translator->translate('beacon.nameLabel') . '",
                "nameError": "' . $translator->translate('beacon.nameError') . '",
                "emailLabel": "' . $translator->translate('beacon.emailLabel') . '",
                "emailError": "' . $translator->translate('beacon.emailError') . '",
                "topicLabel": "' . $translator->translate('beacon.topicLabel') . '",
                "topicError": "' . $translator->translate('beacon.topicError') . '",
                "subjectLabel": "' . $translator->translate('beacon.subjectLabel') . '",
                "subjectError": "' . $translator->translate('beacon.subjectError') . '",
                "messageLabel": "' . $translator->translate('beacon.messageLabel') . '",
                "messageError": "' . $translator->translate('beacon.messageError') . '",
                "sendLabel": "' . $translator->translate('beacon.sendLabel') . '",
                "contactSuccessLabel": "' . $translator->translate('beacon.contactSuccessLabel') . '",
                "contactSuccessDescription": "' . $translator->translate('beacon.contactSuccessDescription') . '",
            },
        });';

        if ($user) {
            $javascript .= '
            HS.beacon.ready(function() {
                HS.beacon.identify({name: "' . $user->getDisplayName() . '", email: "' . $user->getEmail() . '"});
            });';
        }

        return $javascript;
    }

}
