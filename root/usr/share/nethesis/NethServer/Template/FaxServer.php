<?php

echo $view->header()->setAttribute('template', $T('FaxServer_Title'));
$general = $view->panel()
    ->setAttribute('title', $T('FaxServer_General_Title'))
    ->insert($view->textInput('CountryCode'))
    ->insert($view->textInput('AreaCode'))
    ->insert($view->textInput('FaxNumber'))
    ->insert($view->textInput('FaxName'))
;
$modem = $view->panel()
    ->setAttribute('title', $T('FaxServer_Modem_Title'))
    ->insert($view->fieldset()->setAttribute('template',$T('FaxDevice_label'))
        ->insert($view->fieldsetSwitch('FaxDeviceType', 'known', $view::FIELDSETSWITCH_EXPANDABLE)
            ->insert($view->selector('FaxDeviceKnown', $view::SELECTOR_DROPDOWN))
        )
        ->insert($view->fieldsetSwitch('FaxDeviceType', 'custom', $view::FIELDSETSWITCH_EXPANDABLE)
            ->insert($view->textInput('FaxDeviceCustom')->setAttribute('placeholder','ttyXXX'))
        )
    )
    ->insert($view->selector('Mode', $view::SELECTOR_DROPDOWN))
    ->insert($view->textInput('PBXPrefix'))
    ->insert($view->checkbox('WaitDialTone','enabled')->setAttribute('uncheckedValue', 'disabled'))
;
$notification = $view->panel()
    ->setAttribute('title', $T('FaxServer_Notification_Title'))
    ->insert($view->selector('DispatchFileTypeList', $view::SELECTOR_MULTIPLE))
    ->insert($view->fieldset()->setAttribute('template',$T('SendTo_label'))
        ->insert($view->fieldsetSwitch('SendToType', 'faxmaster'))
        ->insert($view->fieldsetSwitch('SendToType', 'custom', $view::FIELDSETSWITCH_EXPANDABLE)
            ->insert($view->textInput('SendToCustom'))
        )
    )
    ->insert($view->selector('NotifyFileTypeList', $view::SELECTOR_MULTIPLE))
    ->insert($view->selector('NotifyMaster'))
    ->insert($view->checkbox('SummaryReport','enabled')->setAttribute('uncheckedValue', 'disabled'))
;

$extra = $view->panel()
    ->setAttribute('title', $T('FaxServer_Extras_Title'))
    ->insert($view->checkbox('ClientShowReceived','enabled')->setAttribute('uncheckedValue', 'disabled'))
    ->insert($view->fieldsetSwitch('PrinteReceived', 'enabled', $view::FIELDSETSWITCH_CHECKBOX | $view::FIELDSETSWITCH_EXPANDABLE)
    ->setAttribute('uncheckedValue', 'disabled')
    ->insert($view->selector('PrinterName', $view::SELECTOR_DROPDOWN)))
    ->insert($view->checkbox('SambaFax','enabled')->setAttribute('uncheckedValue', 'disabled'))
    ->insert($view->checkbox('SendReport','enabled')->setAttribute('uncheckedValue', 'disabled'))
;

$tabs = $view->tabs()
    ->insert($general)
    ->insert($modem)
    ->insert($notification)
    ->insert($extra)
;

echo $tabs;

echo $view->buttonList($view::BUTTON_SUBMIT | $view::BUTTON_HELP);

