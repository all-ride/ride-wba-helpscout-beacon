<?php

namespace ride\web;

use ride\library\config\Config;
use ride\library\event\Event;
use ride\library\mvc\Response;
use ride\library\mvc\view\HtmlView;
use ride\library\i18n\I18n;


class HelpScoutBeacon  {

    public function __construct(Response $response, I18n $i18n, Config $config) {

        $this->config = $config;
        $this->response = $response;
        $this->I18n = $i18n;

    }

    public function enqueueJavascript(Event $event) {

        if(null === $this->config->get('helpscout.beacon.form.id')) {
            return;
        }

        $formId = $this->config->get('helpscout.beacon.form.id');
        $docsDomain = 'http://' . $this->config->get('helpscout.beacon.subdomain') . '.helpscoutdocs.com';
        $translator = $this->I18n->getTranslator();

        $view = $this->response->getView();
        if ($view instanceof HtmlView) {
            $view->addInlineJavascript('
            });
            $(function (e, o, n) {
                window.HSCW = o, window.HS = n, n.beacon = n.beacon || {};
                var t = n.beacon;
                t.userConfig = {}, t.readyQueue = [], t.config = function (e) {
                    this.userConfig = e
                },
                    t.ready = function (e) {
                        this.readyQueue.push(e)
                    },
                    o.config = {
                        docs: {enabled: 0, baseUrl: "' . $docsDomain . '"},
                        contact: {enabled: 1, formId: "' . $formId . '"},
                        icon: "question",
                        color: "#2980B9",
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
                    };
                var r = e.getElementsByTagName("script")[0], c = e.createElement("script");
                c.type = "text/javascript", c.async = !0, c.src = "https://djtflbt20bdde.cloudfront.net/",
                    r.parentNode.insertBefore(c, r)
            }(document, window.HSCW || {}, window.HS || {}
            )); $(function() { ');
        }
    }
}