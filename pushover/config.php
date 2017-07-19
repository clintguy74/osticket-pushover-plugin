<?php

require_once INCLUDE_DIR . 'class.plugin.php';

class PushoverPluginConfig extends PluginConfig {
    function getOptions() {        
        return array(
            'pushover' => new SectionBreakField(array(
                'label' => 'Pushover notifier',
            )),
            'pushover-api-token' => new TextboxField(array(
                'label' => 'Pushover API Token',
                'configuration' => array('size'=>100, 'length'=>200),
            )),
			'pushover-user-key' => new TextboxField(array(
                'label' => 'Pushover User Key',
                'configuration' => array('size'=>100, 'length'=>200),
            )),
        );
    }	
}
