<?php

namespace ride\web;

use ride\library\config\Config;
use ride\library\event\Event;
use ride\library\mvc\Response;
use ride\library\mvc\view\HtmlView;
use ride\library\i18n\I18n;


class HelpScoutBeacon  {

    public function __construct(Response $response, I18n $i18n) {
        $this->response = $response;
        $this->I18n = $i18n;
    }

    public function enqueueJavascript(Event $event, Config $config) {

        $formId = $config->get('helpscout.beacon.form.id');
        $docsDomain = 'http://' . $config->get('helpscout.beacon.subdomain') . '.helpscoutdocs.com';
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
                        color: "#cacaca",
                        translation: {
                            "searchLabel": "' . $translator->translate('beacon.searchLabel') . '",
                            "searchErrorLabel": "' . $translator->translate('searchLabel') . '",
                            "noResultsLabel": "' . $translator->translate('searchLabel') . '",
                            "contactLabel": "' . $translator->translate('searchLabel') . '",
                            "attachFileLabel": "' . $translator->translate('searchLabel') . '",
                            "attachFileError": "' . $translator->translate('searchLabel') . '",
                            "nameLabel": "' . $translator->translate('searchLabel') . '",
                            "nameError": "' . $translator->translate('searchLabel') . '",
                            "emailLabel": "' . $translator->translate('searchLabel') . '",
                            "emailError": "' . $translator->translate('searchLabel') . '",
                            "topicLabel": "' . $translator->translate('searchLabel') . '",
                            "topicError": "' . $translator->translate('searchLabel') . '",
                            "subjectLabel": "' . $translator->translate('searchLabel') . '",
                            "subjectError": "' . $translator->translate('searchLabel') . '",
                            "messageLabel": "' . $translator->translate('searchLabel') . '",
                            "messageError": "' . $translator->translate('searchLabel') . '",
                            "sendLabel": "' . $translator->translate('searchLabel') . '",
                            "contactSuccessLabel": "' . $translator->translate('searchLabel') . '",
                            "contactSuccessDescription": "' . $translator->translate('searchLabel') . '",
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